<?php include_once('../functions/function_auth_admin.php'); ?>

<?php 
	$members_query = "
		select
			m1.id as 'VVarID'
			,m1.m_username as 'MemName'
			,m1.m_email_address as 'EmailAddress'
		from
		(
			SELECT
				m.id
				,m.m_username
				,m.m_email_address
			FROM projectx_ocp2.jdy_f_members m
			join projectx_ocp2.jdy_f_group_members gm
				on gm.gm_member_id = m.id
				AND gm.gm_group_id = '89'
		) m1
		left join (
			select
				m.mem_id
				,m.mem_name
				,m.mem_callsign
				,m.vvar_id
			from projectx_vvarsc2.members m
			where m.mem_sc = 1
		) m2
			on m2.vvar_id = m1.id
		where m2.mem_id is null
	";
	
	$members_query_results = $connection->query($members_query);
	$displayMembers = "";
	
	while(($row = $members_query_results->fetch_assoc()) != false)
	{
		$memVVarID = $row['VVarID'];
		$memName = $row['MemName'];
		$memEmailAddress = $row['EmailAddress'];
	
		$displayMembers .= "
			<tr class=\"adminTableRow\">
				<td class=\"adminTableRowTD memVVarID\" data-vvarid=\"$memVVarID\">
					$memVVarID					
				</td>
				<td class=\"adminTableRowTD memName\" data-name=\"$memName\">
					$memName
				</td>
				<td class=\"adminTableRowTD email\" data-name=\"$memEmailAddress\">
					$memEmailAddress
				</td>
				<td class=\"adminTableRowTD\">
					<button class=\"adminButton adminButtonEdit\" title=\"Sync Member\" style=\"margin-right: 0px;\">
						Sync Member
						<img height=\"20px\" class=\"adminButtonImage\" src=\"http://sc.vvarmachine.com/images/misc/button_edit.png\">
					</button>
				</td>
			</tr>
		";
	}
	
	$rank_query = "
		select
			r.rank_id
			,r.rank_level
			,r.rank_name
		from projectx_vvarsc2.ranks r
		order by
			r.rank_orderby
	";
	
	$rank_query_results = $connection->query($rank_query);
	$displayRanks = "";
	
	while(($row = $rank_query_results->fetch_assoc()) != false)
	{
		$RankID = $row['rank_id'];
		$RankLevel = $row['rank_level'];
		$RankName = $row['rank_name'];
	
		$displayRanks .= "
			<option value=\"$RankID\" id=\"$RankID\">
				$RankLevel - $RankName
			</option>
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
		$('.adminButton.adminButtonEdit').click(function() {
			var dialog = $('#dialog-form-create');
			
			var $self = jQuery(this);
			
			var memVVarID = $self.parent().parent().find('.adminTableRowTD.memVVarID').data("vvarid");
			var memName = $self.parent().parent().find('.adminTableRowTD.memName').data("name");
			
			dialog.find('#VVarID').val(memVVarID).text();
			dialog.find('#Name').val(memName).text();
			dialog.find('#Callsign').val(memName).text();
			
			dialog.find('#Rank').find('#Rank-default').prop('selected',true);
			dialog.find('#Division').find('#Division-default').prop('selected',true);
			dialog.find('#MembershipType').find('#MembershipType-default').prop('selected',true);
			dialog.find('#InfoSecLevel').find('#InfoSecLevel-default').prop('selected',true);
			
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
			$('.adminDiaglogFormFieldset').find('#Rank').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#Division').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#MembershipType').find('option').prop('selected',false);
			
			//Hide All Dialog Containers
			$('#dialog-form-create').hide();
			
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
		<a href="http://sc.vvarmachine.com/admin/?page=admin_mem">&#8672; Back to Member Management Home</a>
	</div>
</div>
<h2 id="MainPageHeaderText">Member Management - Sync from Main Website</h2>
<p style="width: 95%; margin-right: auto; margin-left: auto;">
	The Member Accounts listed below are those which have been successfully registered on VVarMachine.com and belong to the StarCitizen Forum Group, but are not yet created on this site. Use the Action Button to create these members as needed.
</p>
<div id="TEXT">
	<div id="adminMemberTableContainer" class="adminTableContainer">
		<table id="adminMemberTable" class="adminTable">
			<tr class="adminTableHeaderRow">
				<td class="adminTableHeaderRowTD">
					VVarID
				</td>
				<td class="adminTableHeaderRowTD">
					UserName
				</td>
				<td class="adminTableHeaderRowTD">
					EmailAddress
				</td>
				<td class="adminTableHeaderRowTD">
					Actions
				</td>
			</tr>
			<? echo $displayMembers ?>
		</table>
		
		<!--Create Form-->
		<div id="dialog-form-create" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Enter new Member Information Below.</p>
			<p class="validateTips">All Fields are Required.</p>
			<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/function_mem_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="VVarID" class="adminDialogInputLabel">
						VVarMachine ID (from Main Website)
					</label>
					<input type="text" name="VVarID" id="VVarID" value="" class="adminDialogTextInput" readonly>
					
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>
					
					<label for="Callsign" class="adminDialogInputLabel">
						Callsign (RSI Handle)
					</label>
					<input type="text" name="Callsign" id="Callsign" value="" class="adminDialogTextInput" required>
					
					<label for="Rank" class="adminDialogInputLabel">
						Rank
					</label>
					<select name="Rank" id="Rank" class="adminDialogDropDown" required>
						<option selected disabled value="default" id="Rank-default">
							- Select a Rank -
						</option>	
						<? echo $displayRanks ?>
					</select>
					
					<label for="Division" class="adminDialogInputLabel">
						Division
					</label>
					<select name="Division" id="Division" class="adminDialogDropDown" required>
						<option selected disabled value="default" id="Division-default">
							- Select a Division -
						</option>	
						<? echo $displayDivisions ?>
					</select>
					<label for="MembershipType" class="adminDialogInputLabel">
						MembershipType
					</label>
					<select name="MembershipType" id="MembershipType" class="adminDialogDropDown" required>
						<option selected disabled value="default" id="MembershipType-default">
							- Select a MembershipType -
						</option>
						<option value="0" id="MembershipType-0">
							Affiliate
						</option>
						<option value="1" id="MembershipType-1">
							Main
						</option>
					</select>
					<label for="InfoSecLevel" class="adminDialogInputLabel">
						InfoSEC Clearance Level
					</label>
					<select name="InfoSecLevel" id="InfoSecLevel" class="adminDialogDropDown" required>
						<option selected disabled value="default" id="InfoSecLevel-default">
							- Select a Clearance Level for InfoSEC -
						</option>
						<option value="1" id="InfoSecLevel-1">
							1 - Low Security
						</option>
						<option value="2" id="InfoSecLevel-2">
							2 - Medium Security
						</option>
						<option value="3" id="InfoSecLevel-3">
							3 - High Security
						</option>
						<option value="4" id="InfoSecLevel-4">
							4 - Classified / Top-Secret
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
		
	</div>
</div>