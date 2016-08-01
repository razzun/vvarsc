<?php include_once('functions/function_auth_admin.php'); ?>

<?php 
	$members_query = "
		select
			m.mem_id
			,m.mem_name
			,m.mem_callsign
			,r.rank_id
			,r.rank_level
			,r.rank_name
			,r.rank_tinyImage as rank_image
			,d.div_id
			,d.div_name
		from projectx_vvarsc2.members m
		left join projectx_vvarsc2.ranks r
			on r.rank_id = m.ranks_rank_id
		left join projectx_vvarsc2.divisions d
			on d.div_id = m.divisions_div_id
		where m.mem_sc = 1
		order by
			m.mem_name
	";
	
	$members_query_results = $connection->query($members_query);
	$displayMembers = "";
	
	while(($row = $members_query_results->fetch_assoc()) != false)
	{
		$memID = $row['mem_id'];
		$memName = $row['mem_name'];
		$memCallsign = $row['mem_callsign'];
		$memRankID = $row['rank_id'];
		$memRankLevel = $row['rank_level'];
		$memRankName = $row['rank_name'];
		$rank_image = $row['rank_image'];
		$memDivisionID = $row['div_id'];
		$memDivisionName = $row['div_name'];
		
	
		$displayMembers .= "
			<tr class=\"adminTableRow\">
				<td class=\"adminTableRowTD memID\" data-id=\"$memID\">
					$memID					
				</td>
				<td class=\"adminTableRowTD memName\" data-name=\"$memName\">
					<a href=\"../player/$memID\" target=\"_top\">
						$memName
					</a>
				</td>
				<td class=\"adminTableRowTD memCallsign\" data-callsign=\"$memCallsign\">
					$memCallsign
				</td>
				<td class=\"adminTableRowTD memRankInfo\" data-rankid=\"$memRankID\">
					<div class=\"clickableRow_memRank_inner\">
						<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/navy/$rank_image.png\" />
						<div class=\"rank_image_text\">
							$memRankLevel - $memRankName
						</div>
					</div>
				</td>
				<td class=\"adminTableRowTD memDivisionInfo\" data-divinfo=\"$memDivisionID\">
					$memDivisionName
				</td>
				<td class=\"adminTableRowTD\">
					<button class=\"adminButton adminButtonPlayerShips\">
						Manage Ships
					</button>
					<button class=\"adminButton adminButtonEdit\">
						Edit Member
					</button>
					<button class=\"adminButton adminButtonDelete\">
						Delete
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
		$('#adminCreateMember').click(function() {
			var dialog = $('#dialog-form-create');
			
			dialog.find('#Rank').find('#Rank-default').prop('selected',true);
			dialog.find('#Division').find('#Division-default').prop('selected',true);
			
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
			
			var memID = $self.parent().parent().find('.adminTableRowTD.memID').data("id");
			var memName = $self.parent().parent().find('.adminTableRowTD.memName').data("name");
			var memCallsign = $self.parent().parent().find('.adminTableRowTD.memCallsign').data("callsign");
			var memRankID = $self.parent().parent().find('.adminTableRowTD.memRankInfo').data("rankid");
			var memDivisionInfo = $self.parent().parent().find('.adminTableRowTD.memDivisionInfo').data("divinfo");
			
			dialog.find('#ID').val(memID).text();
			dialog.find('#Name').val(memName).text();
			dialog.find('#Callsign').val(memCallsign).text();
			
			dialog.find('#Rank').find('option').prop('selected',false);
			dialog.find('#Rank').find('#' + memRankID).prop('selected',true);
			
			dialog.find('#Division').find('option').prop('selected',false);
			dialog.find('#Division').find('#' + memDivisionInfo).prop('selected',true);
			
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
			
			var memID = $self.parent().parent().find('.adminTableRowTD.memID').data("id");
			var memName = $self.parent().parent().find('.adminTableRowTD.memName').data("name");
			var memCallsign = $self.parent().parent().find('.adminTableRowTD.memCallsign').data("callsign");
			var memRankID = $self.parent().parent().find('.adminTableRowTD.memRankInfo').data("rankid");
			var memDivisionInfo = $self.parent().parent().find('.adminTableRowTD.memDivisionInfo').data("divinfo");
			
			dialog.find('#ID').val(memID).text();
			dialog.find('#Name').val(memName).text();
			dialog.find('#Callsign').val(memCallsign).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
		});
		
		//PlayerShips
		$('.adminButton.adminButtonPlayerShips').click(function() {
			var $self = jQuery(this);
			var memID = $self.parent().parent().find('.adminTableRowTD.memID').data("id");
			
			window.location.href = "?page=admin_playerShips&pid=" + memID;
		});	
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
		
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('#Rank').find('option').prop('selected',false);
			$('.adminDiaglogFormFieldset').find('#Division').find('option').prop('selected',false);
			
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
		<a href="http://sc.vvarmachine.com/admin/">&#8672; Back to Admin Home</a>
	</div>
</div>
<h2>Member Management</h2>
<div id="TEXT">
	<div id="adminMemberTableContainer" class="adminTableContainer">
		<button id="adminCreateMember" class="adminButton adminButtonCreate">Add New Member</button>
		<table id="adminMemberTable" class="adminTable">
			<tr class="adminTableHeaderRow">
				<td class="adminTableHeaderRowTD">
					ID
				</td>
				<td class="adminTableHeaderRowTD">
					Name
				</td>
				<td class="adminTableHeaderRowTD">
					Callsign
				</td>
				<td class="adminTableHeaderRowTD">
					Rank Info
				</td>
				<td class="adminTableHeaderRowTD">
					Division Info
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
					<select name="Rank" id="Rank" class="adminDialogDropDown">
						<option selected disabled value="default" id="Rank-default">
							- Select a Rank -
						</option>	
						<? echo $displayRanks ?>
					</select>
					
					<label for="Division" class="adminDialogInputLabel">
						Division
					</label>
					<select name="Division" id="Division" class="adminDialogDropDown">
						<option selected disabled value="default" id="Division-default">
							- Select a Division -
						</option>	
						<? echo $displayDivisions ?>
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
			<p class="validateTips">Update Member Information</p>
			<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/function_mem_Edit.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
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
					<select name="Rank" id="Rank" class="adminDialogDropDown">
						<? echo $displayRanks ?>
					</select>
					
					<label for="Division" class="adminDialogInputLabel">
						Division
					</label>
					<select name="Division" id="Division" class="adminDialogDropDown">
						<? echo $displayDivisions ?>
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
			<p class="validateTips">Are you sure you want to Delete this Member? Bad Things Could Happen!</p>
			<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/function_mem_Delete.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" readonly>
					
					<label for="Callsign" class="adminDialogInputLabel">
						Callsign (RSI Handle)
					</label>
					<input type="text" name="Callsign" id="Callsign" value="" class="adminDialogTextInput" readonly>
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