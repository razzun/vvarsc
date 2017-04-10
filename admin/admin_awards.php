<?php include_once('../functions/function_auth_admin.php'); ?>

<?php 
	$awards_query = "
		select
			a.AwardID
			,a.AwardName
			,a.AwardImage
			,a.IsActive
			,a.AwardRequirements
			,a.AwardOrderBy
		from projectx_vvarsc2.Awards a
		order by
			a.AwardOrderBy
			,a.AwardName
	";
	
	$awards_query_results = $connection->query($awards_query);
	$displayAwards = "";
	
	while(($row = $awards_query_results->fetch_assoc()) != false)
	{
		$awardID = $row['AwardID'];
		$awardName = $row['AwardName'];
		$awardImage = $row['AwardImage'];
		$awardIsActive = $row['IsActive'];
		$awardReqs = $row['AwardRequirements'];
		$awardOrderBy = $row['AwardOrderBy'];
		
		$awardIsActiveDisplay = "";
		$awardReqsDisplay = "";
		
		if ($awardIsActive == 1)
			$awardIsActiveDisplay = "Active";
		else
			$awardIsActiveDisplay = "Inactive";
		
		if ($awardReqs == null || $awardReqs == "")
			$awardReqsDisplay = "-- no requirements found --";
		else
			$awardReqsDisplay = $awardReqs;
	
		$displayAwards .= "
			<div class=\"table_header_block\">
			</div>
			<div class=\"yard_filters\" style=\"margin-bottom: 16px;\"
				data-id=\"$awardID\"
				data-name=\"$awardName\"
				data-imageurl=\"$awardImage\"
				data-isactive=\"$awardIsActive\"
				data-reqs=\"$awardReqs\"
				data-orderby=\"$awardOrderBy\"
			>
				<div class=\"\" style=\"
					float: right;
					text-align: right;
					margin-right: 8px;
					width: 50%;
					margin-top: 4px;
				\">
					<button class=\"adminButton adminButtonEdit\" title=\"Edit Award\" style=\"
						margin-left: 0px;
						margin-right: 0px;
					\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"../images/misc/button_edit.png\">
					</button>
					<button class=\"adminButton adminButtonDelete\" title=\"Delete Award\" style=\"
						margin-left: 0px;
						margin-right: 0px;
					\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"../images/misc/button_delete.png\">
					</button>
				</div>
		";
		
		$displayAwards .= "
				<div class=\"PayGradeDetails_Entry_Header\" style=\"
					cursor: pointer;
					vertical-align: middle;
					margin-top: 6px;
					display: table;
					padding-bottom: 4px;
				\">
					<img class=\"shipyard_mainTable_row_header_arrow\" style=\"display: table-cell;\" src=\"../images/misc/SC_Button01.png\" align=\"middle\">
					<div class=\"player_qual_row_name\" style=\"
						margin-top:8px;
						margin-bottom:8px;
						padding-left:8px;
						display: table-cell;
						vertical-align: middle;
					\">
						<strong>$awardName</strong>
						<br />
						Order: $awardOrderBy
					</div>
				</div>
				<div class=\"shipyard_mainTable_row_content\" style=\"
					padding-top:0px;
					width: 100%;
				\">
					<div class=\"shipDetails_ownerInfo_tableRow_inner\" style=\"
						display: inline-block;
						padding: 8px;
					\">
						<div class=\"shipDetails_ownerInfo_tableRow_ImgContainer\" style=\"
							height: 22px;
							width: 68px;
							padding-left: 0px;
							padding-right: 0px;
							vertical-align: unset;
						\">
							<div class=\"corner corner-top-left\">
							</div>
							<div class=\"corner corner-top-right\">
							</div>
							<div class=\"corner corner-bottom-left\">
							</div>
							<div class=\"corner corner-bottom-right\">
							</div>
							<img class=\"divinfo_rankImg\" align=\"center\" style=\"height:14px;width:60px;vertical-align:middle;\"src=\"../images/awards/$awardImage\" />					
						</div>
					</div>
					<div class=\"player_qual_row_name\" style=\"
						margin-bottom:8px;
						padding-left:8px;
					\">
						Status
						<br />
						<strong>$awardIsActiveDisplay</strong>
					</div>				
					<h4 style=\"
						padding: 0px 8px 0px 8px;
						margin-left: 0;
						font-size: 12pt;
					\">
						Requirements
					</h4>
					<div class=\"qual_reqs\" style=\"
						padding-left: 12px;
						font-size: 9pt;
						font-style: italic;
					\">
						<div id=\"qual_reqs_entry-$qualID-level1\" class=\"qual_reqs_entry\">
							<p style=\"
								font-size: 9pt;
								font-style: italic;
								color: #DDD;
								margin-bottom: 4px;
							\">
								$awardReqsDisplay
							</p>
						</div>
					</div>
				</div>
			</div>
		";
	}
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script>
	function resizeInput() {
		$(this).attr('size', $(this).val().length);
	}
	
	$(document).ready(function() {
		var dialog = $('#dialog-form');
		dialog.hide();
		
		//Set TextArea Input Height to Correct Values
		$('input[type="text"]')
		// event handler
		.keyup(resizeInput)
		// resize on page load
		.each(resizeInput);	
	});
	
	$(function() {

		var overlay = $('#overlay');

		//Create
		$('#adminCreateQual').click(function() {
			var dialog = $('#dialog-form-create');
			var $self = jQuery(this);
			
			dialog.find('#IsActive').find('option').prop('selected',false);
			dialog.find('#IsActive').find('#IsActive-default').prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});

		});
		
		//Edit
		$('.adminButton.adminButtonEdit').click(function() {
			var dialog = $('#dialog-form-edit');
			
			var $self = jQuery(this);
			
			var awardID = $self.parent().parent().data("id");
			var awardName = $self.parent().parent().data("name");
			var awardImage = $self.parent().parent().data("imageurl");
			var isActive = $self.parent().parent().data("isactive");
			var awardreqs = $self.parent().parent().data("reqs");
			var orderby = $self.parent().parent().data("orderby");

			dialog.find('#ID').val(awardID).text();
			dialog.find('#Name').val(awardName).text();
			dialog.find('#Image').val(awardImage).text();
			
			dialog.find('#IsActive').find('option').prop('selected',false);
			dialog.find('#IsActive').find('#IsActive-' + isActive).prop('selected',true);
			
			dialog.find('#Level1Reqs').val(awardreqs).text();
			dialog.find('#OrderBy').val(orderby).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});

		});

		//Delete
		$('.adminButton.adminButtonDelete').click(function() {
			var dialog = $('#dialog-form-delete');
			
			var $self = jQuery(this);
			
			var awardID = $self.parent().parent().data("id");
			var awardName = $self.parent().parent().data("name");
			var awardImage = $self.parent().parent().data("imageurl");
			var isActive = $self.parent().parent().data("isactive");
			var awardreqs = $self.parent().parent().data("reqs");
			var orderby = $self.parent().parent().data("orderby");

			dialog.find('#ID').val(awardID).text();
			dialog.find('#Name').val(awardName).text();
			dialog.find('#Image').val(awardImage).text();
			
			dialog.find('#IsActive').find('option').prop('selected',false);
			dialog.find('#IsActive').find('#IsActive-' + isActive).prop('selected',true);
			
			dialog.find('#Level1Reqs').val(awardreqs).text();
			dialog.find('#OrderBy').val(orderby).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
		});
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('#ID').val("").text();
			$('.adminDiaglogFormFieldset').find('#Name').val("").text();
			$('.adminDiaglogFormFieldset').find('#IsActive').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#OrderBy').val("").text();
			$('.adminDiaglogFormFieldset').find('#Level1Reqs').val("").text();
			
			//Hide All Dialog Containers
			$('#dialog-form-create').hide();
			$('#dialog-form-edit').hide();
			$('#dialog-form-delete').hide();
			
			overlay.hide();
			$('.adminTable').css({
				filter: 'none'
			});
			$('#MainPageHeaderText').css({
				filter: 'none'
			});
		});
		
	});
