<?php include_once('functions/function_auth_admin.php'); ?>

<?php
	$unit_id = strip_tags(isset($_GET[pid]) ? $_GET[pid] : '');
	
	$unit_details_query = "
		select
			u.UnitID
			,u2.UnitID as ParentUnitID
			,u2.UnitName as ParentUnitName
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
			,TRIM(LEADING '\t' from u.UnitDescription) as UnitDescription
			,u.UnitBackgroundImage
		from projectx_vvarsc2.Units u
		left join projectx_vvarsc2.Units u2
			on u2.UnitID = u.ParentUnitID
		left join projectx_vvarsc2.divisions d
			on d.div_id = u.DivisionID
		left join projectx_vvarsc2.members m
			on m.mem_id = u.UnitLeaderID
		where u.UnitID = '$unit_id'
	";
	
	$unit_details_query_results = $connection->query($unit_details_query);
	$displayUnit = "";
	
	while(($row = $unit_details_query_results->fetch_assoc()) != false)
	{
		$unitID = $row['UnitID'];
		$parentUnitID = $row['ParentUnitID'];
		$parentUnitName = $row['ParentUnitName'];
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
		$unitDescription = $row['UnitDescription'];
		$unitBackgroundImage = $row['UnitBackgroundImage'];
	}
	
	//Query for Divisions Drop-Down Menu
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
	
	//Query for Members Drop-Down Menu
	$member_query = "
		select
			m.mem_id
			,m.mem_name
		from projectx_vvarsc2.members m
		where m.mem_id not in (
			select
				um.MemberID
			from projectx_vvarsc2.UnitMembers um
			where um.UnitID = '$unit_id'
		)
			and m.mem_sc = 1
		order by
			m.mem_name
	";
	
	$member_query_results = $connection->query($member_query);
	$displayMembers = "";
	
	while(($row = $member_query_results->fetch_assoc()) != false)
	{
		$MemberID = $row['mem_id'];
		$MemberName = $row['mem_name'];
	
		$displayMembers .= "
			<option value=\"$MemberID\" id=\"MemberID-$DivisionID\">
				$MemberName
			</option>
		";
	}
	
	//Query for Roles Drop-Down Menu
	$roles_query = "
		select
			r.role_id
			,r.role_name
		from projectx_vvarsc2.roles r
		order by
			r.role_orderby
			,r.role_name
	";
	
	$roles_query_results = $connection->query($roles_query);
	$displayRoles = "";
	
	while(($row = $roles_query_results->fetch_assoc()) != false)
	{
		$RoleID = $row['role_id'];
		$RoleName = $row['role_name'];
	
		$displayRoles .= "
			<option value=\"$RoleID\" id=\"RoleID-$RoleID\">
				$RoleName
			</option>
		";
	}		
	
	$unitMember_query = "
		select
			um.RowID
			,m.mem_id
			,m.mem_name
			,r2.rank_level
			,r2.rank_name
			,r.role_id
			,r.role_name
			,case
				when u.UnitLeaderID = m.mem_id then 'Yes'
				else 'No'
			end as 'UnitLeader'
		from projectx_vvarsc2.Units u
		join projectx_vvarsc2.UnitMembers um
			on um.UnitID = u.UnitID
		join projectx_vvarsc2.members m
			on m.mem_id = um.MemberID
		join projectx_vvarsc2.roles r
			on r.role_id = um.MemberRoleID
		join projectx_vvarsc2.ranks r2
			on r2.rank_id = m.ranks_rank_id
		where u.UnitID = '$unit_id'	
	";
	
	$unitMember_query_results = $connection->query($unitMember_query);
	$displayUnitMembers = "";
	
	while(($row = $unitMember_query_results->fetch_assoc()) != false)
	{
		$rowID = $row['RowID'];
		$memID = $row['mem_id'];
		$memName = $row['mem_name'];
		$rankLevel = $row['rank_level'];
		$rankName = $row['rank_name'];
		$roleID = $row['role_id'];
		$roleName = $row['role_name'];
		$unitLeader = $row['UnitLeader'];
	
		$displayUnitMembers .= "
			<tr class=\"adminTableRow\" data-unitid=\"$unit_id\">
				<td class=\"adminTableRowTD rowID\" data-rowid=\"$rowID\">
					$rowID
				</td>
				<td class=\"adminTableRowTD memID\" data-memid=\"$memID\">
					$memID
				</td>
				<td class=\"adminTableRowTD memName\" data-memname=\"$memName\">
					$memName
				</td>
				<td class=\"adminTableRowTD rankLevel\" data-ranklevel=\"$rankLevel\">
					$rankLevel
				</td>
				<td class=\"adminTableRowTD rankName\" data-rankname=\"$rankName\">
					$rankName
				</td>
				<td class=\"adminTableRowTD roleID\" data-roleid=\"$roleID\">
					$roleID
				</td>
				<td class=\"adminTableRowTD roleName\" data-rolename=\"$roleName\">
					$roleName
				</td>
				<td class=\"adminTableRowTD unitLeader\" data-unitleader=\"$unitLeader\">
					$unitLeader
				</td>
				<td class=\"adminTableRowTD\">
		";
				if($unitLeader == "No")
				$displayUnitMembers .= "
					<button class=\"adminButton adminButtonAssignLeader\">
						Assign Leader
					</button>
				";
				$displayUnitMembers .= "
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
		$("textarea").height( $("textarea")[0].scrollHeight );
		var dialog0 = $('#dialog-form');
		dialog0.hide();
		
		//Populate UnitDetails Form on Load
		var dialog = $('#dialog-form-edit');
		
		var unitID = dialog.find('.adminEntryRow.unitID').data("id");
		var parentUnitName = dialog.find('.adminEntryRow.unitParent').data("parentname");
		var unitName = dialog.find('.adminEntryRow.unitName').data("name");
		var unitShortName = dialog.find('.adminEntryRow.unitShortName').data("shortname");
		var unitFullName = dialog.find('.adminEntryRow.unitFullName').data("fullname");
		var unitDivision = dialog.find('.adminEntryRow.unitDivision').data("divid");
		var unitLevel = dialog.find('.adminEntryRow.unitLevel').data("level");
		var unitIsActive = dialog.find('.adminEntryRow.unitIsActive').data("isactive");
		var unitBackgroundImage = dialog.find('.adminEntryRow.unitBackgroundImage').data("backgroundimage");
		
		dialog.find('#ID').val(unitID).text();
		dialog.find('#ParentUnitName').val(parentUnitName).text();
		dialog.find('#Name').val(unitName).text();
		dialog.find('#ShortName').val(unitShortName).text();
		dialog.find('#FullName').val(unitFullName).text();
		dialog.find('#Division').find('#' + unitDivision).prop('selected',true);
		dialog.find('#Level').val(unitLevel).text();
		dialog.find('#IsActive').find('#IsActive-' + unitIsActive).prop('selected',true);
		dialog.find('#BackgroundImage').val(unitBackgroundImage).text();		
	});
	
	$(function() {

		var overlay = $('#overlay');
		
		//Add Member
		$('#adminAddMember').click(function() {
			var dialog = $('#dialog-form-createMember');
			var $self = jQuery(this);
			
			var unitID = $('.adminTableRow').data("unitid");

			dialog.find('#UnitID').val(unitID).text();
			dialog.find('#MemberID').find('#MemberID-default').prop('selected',true);
			dialog.find('#RoleID').find('#RoleID-default').prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.adminTableContainer').css({
				filter: 'blur(2px)'
			});		
			
		});
		
		//Assign Member as Leader
		$('.adminButton.adminButtonAssignLeader').click(function() {
			var dialog = $('#dialog-form-assignMemberAsLeader');
			var $self = jQuery(this);
			
			var unitID = $('.adminTableRow').data("unitid");
			var memID = $self.parent().parent().find('.adminTableRowTD.memID').data("memid");
			var memName = $self.parent().parent().find('.adminTableRowTD.memName').data("memname");
			var roleName = $self.parent().parent().find('.adminTableRowTD.roleName').data("rolename");

			dialog.find('#UnitID').val(unitID).text();
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#MemberName').val(memName).text();
			dialog.find('#RoleName').val(roleName).text();
			
			dialog.show();
			overlay.show();
			$('.adminTableContainer').css({
				filter: 'blur(2px)'
			});		
			
		});
		
		//Edit Member
		$('.adminButton.adminButtonEdit').click(function() {
			var dialog = $('#dialog-form-editMember');
			
			var $self = jQuery(this);
			
			var unitID = $('.adminTableRow').data("unitid");
			var rowID = $self.parent().parent().find('.adminTableRowTD.rowID').data("rowid");
			var memID = $self.parent().parent().find('.adminTableRowTD.memID').data("memid");
			var memName = $self.parent().parent().find('.adminTableRowTD.memName').data("memname");
			var roleID = $self.parent().parent().find('.adminTableRowTD.roleID').data("roleid");
			var roleName = $self.parent().parent().find('.adminTableRowTD.roleName').data("rolename");
			
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#UnitID').val(unitID).text();
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#MemberName').val(memName).text();
			
			dialog.find('#RoleID').find('option').prop('selected',false);
			dialog.find('#RoleID').find('#RoleID-' + roleID).prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.adminTableContainer').css({
				filter: 'blur(2px)'
			});
		});
		
		//Delete Member
		$('.adminButton.adminButtonDelete').click(function() {
			var dialog = $('#dialog-form-deleteMember');
			
			var $self = jQuery(this);
			
			var unitID = $('.adminTableRow').data("unitid");
			var rowID = $self.parent().parent().find('.adminTableRowTD.rowID').data("rowid");
			var memID = $self.parent().parent().find('.adminTableRowTD.memID').data("memid");
			var memName = $self.parent().parent().find('.adminTableRowTD.memName').data("memname");
			var roleName = $self.parent().parent().find('.adminTableRowTD.roleName').data("rolename");
			
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#UnitID').val(unitID).text();
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#MemberName').val(memName).text();
			dialog.find('#RoleName').val(roleName).text();
			
			dialog.show();
			overlay.show();
			$('.adminTableContainer').css({
				filter: 'blur(2px)'
			});
		});
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('#UnitID').val("").text();
			$('.adminDiaglogFormFieldset').find('#MemberID').val("").text();
			$('.adminDiaglogFormFieldset').find('#MemberName').val("").text();
			$('.adminDiaglogFormFieldset').find('#RoleName').val("").text();
			
			$('.adminDiaglogFormFieldset').find('#MemberID').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#RoleID').find('option').prop('selected',false);
			
			$('.adminDiaglogFormFieldset').find('#RowID').val("").text();
			
			/*
			$('.adminDiaglogFormFieldset').find('#ID').val("").text();
			$('.adminDiaglogFormFieldset').find('#Name').val("").text();
			$('.adminDiaglogFormFieldset').find('#ShortName').val("").text();
			$('.adminDiaglogFormFieldset').find('#FullName').val("").text();
			$('.adminDiaglogFormFieldset').find('#Division').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#IsActive').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#Level').val("").text();
			*/
			
			//Hide All Dialog Containers
			$('#dialog-form-AssignMemberAsLeader').hide();
			$('#dialog-form-createMember').hide();
			$('#dialog-form-editMember').hide();
			$('#dialog-form-deleteMember').hide();
			
			overlay.hide();
			$('.adminTableContainer').css({
				filter: 'none'
			});
		});
		
	});
