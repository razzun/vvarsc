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
	
		$displayQualifications .= "
			<tr class=\"adminTableRow\">
				<td class=\"adminTableRowTD qualID\" data-id=\"$qualID\">
					$qualID
				</td>
				<td class=\"adminTableRowTD qualName\" data-name=\"$qualName\">
					$qualName
				</td>
				<td class=\"adminTableRowTD qualCategory\" data-categoryid=\"$qualCategoryID\">
					$qualCategory
				</td>
				<td class=\"adminTableRowTD qualImage\" data-imageurl=\"$qualImage\">
					<img class=\"shipyard_mainTable_row_header_manuImage\" align=\"center\" src=\"http://sc.vvarmachine.com/images/qualifications/$qualImage\" />
				</td>
				<td class=\"adminTableRowTD qualIsActive\" data-isactive=\"$qualIsActive\">
					$qualIsActive
				</td>
				<td class=\"adminTableRowTD\">
					<button class=\"adminButton adminButtonEdit\" title=\"Edit Qualification\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"http://sc.vvarmachine.com/images/misc/button_edit.png\">
					</button>
					<button class=\"adminButton adminButtonDelete\" title=\"Delete Qualification\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"http://sc.vvarmachine.com/images/misc/button_delete.png\">
					</button>
				</td>
			</tr>
		";
	}
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
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
			
			var roleID = $('.adminTableRow').data("id");
			
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
			
			var qualID = $self.parent().parent().find('.adminTableRowTD.qualID').data("id");
			var qualName = $self.parent().parent().find('.adminTableRowTD.qualName').data("name");
			var qualImage = $self.parent().parent().find('.adminTableRowTD.qualImage').data("imageurl");
			var qualCategoryID = $self.parent().parent().find('.adminTableRowTD.qualCategory').data("categoryid");
			var isActive = $self.parent().parent().find('.adminTableRowTD.qualIsActive').data("isactive");

			
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

		//Delete
		$('.adminButton.adminButtonDelete').click(function() {
			var dialog = $('#dialog-form-delete');
			
			var $self = jQuery(this);
			
			var qualID = $self.parent().parent().find('.adminTableRowTD.qualID').data("id");
			var qualName = $self.parent().parent().find('.adminTableRowTD.qualName').data("name");
			var qualImage = $self.parent().parent().find('.adminTableRowTD.qualImage').data("imageurl");
			var qualCategoryID = $self.parent().parent().find('.adminTableRowTD.qualCategory').data("categoryid");
			var isActive = $self.parent().parent().find('.adminTableRowTD.qualIsActive').data("isactive");

			
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

<br />
<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="http://sc.vvarmachine.com/admin/">&#8672; Back to Admin Home</a>
	</div>
</div>
<h2 id="MainPageHeaderText">Qualifications Management</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<button id="adminCreateQual" class="adminButton adminButtonCreate" title="Add Qualifcation" style="
			float: right;
			margin-left: 0px;
			margin-right: 2%;			
		">
			<img height="20px" class="adminButtonImage" src="http://sc.vvarmachine.com/images/misc/button_add.png">
			Add New Qualification
		</button>
		<table id="adminQualificationsTable" class="adminTable">
			<tr class="adminTableHeaderRow">
				<td class="adminTableHeaderRowTD">
					ID
				</td>
				<td class="adminTableHeaderRowTD">
					Name
				</td>
				<td class="adminTableHeaderRowTD">
					Category
				</td>
				<td class="adminTableHeaderRowTD">
					Image
				</td>
				<td class="adminTableHeaderRowTD">
					IsActive
				</td>
				<td class="adminTableHeaderRowTD">
					Actions
				</td>
			</tr>
			<? echo $displayQualifications ?>
		</table>
		
		<!--Create Form-->
		<div id="dialog-form-create" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Create a new Qualification</p>
			<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/function_qualification_Create.php" method="POST" role="form">
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
			<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/function_qualification_Edit.php" method="POST" role="form">
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
			<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/function_qualification_Delete.php" method="POST" role="form">
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