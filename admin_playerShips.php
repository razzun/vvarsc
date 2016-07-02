<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<?php
	$player_id = strip_tags(isset($_GET[pid]) ? $_GET[pid] : '');
	
	if(is_numeric($player_id)) {
		$playerShips_query = "
			select
				shm.rowID
				,s.ship_id
				,s.ship_name
				,m.manu_shortName
				,shm.shm_package
				,shm.shm_lti
				,m2.mem_name
			from projectx_vvarsc2.members m2
			left join projectx_vvarsc2.ships_has_members shm
				on shm.members_mem_id = m2.mem_id
			left join projectx_vvarsc2.ships s
				on s.ship_id = shm.ships_ship_id
			left join projectx_vvarsc2.manufacturers m
				on m.manu_id = s.manufacturers_manu_id
			where m2.mem_id = '$player_id'
			order by
				m.manu_shortName
				,s.ship_name
		";
		
		$playerShips_query_results = $connection->query($playerShips_query);
		$displayPlayerShips = "";
		
		while(($row = $playerShips_query_results->fetch_assoc()) != false)
		{
			$rowID = $row['rowID'];
			$shipID = $row['ship_id'];
			$shipName = $row['ship_name'];
			$manuName = $row['manu_shortName'];
			$package = $row['shm_package'];
			$lti = $row['shm_lti'];
			$mem_name = $row['mem_name'];
			
			$displayPlayerShips .= "
				<tr class=\"adminTableRow\" data-memid=\"$player_id\">
					<td class=\"adminTableRowTD rowID\" data-id=\"$rowID\">
						$rowID
					</td>
					<td class=\"adminTableRowTD shipID\" data-shipid=\"$shipID\">
						$shipID
					</td>
					<td class=\"adminTableRowTD manuName\" data-manuname=\"$manuName\">
						$manuName
					</td>
					<td class=\"adminTableRowTD shipName\" data-shipname=\"$shipName\">
						$shipName
					</td>
					<td class=\"adminTableRowTD package\" data-package=\"$package\">
						$package
					</td>
					<td class=\"adminTableRowTD lti\" data-lti=\"$lti\">
						$lti
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
	
	}
	
	$ships_query = "
		select
			s.ship_id
			,m.manu_shortName
			,s.ship_name
		from projectx_vvarsc2.ships s
		join projectx_vvarsc2.manufacturers m
			on m.manu_id = s.manufacturers_manu_id
		order by
			m.manu_name
			,s.ship_name
	";
	
	$ships_query_results = $connection->query($ships_query);
	$displayShips = "";
	
	while(($row = $ships_query_results->fetch_assoc()) != false)
	{
		$ShipID = $row['ship_id'];
		$ManuName = $row['manu_shortName'];
		$ShipName = $row['ship_name'];
	
		$displayShips .= "
			<option value=\"$ShipID\" id=\"$ShipID\">
				$ManuName - $ShipName
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
		$('#adminCreateMember').click(function() {
			var dialog = $('#dialog-form-create');
			var $self = jQuery(this);
			
			var memID = $('.adminTableRow').data("memid");
			
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#ShipID').find('#ShipID-default').prop('selected',true);
			dialog.find('#Package').find('#Package-default').prop('selected',true);
			dialog.find('#LTI').find('#LTI-default').prop('selected',true);
			
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
			
			var memID = $('.adminTableRow').data("memid");
			var rowID = $self.parent().parent().find('.adminTableRowTD.rowID').data("id");
			var shipID = $self.parent().parent().find('.adminTableRowTD.shipID').data("shipid");
			var ispackage = $self.parent().parent().find('.adminTableRowTD.package').data("package");
			var islti = $self.parent().parent().find('.adminTableRowTD.lti').data("lti");

			dialog.find('#MemberID').val(memID).text();
			dialog.find('#RowID').val(rowID).text();
			
			dialog.find('#ShipID').find('option').prop('selected',false);
			dialog.find('#ShipID').find('#' + shipID).prop('selected',true);
			
			dialog.find('#Package').find('option').prop('selected',false);
			dialog.find('#Package').find('#Package-' + ispackage).prop('selected',true);
			
			dialog.find('#LTI').find('option').prop('selected',false);
			dialog.find('#LTI').find('#LTI-' + islti).prop('selected',true);

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
			
			var memID = $('.adminTableRow').data("memid");
			var rowID = $self.parent().parent().find('.adminTableRowTD.rowID').data("id");
			var shipID = $self.parent().parent().find('.adminTableRowTD.shipID').data("shipid");
			var ispackage = $self.parent().parent().find('.adminTableRowTD.package').data("package");
			var islti = $self.parent().parent().find('.adminTableRowTD.lti').data("lti");
			
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#ShipID').val(shipID).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
		});
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('#MemberID').val("").text();
			$('.adminDiaglogFormFieldset').find('#RowID').val("").text();
			$('.adminDiaglogFormFieldset').find('#ShipID').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#Package').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#LTI').find('option').prop('selected',false);
			
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
		<a href="http://sc.vvarmachine.com/admin_mem">&#8672; Back to Member Management</a>
	</div>
</div>
<h2>
	PlayerShips Management - <? echo $mem_name ?>
</h2>
<div id="TEXT">
	<div id="adminMemberTableContainer" class="adminTableContainer">
		<button id="adminCreateMember" class="adminButton adminButtonCreate">Add Ship to Member</button>
		<table id="adminMemberTable" class="adminTable">
			<tr class="adminTableHeaderRow">
				<td class="adminTableHeaderRowTD">
					RowID
				</td>
				<td class="adminTableHeaderRowTD">
					ShipID
				</td>
				<td class="adminTableHeaderRowTD">
					Manufacturer
				</td>
				<td class="adminTableHeaderRowTD">
					ShipName
				</td>
				<td class="adminTableHeaderRowTD">
					Package
				</td>
				<td class="adminTableHeaderRowTD">
					LTI
				</td>
				<td class="adminTableHeaderRowTD">
					Actions
				</td>
			</tr>
			<? echo $displayPlayerShips ?>
		</table>
		
		<!--Create Form-->
		<div id="dialog-form-create" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Add Ship to Member Here!</p>
			<p class="validateTips">All Fields are Required.</p>
			<form class="adminDialogForm" action="/functions/function_playerShip_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<!--
					<label for="RowID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" style="display: none" readonly>
					-->
					
					<label for="MemberID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="MemberID" id="MemberID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
					<label for="ShipID" class="adminDialogInputLabel">
						Ship
					</label>
					<select name="ShipID" id="ShipID" class="adminDialogDropDown">
						<option selected disabled value="default" id="ShipID-default">
							- Select a Ship -
						</option>	
						<? echo $displayShips ?>
					</select>
					
					<label for="Package" class="adminDialogInputLabel">
						Package
					</label>
					<select name="Package" id="Package" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="Package-default">
							Package?
						</option>
						<option value="1" id="Package-1">
							Yes
						</option>
						<option value="0" id="Package-0">
							No
						</option>
					</select>
					
					<label for="LTI" class="adminDialogInputLabel">
						LTI
					</label>
					<select name="LTI" id="LTI" class="adminDialogDropDown">
						<option selected disabled value="default" id="LTI-default">
							LTI?
						</option>					
						<option value="1" id="LTI-1">
							Yes
						</option>
						<option value="0" id="LTI-0">
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
			<p class="validateTips">Update PlayerShip Entry</p>
			<form class="adminDialogForm" action="/functions/function_playerShip_Edit.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="RowID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
					<label for="MemberID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="MemberID" id="MemberID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
					<label for="ShipID" class="adminDialogInputLabel">
						Ship
					</label>
					<select name="ShipID" id="ShipID" class="adminDialogDropDown">
						<? echo $displayShips ?>
					</select>
					
					<label for="Package" class="adminDialogInputLabel">
						Package
					</label>
					<select name="Package" id="Package" class="adminDialogDropDown">
						<option value="1" id="Package-1">
							Yes
						</option>
						<option value="0" id="Package-0">
							No
						</option>
					</select>
					
					<label for="LTI" class="adminDialogInputLabel">
						LTI
					</label>
					<select name="LTI" id="LTI" class="adminDialogDropDown">
						<option value="1" id="LTI-1">
							Yes
						</option>
						<option value="0" id="LTI-0">
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
			<p class="validateTips">Are you sure you want to Remove the Ship from this Member?</p>
			<form class="adminDialogForm" action="/functions/function_playerShip_Delete.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="RowID" class="adminDialogInputLabel">
						RowID
					</label>
					<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" readonly>
					
					<label for="MemberID" class="adminDialogInputLabel">
						MemberID
					</label>
					<input type="none" name="MemberID" id="MemberID" value="" class="adminDialogTextInput" readonly>
					
					<label for="ShipID" class="adminDialogInputLabel">
						Ship
					</label>
					<input type="none" name="ShipID" id="ShipID" value="" class="adminDialogTextInput" readonly>
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