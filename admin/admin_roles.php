<?php include_once('functions/function_auth_admin.php'); ?>

<?php 
	$roles_query = "
		select
			r.role_id
			,r.role_name
			,r.role_shortName
			,r.isPrivate
			,r.role_orderby
		from projectx_vvarsc2.roles r
		order by
			r.role_orderby
			,r.role_name
	";
	
	$roles_query_results = $connection->query($roles_query);
	$displayRoles = "";
	
	while(($row = $roles_query_results->fetch_assoc()) != false)
	{
		$roleID = $row['role_id'];
		$roleName = $row['role_name'];
		$roleShortName = $row['role_shortName'];
		$isPrivate = $row['isPrivate'];
		$orderBy = $row['role_orderby'];
	
		$displayRoles .= "
			<tr class=\"adminTableRow\">
				<td class=\"adminTableRowTD roleID\" data-id=\"$roleID\">
					$roleID
				</td>
				<td class=\"adminTableRowTD roleName\" data-name=\"$roleName\">
					$roleName
				</td>
				<td class=\"adminTableRowTD roleShortName\" data-shortname=\"$roleShortName\">
					$roleShortName
				</td>
				<td class=\"adminTableRowTD isPrivate\" data-isprivate=\"$isPrivate\">
					$isPrivate
				</td>
				<td class=\"adminTableRowTD orderBy\" data-orderby=\"$orderBy\">
					$orderBy
				</td>
				<td class=\"adminTableRowTD\">
					<button class=\"adminButton adminButtonEdit\">
						Edit
					</button>
					<button class=\"adminButton adminButtonDelete\">
						Delete
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
		$('#adminCreateManu').click(function() {
			var dialog = $('#dialog-form-create');
			var $self = jQuery(this);
			
			var roleID = $('.adminTableRow').data("id");
			dialog.find('#IsPrivate').find('#IsPrivate-default').prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
		});
		
		//Edit
		$('.adminButton.adminButtonEdit').click(function() {
			var dialog = $('#dialog-form-edit');
			
			var $self = jQuery(this);
			
			var roleID = $self.parent().parent().find('.adminTableRowTD.roleID').data("id");
			var roleName = $self.parent().parent().find('.adminTableRowTD.roleName').data("name");
			var roleShortName = $self.parent().parent().find('.adminTableRowTD.roleShortName').data("shortname");
			var roleIsPrivate = $self.parent().parent().find('.adminTableRowTD.isPrivate').data("isprivate");
			var roleOrderBy = $self.parent().parent().find('.adminTableRowTD.orderBy').data("orderby");
			
			dialog.find('#ID').val(roleID).text();
			dialog.find('#Name').val(roleName).text();
			dialog.find('#ShortName').val(roleShortName).text();
			
			dialog.find('#IsPrivate').find('option').prop('selected',false);
			dialog.find('#IsPrivate').find('#IsPrivate-' + roleIsPrivate).prop('selected',true);
			
			dialog.find('#Order').val(roleOrderBy).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
		});

		//Delete
		$('.adminButton.adminButtonDelete').click(function() {
			var dialog = $('#dialog-form-delete');
			
			var $self = jQuery(this);
			
			var roleID = $self.parent().parent().find('.adminTableRowTD.roleID').data("id");
			var roleName = $self.parent().parent().find('.adminTableRowTD.roleName').data("name");
			var roleShortName = $self.parent().parent().find('.adminTableRowTD.roleShortName').data("shortname");
			var roleIsPrivate = $self.parent().parent().find('.adminTableRowTD.isPrivate').data("isprivate");
			var roleOrderBy = $self.parent().parent().find('.adminTableRowTD.orderBy').data("orderby");
			
			dialog.find('#ID').val(roleID).text();
			dialog.find('#Name').val(roleName).text();
			dialog.find('#ShortName').val(roleShortName).text();
			dialog.find('#IsPrivate').val(roleIsPrivate).text();
			dialog.find('#Order').val(roleOrderBy).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
		});
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('#ID').val("").text();
			$('.adminDiaglogFormFieldset').find('#Name').val("").text();
			$('.adminDiaglogFormFieldset').find('#ShortName').val("").text();
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
		});
		
	});
</script>

<br />
<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="http://sc.vvarmachine.com/admin">&#8672; Back to Admin Home</a>
	</div>
</div>
<h2>Roles Management</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<button id="adminCreateManu" class="adminButton adminButtonCreate">Add New Role</button>
		<table id="adminRolesTable" class="adminTable">
			<tr class="adminTableHeaderRow">
				<td class="adminTableHeaderRowTD">
					ID
				</td>
				<td class="adminTableHeaderRowTD">
					Name
				</td>
				<td class="adminTableHeaderRowTD">
					ShortName
				</td>
				<td class="adminTableHeaderRowTD">
					IsPrivate
				</td>
				<td class="adminTableHeaderRowTD">
					Order
				</td>
				<td class="adminTableHeaderRowTD">
					Actions
				</td>
			</tr>
			<? echo $displayRoles ?>
		</table>
		
		<!--Create Form-->
		<div id="dialog-form-create" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Enter new Role Information Below.</p>
			<form class="adminDialogForm" action="/functions/function_role_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="ShortName" class="adminDialogInputLabel">
						ShortName
					</label>
					<input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput">

					<label for="IsPrivate" class="adminDialogInputLabel">
						IsPrivate
					</label>
					<select name="IsPrivate" id="IsPrivate" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="IsPrivate-default">
							IsPrivate?
						</option>
						<option value="1" id="IsPrivate-1">
							Yes
						</option>
						<option value="0" id="IsPrivate-0">
							No
						</option>
					</select>

					<label for="Order" class="adminDialogInputLabel">
						Order
					</label>
					<input type="text" name="Order" id="Order" value="" class="adminDialogTextInput"required>
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
			<p class="validateTips">Update Role Information</p>
			<form class="adminDialogForm" action="functions/function_role_Edit.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>	
					
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="ShortName" class="adminDialogInputLabel">
						ShortName
					</label>
					<input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput">

					<label for="IsPrivate" class="adminDialogInputLabel">
						IsPrivate
					</label>
					<select name="IsPrivate" id="IsPrivate" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="IsPrivate-default">
							IsPrivate?
						</option>
						<option value="1" id="IsPrivate-1">
							Yes
						</option>
						<option value="0" id="IsPrivate-0">
							No
						</option>
					</select>

					<label for="Order" class="adminDialogInputLabel">
						Order
					</label>
					<input type="text" name="Order" id="Order" value="" class="adminDialogTextInput" required>
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
			<p class="validateTips">Are you sure you want to Delete this Role? Bad Things Could Happen!</p>
			<form class="adminDialogForm" action="functions/function_role_Delete.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>	
					
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus readonly>

					<label for="ShortName" class="adminDialogInputLabel">
						ShortName
					</label>
					<input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput" readonly>

					<label for="IsPrivate" class="adminDialogInputLabel">
						IsPrivate
					</label>
					<input type="text" name="IsPrivate" id="IsPrivate" value="" class="adminDialogTextInput" required  readonly>

					<label for="Order" class="adminDialogInputLabel">
						Order
					</label>
					<input type="text" name="Order" id="Order" value="" class="adminDialogTextInput" required  readonly>
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