</script>

<!--Script to Show/Hide Rows when Arrows are clicked on each row-->
<script language="javascript">
    $(document).ready(function () {
        $(".shipyard_mainTable_row_content").hide();
		//$(".shipyard_mainTable_row_header_arrow").addClass('rotate90CW');
		
        $(".PayGradeDetails_Entry_Header").click(function () {
            $(this).parent().find(".shipyard_mainTable_row_content").slideToggle(500);
			$(this).find('.shipyard_mainTable_row_header_arrow').toggleClass('rotate90CW');
			$(this).find('.OperationText_Hideable').toggleClass('hidden');
        });		
    });
</script>

<br />
<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="?page=admin">&#8672; Back to Admin Home</a>
	</div>
</div>
<h2 id="MainPageHeaderText">awards Management</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<button id="adminCreateQual" class="adminButton adminButtonCreate" title="Add Qualifcation" style="
			margin-left: 0px;
			margin-right: 2%;
			width: 100%;
			text-align: right;
		">
			<img height="20px" class="adminButtonImage" src="../images/misc/button_add.png">
			Add New Award
		</button>
		<div id="adminawardsTable" class="unit_description_container">
			<div class="top-line">
			</div>
			<div class="corner4 corner-diag-blue-topLeft">
			</div>
			<div class="corner4 corner-diag-blue-topRight">
			</div>
			<div class="corner4 corner-diag-blue-bottomLeft">
			</div>
			<div class="corner4 corner-diag-blue-bottomRight">
			</div>
			<div class="PayGradeDetails">
				<? echo $displayAwards ?>
			</div>
		</div>
		
		<!--Create Form-->
		<div id="dialog-form-create" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Create a new Award</p>
			<form class="adminDialogForm" action="../functions/function_award_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="Image" class="adminDialogInputLabel">
						Image FileName (../images/awards/)
					</label>
					<input type="text" name="Image" id="Image" value="" class="adminDialogTextInput" required>
					
					<label for="IsActive" class="adminDialogInputLabel">
						IsActive
					</label>
					<select name="IsActive" id="IsActive" class="adminDialogDropDown" required>
						<option selected="true" disabled="true" value="default" id="IsActive-default">
							IsActive?
						</option>
						<option value="1" id="IsActive-1">
							Yes
						</option>
						<option value="0" id="IsActive-0">
							No
						</option>
					</select>
					
					<label for="Level1Reqs" class="adminDialogInputLabel">
						Requirements
					</label>
					<textarea name="Level1Reqs" id="Level1Reqs" class="adminDialogTextArea"></textarea>
					
					<label for="OrderBy" class="adminDialogInputLabel">
						OrderBy
					</label>
					<input type="text" name="OrderBy" id="OrderBy" value="" class="adminDialogTextInput" required>
					
				</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>
			</form>
		</div>
		
		<!--Edit Form-->
		<div id="dialog-form-edit" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Update award</p>
			<form class="adminDialogForm" action="../functions/function_award_Edit.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="Image" class="adminDialogInputLabel">
						Image FileName (../images/awards/)
					</label>
					<input type="text" name="Image" id="Image" value="" class="adminDialogTextInput" required>
					
					<label for="IsActive" class="adminDialogInputLabel">
						IsActive
					</label>
					<select name="IsActive" id="IsActive" class="adminDialogDropDown" required>
						<option selected="true" disabled="true" value="default" id="IsActive-default">
							IsActive?
						</option>
						<option value="1" id="IsActive-1">
							Yes
						</option>
						<option value="0" id="IsActive-0">
							No
						</option>
					</select>
					
					<label for="Level1Reqs" class="adminDialogInputLabel">
						Requirements
					</label>
					<textarea name="Level1Reqs" id="Level1Reqs" class="adminDialogTextArea"></textarea>
					
					<label for="OrderBy" class="adminDialogInputLabel">
						OrderBy
					</label>
					<input type="text" name="OrderBy" id="OrderBy" value="" class="adminDialogTextInput" required>
				</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>
			</form>
		</div>
	
		<!--Delete Form-->
		<div id="dialog-form-delete" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Confirmation Required!</p>
			<p class="validateTips">Are you sure you want to Delete this award? Bad Things Could Happen!</p>
			<form class="adminDialogForm" action="../functions/function_award_Delete.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus readonly>

					<label for="Image" class="adminDialogInputLabel">
						Image FileName (../images/awards/)
					</label>
					<input type="text" name="Image" id="Image" value="" class="adminDialogTextInput" readonly>
					
					<label for="IsActive" class="adminDialogInputLabel">
						IsActive
					</label>
					<select name="IsActive" id="IsActive" class="adminDialogDropDown" disabled>
						<option selected="true" disabled="true" value="default" id="IsActive-default">
							IsActive?
						</option>
						<option value="1" id="IsActive-1">
							Yes
						</option>
						<option value="0" id="IsActive-0">
							No
						</option>
					</select>
				</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Confirm Delete
					</button>
				</div>
			</form>
		</div>
	</div>
</div>