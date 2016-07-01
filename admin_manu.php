<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<?php 
	$manu_query = "
		select
			m.manu_id
			,m.manu_name
			,m.manu_shortName
			,m.manu_smallImage
		from projectx_vvarsc2.manufacturers m
	";
	
	$manu_query_results = $connection->query($manu_query);
	$displayManu = "";
	
	while(($row = $manu_query_results->fetch_assoc()) != false)
	{
		$manuID = $row['manu_id'];
		$manuName = $row['manu_name'];
		$manuShortName = $row['manu_shortName'];
		$manuSmallImage = $row['manu_smallImage'];
	
		$displayManu .= "
			<tr class=\"adminTableRow\">
				<td class=\"adminTableRowTD manuID\" data-id=\"$manuID\">
					$manuID
				</td>
				<td class=\"adminTableRowTD manuName\" data-name=\"$manuName\">
					$manuName
				</td>
				<td class=\"adminTableRowTD manuShortName\" data-shortname=\"$manuShortName\">
					$manuShortName
				</td>
				<td class=\"adminTableRowTD manuSmallImage\" data-imageurl=\"$manuSmallImage\">
					<img class=\"shipyard_mainTable_row_header_manuImage\" align=\"center\" src=\"$manuSmallImage\" />
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
	$(document).ready(function() {
		var dialog = $('#dialog-form');
		dialog.hide();
	});
	
	$(function() {

		var overlay = $('#overlay');

		//Create
		$('#adminCreateManu').click(function() {
			var dialog = $('#dialog-form-create');
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(4px)'
			});
		});
		
		//Edit
		$('.adminButton.adminButtonEdit').click(function() {
			var dialog = $('#dialog-form-edit');
			
			var $self = jQuery(this);
			
			var manuID = $self.parent().parent().find('.adminTableRowTD.manuID').data("id");
			var manuName = $self.parent().parent().find('.adminTableRowTD.manuName').data("name");
			var manuShortName = $self.parent().parent().find('.adminTableRowTD.manuShortName').data("shortname");
			var manuSmallImage = $self.parent().parent().find('.adminTableRowTD.manuSmallImage').data("imageurl");
			
			dialog.find('#ID').val(manuID).text();
			dialog.find('#Name').val(manuName).text();
			dialog.find('#ShortName').val(manuShortName).text();
			dialog.find('#ImageURL').val(manuSmallImage).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(4px)'
			});
		});

		//Delete
		$('.adminButton.adminButtonDelete').click(function() {
			var dialog = $('#dialog-form-delete');
			
			var $self = jQuery(this);
			
			var manuID = $self.parent().parent().find('.adminTableRowTD.manuID').data("id");
			var manuName = $self.parent().parent().find('.adminTableRowTD.manuName').data("name");
			var manuShortName = $self.parent().parent().find('.adminTableRowTD.manuShortName').data("shortname");
			var manuSmallImage = $self.parent().parent().find('.adminTableRowTD.manuSmallImage').data("imageurl");
			
			dialog.find('#ID').val(manuID).text();
			dialog.find('#Name').val(manuName).text();
			dialog.find('#ShortName').val(manuShortName).text();
			dialog.find('#ImageURL').val(manuSmallImage).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(4px)'
			});
		});
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
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
<h2>Manufacturer Management</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<button id="adminCreateManu" class="adminButton adminButtonCreate">Add New Manufacturer</button>
		<table id="adminManuTable" class="adminTable">
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
					LogoImage
				</td>
				<td class="adminTableHeaderRowTD">
					Actions
				</td>
			</tr>
			<? echo $displayManu ?>
		</table>
		
		<!--Create Form-->
		<div id="dialog-form-create" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Enter new Manufacturer Information Below.</p>
			<p class="validateTips">All form fields are required.</p>
			<form class="adminDialogForm" action="/functions/function_manu_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="ShortName" class="adminDialogInputLabel">
						ShortName
					</label>
					<input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput"required>

					<label for="ImageURL" class="adminDialogInputLabel">
						ImageURL
					</label>
					<input type="text" name="ImageURL" id="ImageURL" value="" class="adminDialogTextInput"required>
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
			<p class="validateTips">Update Manufacturer Information</p>
			<form class="adminDialogForm" action="functions/function_manu_Edit.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" required>
					
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="ShortName" class="adminDialogInputLabel">
						ShortName
					</label>
					<input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput"required>

					<label for="ImageURL" class="adminDialogInputLabel">
						ImageURL
					</label>
					<input type="text" name="ImageURL" id="ImageURL" value="" class="adminDialogTextInput"required>
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
			<p class="validateTips">Are you sure you want to Delete this Manufacturer? Bad Things Could Happen!</p>
			<form class="adminDialogForm" action="functions/function_manu_Delete.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" readonly>

					<label for="ShortName" class="adminDialogInputLabel">
						ShortName
					</label>
					<input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput"readonly>

					<label for="ImageURL" class="adminDialogInputLabel">
						ImageURL
					</label>
					<input type="text" name="ImageURL" id="ImageURL" value="" class="adminDialogTextInput"readonly>
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