<?php include_once('functions/function_auth_user.php'); ?>
<?php include_once('inc/OptionQueries/unit_queries.php'); ?>
<?php include_once('inc/OptionQueries/ship_queries.php'); ?>

<?
	$canEdit = false;
	
	if (($_SESSION['sess_userrole'] == "admin") ||
			($_SESSION['sess_userrole'] == "officer"))
	{
		$canEdit = true;
	}
?>

<?php include_once('inc/ListQueries/operation_queries.php'); ?>

<?
	$displayMainActionButtons = "";
	$displayCreateOperationButton = "";
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
					<img height=\"20px\" class=\"adminButtonImage\" src=\"http://sc.vvarmachine.com/images/misc/button_edit.png\">
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
					<img height=\"20px\" class=\"adminButtonImage\" src=\"http://sc.vvarmachine.com/images/misc/button_add.png\">
					Create New Operation Template
				</button>
			</div>		
		";
	}


	$connection->close();
?>
<h2 id="MainPageHeaderText">Mission Plans</h2>
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
			<? echo $display_dateSorted_missionsList; ?>
		</div>
	</div>
	
	<!--Forms-->

</div>
  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>
<script type="text/javascript" src="/js/jquery.jScale.js">
</script>
<script type="text/javascript" src="http://www.wduffy.co.uk/blog/wp-content/themes/agregado/js/jquery.jscroll.min.js">
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