</script>

<br />
<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="http://sc.vvarmachine.com/admin_units">&#8672; Back to Unit Management</a>
	</div>
</div>
<h2>Unit Management - <? echo $unitName ?> (under construction)</h2>
<div id="TEXT">
	<div id="adminUnitDetailsTableContainer" class="adminTableContainer">
	
		<!--MAIN FORM (Unit EDIT) -->
		<form class="adminDialogForm adminObjectDetails" id="dialog-form-edit" action="/functions/function_unit_Edit.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<div class="adminEntryRow unitID" data-id="<? echo $unitID ?>">
					<div class="adminDetailEntryKey">
						ID
					</div>
					<div class="adminDetailEntryValue">
						<input type="text" name="ID" id="ID" class="adminDialogTextInput" readonly>
						</input>
					</div>
				</div>
				<div class="adminEntryRow unitParent" data-parentname="<? echo $parentUnitName ?>" data-parentid="<? echo $parentUnitID ?>">
					<div class="adminDetailEntryKey">
						ParentUnitName
					</div>
					<div class="adminDetailEntryValue">
						<input type="text" name="ParentUnitName" id="ParentUnitName" class="adminDialogTextInput" readonly>
						</input>
					</div>
				</div>
				<div class="adminEntryRow unitName" data-name="<? echo $unitName ?>">
					<div class="adminDetailEntryKey">
						Name
					</div>
					<div class="adminDetailEntryValue">
						<input type="text" name="Name" id="Name" class="adminDialogTextInput">
						</input>
					</div>
				</div>
				<div class="adminEntryRow unitShortName" data-shortname="<? echo $unitShortName ?>">
					<div class="adminDetailEntryKey">
						ShortName
					</div>
					<div class="adminDetailEntryValue">
						<input type="text" name="ShortName" id="ShortName" class="adminDialogTextInput">
						</input>
					</div>
				</div>
				<div class="adminEntryRow unitFullName" data-fullname="<? echo $unitFullName ?>">
					<div class="adminDetailEntryKey">
						FullName
					</div>
					<div class="adminDetailEntryValue">
						<input type="text" name="FullName" id="FullName" class="adminDialogTextInput">
						</input>
					</div>
				</div>
				<div class="adminEntryRow unitDivision" data-divid="<? echo $unitDivID ?>" data-divname="<? echo $unitDivName ?>">
					<div class="adminDetailEntryKey">
						Division
					</div>
					<div class="adminDetailEntryValue">
						<select name="Division" id="Division" class="adminDialogDropDown" required>
							<option selected="true" disabled="true" value="default" id="Division-default">
								-Select Division-
							</option>						
							<? echo $displayDivisions ?>
						</select>
					</div>
				</div>
				<div class="adminEntryRow unitLevel" data-level="<? echo $unitLevel ?>">
					<div class="adminDetailEntryKey">
						Level
					</div>
					<div class="adminDetailEntryValue">
						<input type="text" name="Level" id="Level" class="adminDialogTextInput">
						</input>
					</div>
				</div>
				<div class="adminEntryRow unitIsActive" data-isactive="<? echo $unitIsActive ?>">
					<div class="adminDetailEntryKey">
						IsActive
					</div>
					<div class="adminDetailEntryValue">
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
					</div>
				</div>
				<div class="adminEntryRow unitDescription">
					<div class="adminDetailEntryKey">
						Description
					</div>
					<div class="adminDetailEntryValue">
						<textarea name="Description" id="Description" class="adminDialogTextArea"><? echo $unitDescription ?></textarea>
					</div>
				</div>
				<div class="adminEntryRow unitBackgroundImage" data-backgroundimage="<? echo $unitBackgroundImage ?>">
					<div class="adminDetailEntryKey">
						BackgroundImage
					</div>
					<div class="adminDetailEntryValue">
						<input type="text" name="BackgroundImage" id="BackgroundImage" class="adminDialogTextInput">
						</input>
					</div>
				</div>				
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Update Unit
				</button>
			</div>			
		</form>
		
		<br />
		<!--Members Table-->
		<button id="adminAddMember" class="adminButton adminButtonCreate">Add Member To Unit</button>
		<table id="adminMemberTable" class="adminTable">
			<tr class="adminTableHeaderRow">
				<td class="adminTableHeaderRowTD">
					RowID
				</td>
				<td class="adminTableHeaderRowTD">
					MemberID
				</td>
				<td class="adminTableHeaderRowTD">
					MemberName
				</td>
				<td class="adminTableHeaderRowTD">
					RankLevel
				</td>
				<td class="adminTableHeaderRowTD">
					RankName
				</td>
				<td class="adminTableHeaderRowTD">
					RoleID
				</td>
				<td class="adminTableHeaderRowTD">
					RoleName
				</td>
				<td class="adminTableHeaderRowTD">
					UnitLeader
				</td>
				<td class="adminTableHeaderRowTD">
					Actions
				</td>
			</tr>
			<? echo $displayUnitMembers ?>
		</table>
		
	</div>
	
	<!--Add Member Form-->
	<div id="dialog-form-createMember" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Add Member to Unit Here!</p>
		<p class="validateTips">All Fields are Required.</p>
		<form class="adminDialogForm" action="/functions/function_unit_AddMember.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<!--
				<label for="RowID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" style="display: none" readonly>
				-->
				<label for="UnitID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitID" id="UnitID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="MemberID" class="adminDialogInputLabel">
					Member
				</label>
				<select name="MemberID" id="MemberID" class="adminDialogDropDown">
					<option selected disabled value="default" id="MemberID-default">
						- Select a Member -
					</option>	
					<? echo $displayMembers ?>
				</select>
				
				<label for="RoleID" class="adminDialogInputLabel">
					Role
				</label>
				<select name="RoleID" id="RoleID" class="adminDialogDropDown">
					<option selected disabled value="default" id="RoleID-default">
						- Select a Role -
					</option>	
					<? echo $displayRoles ?>
				</select>
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
	</div>
	
	<!--Add Member as Leader Form-->
	<div id="dialog-form-assignMemberAsLeader" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Please Confirm Change in Leader</p>
		<form class="adminDialogForm" action="/functions/function_unit_AssignMemberAsLeader.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">

				<label for="UnitID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitID" id="UnitID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="MemberID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="MemberID" id="MemberID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="MemberName" class="adminDialogInputLabel">
					MemberName
				</label>
				<input type="none" name="MemberName" id="MemberName" value="" class="adminDialogTextInput" readonly>
				
				<label for="RoleName" class="adminDialogInputLabel">
					MemberRole
				</label>
				<input type="none" name="RoleName" id="RoleName" value="" class="adminDialogTextInput" readonly>
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
	</div>	
		
	<!--Edit Member Form-->
	<div id="dialog-form-editMember" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update UnitMember Information</p>
		<form class="adminDialogForm" action="/functions/function_unit_EditMember.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">

				<label for="RowID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="UnitID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitID" id="UnitID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="MemberID" class="adminDialogInputLabel">
					MemberID
				</label>
				<input type="none" name="MemberID" id="MemberID" value="" class="adminDialogTextInput" readonly>
				
				<label for="MemberName" class="adminDialogInputLabel">
					MemberName
				</label>
				<input type="none" name="MemberName" id="MemberName" value="" class="adminDialogTextInput" readonly>				
				
				<label for="RoleID" class="adminDialogInputLabel">
					Role
				</label>
				<select name="RoleID" id="RoleID" class="adminDialogDropDown">
					<option selected disabled value="default" id="RoleID-default">
						- Select a Role -
					</option>	
					<? echo $displayRoles ?>
				</select>
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
	</div>

	<!--Delete Member Form-->
	<div id="dialog-form-deleteMember" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Confirmation Required!</p>
		<p class="validateTips">Are you sure you want to Remove this Member from the Unit?</p>
		<form class="adminDialogForm" action="/functions/function_unit_DeleteMember.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">

				<label for="RowID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" style="display: none" readonly>

				<label for="UnitID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="UnitID" id="UnitID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="MemberID" class="adminDialogInputLabel">
					MemberID
				</label>
				<input type="none" name="MemberID" id="MemberID" value="" class="adminDialogTextInput" readonly>
				
				<label for="MemberName" class="adminDialogInputLabel">
					MemberName
				</label>
				<input type="none" name="MemberName" id="MemberName" value="" class="adminDialogTextInput" readonly>				
				
				<label for="RoleName" class="adminDialogInputLabel">
					Role
				</label>
				<input type="none" name="RoleName" id="RoleName" value="" class="adminDialogTextInput" readonly>
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Confirm Delete
				</button>
			</div>
		</form>
	</div>
</div>