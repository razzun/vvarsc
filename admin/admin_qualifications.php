<?php include_once('../functions/function_auth_admin.php'); ?>
<?php include_once('../inc/OptionQueries/lk_qualificationCategories_query.php'); ?>

<?php 
	$qualifications_query = "
		select
			q.qualification_id
			,q.qualification_name
			,q.qualification_image
			,q.qualification_categoryID
			,lk.CategoryName as qualification_category
			,q.IsActive
			,q.level1_reqs
			,q.level2_reqs
			,q.level3_reqs
		from projectx_vvarsc2.qualifications q
		join projectx_vvarsc2.LK_QualificationCategories lk
			on lk.CategoryID = q.qualification_categoryID
		order by
			lk.CategoryName
			,q.qualification_name
	";
	
	$qualifications_query_results = $connection->query($qualifications_query);
	$displayQualifications = "";
	
	while(($row = $qualifications_query_results->fetch_assoc()) != false)
	{
		$qualID = $row['qualification_id'];
		$qualName = $row['qualification_name'];
		$qualCategoryID = $row['qualification_categoryID'];
		$qualCategory = $row['qualification_category'];
		$qualImage = $row['qualification_image'];
		$qualIsActive = $row['IsActive'];
		$qualLevel1Reqs = $row['level1_reqs'];
		$qualLevel2Reqs = $row['level2_reqs'];
		$qualLevel3Reqs = $row['level3_reqs'];
		
		$qualIsActiveDisplay = "";
		$qualLevel1ReqsDisplay = "";
		$qualLevel2ReqsDisplay = "";
		$qualLevel3ReqsDisplay = "";
		
		if ($qualIsActive == 1)
			$qualIsActiveDisplay = "Active";
		else
			$qualIsActiveDisplay = "Inactive";
		
		if ($qualLevel1Reqs == null || $qualLevel1Reqs == "")
			$qualLevel1ReqsDisplay = "-- no requirements found --";
		else
			$qualLevel1ReqsDisplay = $qualLevel1Reqs;
			
		if ($qualLevel2Reqs == null || $qualLevel2Reqs == "")
			$qualLevel2ReqsDisplay = "-- no requirements found --";
		else
			$qualLevel2ReqsDisplay = $qualLevel2Reqs;
			
		if ($qualLevel3Reqs == null || $qualLevel3Reqs == "")
			$qualLevel3ReqsDisplay = "-- no requirements found --";
		else
			$qualLevel3ReqsDisplay = $qualLevel3Reqs;
	
		$displayQualifications .= "
			<div class=\"table_header_block\">
			</div>
			<div class=\"yard_filters\" style=\"margin-bottom: 16px;\"
				data-id=\"$qualID\"
				data-name=\"$qualName\"
				data-categoryid=\"$qualCategoryID\"
				data-imageurl=\"$qualImage\"
				data-isactive=\"$qualIsActive\"
				data-levelonereqs=\"$qualLevel1Reqs\"
				data-leveltworeqs=\"$qualLevel2Reqs\"
				data-levelthreereqs=\"$qualLevel3Reqs\"
			>
				<div class=\"\" style=\"
					float: right;
					text-align: right;
					margin-right: 8px;
					width: 50%;
					margin-top: 4px;
				\">
					<button class=\"adminButton adminButtonEdit\" title=\"Edit Qualification\" style=\"
						margin-left: 0px;
						margin-right: 0px;
					\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"../images/misc/button_edit.png\">
					</button>
					<button class=\"adminButton adminButtonDelete\" title=\"Delete Qualification\" style=\"
						margin-left: 0px;
						margin-right: 0px;
					\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"../images/misc/button_delete.png\">
					</button>
				</div>
		";
		
		$displayQualifications .= "
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
						$qualCategory
						<br />
						<strong>$qualName</strong>
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
							height: 38px;
							width: 38px;
							padding-left: 0px;
							padding-right: 0px;
						\">
							<div class=\"corner corner-top-left\">
							</div>
							<div class=\"corner corner-top-right\">
							</div>
							<div class=\"corner corner-bottom-left\">
							</div>
							<div class=\"corner corner-bottom-right\">
							</div>
							<img class=\"divinfo_rankImg\" align=\"center\" style=\"height:30px;width:30px;\"src=\"../images/qualifications/$qualImage\" />					
						</div>
					</div>
					<div class=\"player_qual_row_name\" style=\"
						margin-bottom:8px;
						padding-left:8px;
					\">
						Status
						<br />
						<strong>$qualIsActiveDisplay</strong>
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
						padding-right: 8px;
						font-size: 9pt;
					\">
						<div id=\"qual_reqs_entry-$qualID-level1\" class=\"qual_reqs_entry\">
							<strong>Level 1</strong>
							<p style=\"
								font-size: 9pt;
								color: #DDD;
								margin-bottom: 4px;
							\">
								$qualLevel1ReqsDisplay
							</p>
						</div>
						<div id=\"qual_reqs_entry-$qualID-level2\" class=\"qual_reqs_entry\">
							<strong>Level 2</strong>
							<p style=\"
								font-size: 9pt;
								color: #DDD;
								margin-bottom: 4px;
							\">
								$qualLevel2ReqsDisplay
							</p>
						</div>
						<div id=\"qual_reqs_entry-$qualID-level3\" class=\"qual_reqs_entry\">
							<strong>Level 3</strong>
							<p style=\"
								font-size: 9pt;
								color: #DDD;
								margin-bottom: 4px;
							\">
								$qualLevel3ReqsDisplay
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
			
			dialog.find('#Category').find('option').prop('selected',false);
			dialog.find('#Category').find('#CategoryID-default').prop('selected',true);
			
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
			
			var qualID = $self.parent().parent().data("id");
			var qualName = $self.parent().parent().data("name");
			var qualImage = $self.parent().parent().data("imageurl");
			var qualCategoryID = $self.parent().parent().data("categoryid");
			var isActive = $self.parent().parent().data("isactive");
			var level1reqs = $self.parent().parent().data("levelonereqs");
			var level2reqs = $self.parent().parent().data("leveltworeqs");
			var level3reqs = $self.parent().parent().data("levelthreereqs");

			dialog.find('#ID').val(qualID).text();
			dialog.find('#Name').val(qualName).text();
			dialog.find('#Image').val(qualImage).text();
			
			dialog.find('#Category').find('option').prop('selected',false);
			dialog.find('#Category').find('#CategoryID-' + qualCategoryID).prop('selected',true);
			
			dialog.find('#IsActive').find('option').prop('selected',false);
			dialog.find('#IsActive').find('#IsActive-' + isActive).prop('selected',true);
			
			dialog.find('#Level1Reqs').val(level1reqs).text();
			dialog.find('#Level2Reqs').val(level2reqs).text();
			dialog.find('#Level3Reqs').val(level3reqs).text();
			
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
			
			var qualID = $self.parent().parent().data("id");
			var qualName = $self.parent().parent().data("name");
			var qualImage = $self.parent().parent().data("imageurl");
			var qualCategoryID = $self.parent().parent().data("categoryid");
			var isActive = $self.parent().parent().data("isactive");

			
			dialog.find('#ID').val(qualID).text();
			dialog.find('#Name').val(qualName).text();
			dialog.find('#Image').val(qualImage).text();
			
			dialog.find('#Category').find('option').prop('selected',false);
			dialog.find('#Category').find('#CategoryID-' + qualCategoryID).prop('selected',true);
			
			dialog.find('#IsActive').find('option').prop('selected',false);
			dialog.find('#IsActive').find('#IsActive-' + isActive).prop('selected',true);
			
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
			$('.adminDiaglogFormFieldset').find('#ShortName').val("").text();
			$('.adminDiaglogFormFieldset').find('#DisplayName').val("").text();
			$('.adminDiaglogFormFieldset').find('#IsPrivate').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#Order').val("").text();
			
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
<h2 id="MainPageHeaderText">Qualifications Management</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<button id="adminCreateQual" class="adminButton adminButtonCreate" title="Add Qualifcation" style="
			margin-left: 0px;
			margin-right: 2%;
			width: 100%;
			text-align: right;
		">
			<img height="20px" class="adminButtonImage" src="../images/misc/button_add.png">
			Add New Qualification
		</button>
		<div id="adminQualificationsTable" class="unit_description_container">
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
				<? echo $displayQualifications ?>
			</div>
		</div>
		
		<!--Create Form-->
		<div id="dialog-form-create" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Create a new Qualification</p>
			<form class="adminDialogForm" action="../functions/function_qualification_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="Category" class="adminDialogInputLabel">
						Category
					</label>
					<select name="Category" id="Category" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="CategoryID-default">
							--Select a Category--
						</option>
						<? echo $displayQualificationCategorySelectors ?>
					</select>

					<label for="Image" class="adminDialogInputLabel">
						Image FileName (../images/qualifications/)
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
						Requirements: Level 1
					</label>
					<textarea name="Level1Reqs" id="Level1Reqs" class="adminDialogTextArea"></textarea>
					
					<label for="Level2Reqs" class="adminDialogInputLabel">
						Requirements: Level 2
					</label>
					<textarea name="Level2Reqs" id="Level2Reqs" class="adminDialogTextArea"></textarea>
					
					<label for="Level3Reqs" class="adminDialogInputLabel">
						Requirements: Level 3
					</label>
					<textarea name="Level3Reqs" id="Level3Reqs" class="adminDialogTextArea"></textarea>
					
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
			<p class="validateTips">Update Qualification</p>
			<form class="adminDialogForm" action="../functions/function_qualification_Edit.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="Category" class="adminDialogInputLabel">
						Category
					</label>
					<select name="Category" id="Category" class="adminDialogDropDown" required>
						<option selected="true" disabled="true" value="default" id="CategoryID-default">
							--Select a Category--
						</option>
						<? echo $displayQualificationCategorySelectors ?>
					</select>

					<label for="Image" class="adminDialogInputLabel">
						Image FileName (../images/qualifications/)
					</label>
					<input type="text" name="Image" id="Image" value="" class="adminDialogTextInput">
					
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
						Requirements: Level 1
					</label>
					<textarea name="Level1Reqs" id="Level1Reqs" class="adminDialogTextArea"></textarea>
					
					<label for="Level2Reqs" class="adminDialogInputLabel">
						Requirements: Level 2
					</label>
					<textarea name="Level2Reqs" id="Level2Reqs" class="adminDialogTextArea"></textarea>
					
					<label for="Level3Reqs" class="adminDialogInputLabel">
						Requirements: Level 3
					</label>
					<textarea name="Level3Reqs" id="Level3Reqs" class="adminDialogTextArea"></textarea>
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
			<p class="validateTips">Are you sure you want to Delete this Qualification? Bad Things Could Happen!</p>
			<form class="adminDialogForm" action="../functions/function_qualification_Delete.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus readonly>

					<label for="Category" class="adminDialogInputLabel">
						Category
					</label>
					<select name="Category" id="Category" class="adminDialogDropDown" disabled>
						<option selected="true" disabled="true" value="default" id="CategoryID-default">
							--Select a Category--
						</option>
						<? echo $displayQualificationCategorySelectors ?>
					</select>

					<label for="Image" class="adminDialogInputLabel">
						Image FileName (../images/qualifications/)
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