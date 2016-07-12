<?php include_once('functions/function_auth_admin.php'); ?>

<?php 
	$units_query = "
		select
			u.UnitID
			,u.UnitName
			,u.UnitShortName
			,u.UnitFullName
			,d.div_id
			,d.div_name
			,u.UnitLevel
			,u.UnitLeaderID
			,m.mem_name
			,u.CreatedOn
			,u.IsActive
		from projectx_vvarsc2.Units u
		left join projectx_vvarsc2.divisions d
			on d.div_id = u.DivisionID
		left join projectx_vvarsc2.members m
			on m.mem_id = u.UnitLeaderID
		order by
			u.UnitName
	";
	
	$units_query_results = $connection->query($units_query);
	$displayUnits = "";
	
	while(($row = $units_query_results->fetch_assoc()) != false)
	{
		$unitID = $row['UnitID'];
		$unitName = $row['UnitName'];
		$unitShortName = $row['UnitShortName'];
		$unitFullName = $row['UnitFullName'];
		$unitDivID = $row['div_id'];
		$unitDivName = $row['div_name'];
		$unitLevel = $row['UnitLevel'];
		$unitLeaderID = $row['UnitLeaderID'];
		$unitLeaderName = $row['mem_name'];
		$unitCreatedOn = $row['CreatedOn'];
		$unitIsActive = $row['IsActive'];
	
		$displayUnits .= "
			<tr class=\"adminTableRow\" data-id=\"$unitID\">
				<td class=\"adminTableRowTD unitID\" data-id=\"$unitID\">
					$unitID
				</td>
				<td class=\"adminTableRowTD unitName\" data-name=\"$unitName\">
					<a href=\"unit/$unitID\" target=\"_top\">	
						$unitName
					</a>	
				</td>
				<td class=\"adminTableRowTD unitShortName\" data-shortname=\"$unitShortName\">
					$unitShortName
				</td>
				<td class=\"adminTableRowTD unitFullName\" data-fullname=\"$unitFullName\">
					$unitFullName
				</td>
				<td class=\"adminTableRowTD unitDivName\" data-divname=\"$unitDivName\" data-divid=\"$unitDivID\">
					$unitDivName
				</td>
				<td class=\"adminTableRowTD unitLevel\" data-level=\"$unitLevel\">
					$unitLevel
				</td>
				<td class=\"adminTableRowTD unitLeaderName\" data-leadername=\"$unitLeaderName\" data-id=\"$unitLeaderID\">
					<a href=\"player/$unitLeaderID\" target=\"_top\">	
						$unitLeaderName
					</a>
				</td>
				<td class=\"adminTableRowTD unitCreatedOn\" data-createdon=\"$unitCreatedOn\">
					$unitCreatedOn
				</td>
				<td class=\"adminTableRowTD unitIsActive\" data-isactive=\"$unitIsActive\">
					$unitIsActive
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
	
	$division_query = "
		select
			d.div_id
			,d.div_name
		from projectx_vvarsc2.divisions d
		order by
			d.div_name
	";
	
	$division_query_results = $connection->query($division_query);
	$displayDivisions = "";
	
	while(($row = $division_query_results->fetch_assoc()) != false)
	{
		$DivisionID = $row['div_id'];
		$DivisionName = $row['div_name'];
	
		$displayDivisions .= "
			<option value=\"$DivisionID\" id=\"$DivisionID\">
				$DivisionName
			</option>
		";
	}
	
	$parentUnits_query = "
		select
			u.UnitID
			,COALESCE(u.UnitFullName,u.UnitName) as UnitName
		from projectx_vvarsc2.Units u
		where u.UnitLevel not in ('Squadron','Platoon')
		order by
			u.UnitName
	";
	
	$parentUnits_query_results = $connection->query($parentUnits_query);
	$displayParentUnits = "";
	
	while(($row = $parentUnits_query_results->fetch_assoc()) != false)
	{
		$UnitID = $row['UnitID'];
		$UnitName = $row['UnitName'];
	
		$displayParentUnits .= "
			<option value=\"$UnitID\" id=\"$UnitID\">
				$UnitName
			</option>
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
			var $self = jQuery(this);
			
			var unitID = $('.adminTableRow').data("id");
			dialog.find('#ParentUnit').find('#ParentUnit-default').prop('selected',true);
			dialog.find('#Division').find('#Division-default').prop('selected',true);
			dialog.find('#IsActive').find('#IsActive-default').prop('selected',true);
			
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
			var unitID = $self.parent().parent().find('.adminTableRowTD.unitID').data("id");
			
			//Launch Unit Edit Page
			window.location.href = "http://sc.vvarmachine.com/admin_unit/" + unitID;
			
			/*
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
			*/
		});

		//Delete
		$('.adminButton.adminButtonDelete').click(function() {
			var dialog = $('#dialog-form-delete');
			
			var $self = jQuery(this);
			
			var unitID = $self.parent().parent().find('.adminTableRowTD.unitID').data("id");
			var unitName = $self.parent().parent().find('.adminTableRowTD.unitName').data("name");
			var unitShortName = $self.parent().parent().find('.adminTableRowTD.unitShortName').data("shortname");
			var unitFullName = $self.parent().parent().find('.adminTableRowTD.unitFullName').data("fullname");
			var unitDivision = $self.parent().parent().find('.adminTableRowTD.unitDivName').data("divname");
			var unitIsActive = $self.parent().parent().find('.adminTableRowTD.unitIsActive').data("isactive");
			var unitLevel = $self.parent().parent().find('.adminTableRowTD.unitLevel').data("level");
			
			dialog.find('#ID').val(unitID).text();
			dialog.find('#Name').val(unitName).text();
			dialog.find('#ShortName').val(unitShortName).text();
			dialog.find('#FullName').val(unitFullName).text();
			dialog.find('#Division').val(unitDivision).text();
			dialog.find('#IsActive').val(unitIsActive).text();
			dialog.find('#Level').val(unitLevel).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
		});
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('#ParentUnit').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#ID').val("").text();
			$('.adminDiaglogFormFieldset').find('#Name').val("").text();
			$('.adminDiaglogFormFieldset').find('#ShortName').val("").text();
			$('.adminDiaglogFormFieldset').find('#FullName').val("").text();
			$('.adminDiaglogFormFieldset').find('#Division').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#IsActive').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#Level').val("").text();
			
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
<h2>Unit Management (under construction)</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<button id="adminCreateManu" class="adminButton adminButtonCreate">Add New Unit</button>
		<table id="adminUnitsTable" class="adminTable">
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
					FullName
				</td>
				<td class="adminTableHeaderRowTD">
					Division
				</td>
				<td class="adminTableHeaderRowTD">
					Level
				</td>
				<td class="adminTableHeaderRowTD">
					Leader
				</td>
				<td class="adminTableHeaderRowTD">
					CreatedOn
				</td>
				<td class="adminTableHeaderRowTD">
					IsActive
				</td>
				<td class="adminTableHeaderRowTD">
					Actions
				</td>
			</tr>
			<? echo $displayUnits ?>
		</table>
		
		<!--Create Form-->
		<div id="dialog-form-create" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Enter new Unit Information Below.</p>
			<form class="adminDialogForm" action="/functions/function_unit_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">

					<label for="ParentUnit" class="adminDialogInputLabel">
						ParentUnit
					</label>
					<select name="ParentUnit" id="ParentUnit" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="ParentUnit-default">
							-Select Parent Unit-
						</option>
						<? echo $displayParentUnits ?>
					</select>

					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="ShortName" class="adminDialogInputLabel">
						ShortName
					</label>
					<input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput">
					
					<label for="FullName" class="adminDialogInputLabel">
						FullName
					</label>
					<input type="text" name="FullName" id="FullName" value="" class="adminDialogTextInput">

					<label for="Division" class="adminDialogInputLabel">
						Division
					</label>
					<select name="Division" id="Division" class="adminDialogDropDown" required>
						<option selected="true" disabled="true" value="default" id="Division-default">
							-Select Division-
						</option>						
						<? echo $displayDivisions ?>
					</select>
					
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
					
					<label for="Level" class="adminDialogInputLabel">
						Level
					</label>
					<input type="text" name="Level" id="Level" value="" class="adminDialogTextInput" required>
					
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
			<p class="validateTips">Update Unit Information</p>
			<form class="adminDialogForm" action="functions/function_unit_Edit.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">

					<label for="ID" class="adminDialogInputLabel">
						ID
					</label>
					<input type="text" name="ID" id="ID" value="" class="adminDialogTextInput" readonly>

					<label for="ParentUnit" class="adminDialogInputLabel">
						ParentUnit
					</label>
					<select name="ParentUnit" id="ParentUnit" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="ParentUnit-default">
							-Select Parent Unit-
						</option>
						<? echo $displayParentUnits ?>
					</select>

					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>

					<label for="ShortName" class="adminDialogInputLabel">
						ShortName
					</label>
					<input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput"required>
					
					<label for="FullName" class="adminDialogInputLabel">
						FullName
					</label>
					<input type="text" name="FullName" id="FullName" value="" class="adminDialogTextInput">

					<label for="Division" class="adminDialogInputLabel">
						Division
					</label>
					<select name="Division" id="Division" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="Division-default">
							-Select Division-
						</option>						
						<? echo $displayDivisions ?>
					</select>
					
					<label for="IsActive" class="adminDialogInputLabel">
						IsActive
					</label>
					<select name="IsActive" id="IsActive" class="adminDialogDropDown">
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
					
					<label for="Level" class="adminDialogInputLabel">
						Level
					</label>
					<input type="text" name="Level" id="Level" value="" class="adminDialogTextInput">
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
			<p class="validateTips">Are you sure you want to Delete this Unit? Bad Things Could Happen!</p>
			<form class="adminDialogForm" action="functions/function_unit_Delete.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">

					<label for="ID" class="adminDialogInputLabel">
						ID
					</label>
					<input type="text" name="ID" id="ID" value="" class="adminDialogTextInput" readonly>

					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" readonly>

					<label for="ShortName" class="adminDialogInputLabel">
						ShortName
					</label>
					<input type="text" name="ShortName" id="ShortName" value="" class="adminDialogTextInput" readonly>
					
					<label for="FullName" class="adminDialogInputLabel">
						FullName
					</label>
					<input type="text" name="FullName" id="FullName" value="" class="adminDialogTextInput"  readonly>

					<label for="Division" class="adminDialogInputLabel">
						Division
					</label>
					<input type="text" name="Division" id="Division" value="" class="adminDialogTextInput"  readonly>
					
					<label for="IsActive" class="adminDialogInputLabel">
						IsActive
					</label>
					<input type="text" name="IsActive" id="IsActive" value="" class="adminDialogTextInput" readonly>
					
					<label for="Level" class="adminDialogInputLabel">
						Level
					</label>
					<input type="text" name="Level" id="Level" value="" class="adminDialogTextInput" readonly>
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