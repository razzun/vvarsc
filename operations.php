<?php include_once('functions/function_auth_user.php'); ?>
<?php include_once('inc/OptionQueries/unit_queries.php'); ?>
<?php include_once('inc/OptionQueries/ship_queries.php'); ?>

<?
	$canEdit = false;
	$OperationID = 0;
	$MissionID = 0;
	$MaintainEdit = 'false';
	$unit_id = 0;
	
	if (($_SESSION['sess_userrole'] == "admin") ||
			($_SESSION['sess_userrole'] == "officer"))
	{
		$canEdit = true;
	}
	
	if ($_SESSION['maintain_edit'] == 'true')
		$MaintainEdit = 'true';
	else
		$MaintainEdit = 'false';
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
				padding-left: 8px;
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
		
		$displayCreateOperationButton .= "
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

	$connection->close();
?>
<h2 id="MainPageHeaderText">Operation Plan Templates</h2>
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
		-->
		<? echo $displayMainActionButtons; ?>
	</div>
	<div class="tbinfo_container">
		<? echo $displayCreateOperationButton; ?>
		<div id="operations_list_menu_full">
			<div class="top-line-yellow">
			</div>
			<div class="partialBorder-left-orange1 border-left border-top1px border-4px">
			</div>
			<div class="operations_menu_inner_items_container">
				<? echo $display_operationsList; ?>
			</div>
			<div class="partialBorder-left-orange1 border-right border-bottom1px border-4px">
			</div>
			<div class="partialBorder-right-orange1 border-left border-bottom1px border-4px">
			</div>
		</div>
	</div>
	
	<!--Forms-->
	<!--Create Operation Form-->
	<div id="dialog-form-create-operation" class="adminDialogFormContainer" style="max-width:80%;min-width:50%">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Enter Operation Template Main Info Below</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/operations/function_operation_createOpTemplate.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">				
				<label for="OperationName" class="adminDialogInputLabel">
					Operation Name
				</label>
				<input type="text" name="OperationName" id="OperationName" value="" class="adminDialogTextInput" required autofocus>
				
				<label for="OperationType" class="adminDialogInputLabel">
					Operation Type
				</label>
				<input type="text" name="OperationType" id="OperationType" value="" class="adminDialogTextInput" required>
				
				<label for="StartingLocation" class="adminDialogInputLabel">
					Starting Location
				</label>
				<input type="text" name="StartingLocation" id="StartingLocation" value="" class="adminDialogTextInput" required>
				
				<label for="MissionSummary" class="adminDialogInputLabel">
					Mission Summary
				</label>
				<textarea name="MissionSummary" id="MissionSummary" class="adminDialogTextArea" required></textarea>
				
				<label for="ObjectiveDetails" class="adminDialogInputLabel">
					Objective Details
				</label>
				<textarea name="ObjectiveDetails" id="ObjectiveDetails" class="adminDialogTextArea" required></textarea>
			</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>	
		</form>
	</div>	

</div>
  
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>
<script type="text/javascript" src="/js/jquery.jScale.js">
</script>
<script type="text/javascript" src="<? $link_base; ?>/js/jquery.jscroll.min.js">
</script>

<!--FORM CONTROLS-->
<script>
	function resizeInput() {
		$(this).attr('size', $(this).val().length);
	}
	
	$(document).ready(function() {
		var dialog = $('#dialog-form');
		dialog.hide();
		
		//Set TextArea Input Height to Correct Values
		$("textarea").height( $("textarea")[0].scrollHeight );
		
		$('input[type="text"]')
		// event handler
		.keyup(resizeInput)
		// resize on page load
		.each(resizeInput);
	});
	
	$(function() {
	
		var overlay = $('#overlay');
		
		//Edit Operation
		$('#ButtonAddOpTemplate').click(function() {
			var dialog = $('#dialog-form-create-operation');
			
			var $self = jQuery(this);
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
		});
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('select').find('option').prop('selected',false);
			
			//Hide All Dialog Containers
			$('#dialog-form-create-operation').hide();
			
			overlay.hide();
			$('.operation_main_container').css({
				filter: 'none'
			});
			$('#MainPageHeaderText').css({
				filter: 'none'
			});
		});	
	});
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