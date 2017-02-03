<?php include_once('functions/function_auth_user.php'); ?>
<?php include_once('inc/OptionQueries/unit_queries.php'); ?>
<?php include_once('inc/OptionQueries/ship_queries.php'); ?>

<?
	$OperationID = 0;
	$MissionID = 0;
	$MaintainEdit = "false";

	$unit_id = strip_tags(isset($_GET['unit']) ? $_GET['unit'] : '');
	if ($unit_id == null)
		$unit_id = 0;

	$canEdit = false;
	
	if (($_SESSION['sess_userrole'] == "admin") ||
			($_SESSION['sess_userrole'] == "officer"))
	{
		$canEdit = true;
	}
?>

<?php 
	if(is_numeric($unit_id))
	{
		include_once('inc/ListQueries/operation_queries.php');
	}
?>

<?
	$displayMainActionButtons = "";
	$displayCreateOpTemplateButton = "";
	if ($canEdit)
	{
		$displayMainActionButtons = "
			<div id=\"MainActionButtons\" style=\"
				display: table-cell;
			\">
				<button id=\"ButtonEditToggle\" class=\"adminButton adminButtonEdit\" title=\"Edit Toggle\"style=\"
					margin-left: 0px;
					margin-right: 2%;
				\">
					<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
					Toggle Edit Options
				</button>				
			</div>
		";
		
		$displayCreateOpTemplateButton .= "
			<div id=\"OpTemplateCreateButtons\" style=\"
				width: 100%;
				text-align: right;
			\">
				<button id=\"ButtonAddOpTemplate\" class=\"adminButton adminButtonCreate\" title=\"Add Unit\"style=\"
					margin-left: 0px;
					margin-right: 2%;
				\">
					<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
					Create New Operation Template
				</button>
			</div>		
		";
	}
	
	//For Display of Unit Name
	if ($unit_id > 0)
	{
		$unitNameQuery = "
			select
				case
					when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
					else u.UnitFullName
				end as UnitName
			from projectx_vvarsc2.Units u
			where u.UnitID = $unit_id
		";
		
		$unitNameQuery_results = $connection->query($unitNameQuery);
		$displayUnitName = "";
		
		while(($row = $unitNameQuery_results->fetch_assoc()) != false) {
			$UnitNameDisplay = $row['UnitName'];
			
			$displayUnitName .= "
				<h4 style=\"
					padding-top: 0px;
					padding-bottom: 0px;
				\">
					$UnitNameDisplay
					<a href=\"$link_base/missions/\" style=\"
						text-decoration: none;
					\">
						<button class=\"adminButton adminButtonDelete\" title=\"Clear Filter\">
							<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
							Clear Filter
						</button>
					</a>
				</h4>			
			";
		}
		
	}

	$connection->close();
?>
<h2 id="MainPageHeaderText">Mission Plans</h2>
<?echo $displayUnitName; ?>
<div id="TEXT">
	<div id="operations_topMenu_container">
		<!--
		<div class="div_filters_container" id="filtersContainer_OpMenu_Show" style="
			display:table-cell;
			width: auto;
		">
			<div class="div_filters_entry">
				<div id="operations_menu_toggle_on">
					Operation Templates
				</div>
			</div>
			<div class="div_filters_entry" style="margin-left: 8px">
				<div id="missions_menu_toggle_on">
					Missions
				</div>
			</div>
		</div>
		<? echo $displayMainActionButtons; ?>
		-->
	</div>
	<div class="tbinfo_container">
		<div id="operations_list_menu_full">
			<div class="top-line-yellow">
			</div>
			<div class="partialBorder-left-orange1 border-left border-top1px border-4px">
			</div>
			<? echo $display_dateSorted_missionsList; ?>
			<div class="partialBorder-left-orange1 border-right border-bottom1px border-4px">
			</div>
			<div class="partialBorder-right-orange1 border-left border-bottom1px border-4px">
			</div>
		</div>
	</div>
	
	<!--Forms-->

</div>
  
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>
<script type="text/javascript" src="/js/jquery.jScale.js">
</script>
<script type="text/javascript" src="<? $link_base; ?>/js/jquery.jscroll.min.js">
</script>

<!--Link CONTROLS-->
<script>
	$(document).ready(function($) {
		$(".operationsListItemContainer").click(function() {
			window.document.location = $(this).data("url");
		});
	});	
</script>

<!--Script to Show/Hide Admin Buttons-->
<!--
<script>
	//Hide all Admin Buttons on Page Load
	$(document).ready(function() {
		var enableEdit = "<? echo $MaintainEdit; ?>";
		
		if (enableEdit == 'true')
		{
			$('#ButtonEditToggle').addClass("operations_toggleSelected");
			$('.tbinfo_container').find('.adminButton').show();
		}
		else
		{	
			$('#ButtonEditToggle').removeClass("operations_toggleSelected");
			$('.tbinfo_container').find('.adminButton').hide();
		}
    });
	
	$('#ButtonEditToggle').click(function() {
		var $self = jQuery(this);
		
		if ($self.hasClass("operations_toggleSelected"))
		{
			//Toggle is already Enabled - close it!
			$self.removeClass("operations_toggleSelected");
			$('.tbinfo_container').find('.adminButton').hide();
		}
		else
		{
			//Toggle is Disabled - open it!
			$self.addClass("operations_toggleSelected");
			$('.tbinfo_container').find('.adminButton').show();
		}
		
	});
	
	//adminButton
</script>
-->

<!--Script to Keep TopMenu Fixed While Scrolling Vertically-->
<script>
	var currentScroll;
	var fixmeTop = $('.tbinfo_container').offset().top;
	var divToScroll = $('#operations_topMenu_container');
	
	$(window).on( 'scroll', function(){
		currentScroll = $(window).scrollTop();
		
		if (currentScroll <= fixmeTop)
		{
			divToScroll
				.stop()
				.css({
				"position": 'relative',
				"top": 'unset',
				"left": 'unset',
				"z-index": '99',
				"background": 'none',
				"border-bottom": 'none'
			});			
		}
		else
		{
			divToScroll
				.stop()
				.css({
				"position": 'fixed',
				"top": '0px',
				"left": '0px',
				"z-index": '99',
				"background": 'rgba(0, 0, 0, 0.85)',
				"border-bottom": '1px solid rgba(0, 153, 170, 0.9)'
			});			
		}
		

	});

</script>