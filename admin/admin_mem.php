<?php include_once('../functions/function_auth_admin.php'); ?>

<?php 
	$members_query = "
		select
			m.mem_id
			,m.mem_name
			,m.mem_callsign
			,m.membership_type
			,m.mem_status
			,r.rank_id
			,r.rank_level
			,r.rank_name
			,r.rank_tinyImage as rank_image
			,d.div_id
			,d.div_name
			,lk1.InfoSecLevelID
			,lk1.InfoSecLevelName
			,lk1.InfoSecLevelShortName
			,m.vvar_id
			,m.mem_email
		from projectx_vvarsc2.members m
		left join projectx_vvarsc2.ranks r
			on r.rank_id = m.ranks_rank_id
		left join projectx_vvarsc2.divisions d
			on d.div_id = m.divisions_div_id
		join projectx_vvarsc2.LK_InfoSecLevels lk1
			on lk1.InfoSecLevelID = m.InfoSecLevelID
		where m.mem_sc = 1
		order by
			m.mem_callsign
	";
	
	$members_query_results = $connection->query($members_query);
	$displayMembers = "";
	
	while(($row = $members_query_results->fetch_assoc()) != false)
	{
		$memID = $row['mem_id'];
		$memVVarID = $row['vvar_id'];
		$memName = $row['mem_name'];
		$memEmail = $row['mem_email'];
		$memCallsign = $row['mem_callsign'];
		$memStatus = $row['mem_status'];
		$memRankID = $row['rank_id'];
		$memRankLevel = $row['rank_level'];
		$memRankName = $row['rank_name'];
		$rank_image = $row['rank_image'];
		$memDivisionID = $row['div_id'];
		$memDivisionName = $row['div_name'];
		$membershipType = $row['membership_type'];
		$infoSecLevelID = $row['InfoSecLevelID'];
		$infoSecLevelName = $row['InfoSecLevelName'];
		$infoSecLevelShortName = $row['InfoSecLevelShortName'];
		
		$displayMembershipType = "";
		
		if ($membershipType == 1)
		{
			$displayMembershipType = 'Main';
		}
		else
		{
			$displayMembershipType = 'Affiliate';
		}
		
		$displayMembers .= "
			<div class=\"table_header_block\">
			</div>
			<div class=\"yard_filters\" style=\"margin-bottom: 8px;\"
				data-id=\"$memID\"
				data-vvarid=\"$memVVarID\"
				data-callsign=\"$memCallsign\"
				data-name=\"$memName\"
				data-rankid=\"$memRankID\"
				data-divinfo=\"$memDivisionID\"
				data-memtype=\"$membershipType\"
				data-infoseclevel=\"$infoSecLevelID\"
				data-email=\"$memEmail\"
				data-memstatus=\"$memStatus\"
			>
				<div class=\"\" style=\"
					float: right;
					text-align: right;
					margin-right: 8px;
					width: 50%;
					margin-top: 4px;
				\">
					<button class=\"adminButton adminButtonEdit\" title=\"Edit Member\" style=\"margin-right: 0px;\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"../images/misc/button_edit.png\">
					</button>
					<button class=\"adminButton adminButtonDelete\" title=\"Delete Member\" style=\"margin-left: 0px;\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"../images/misc/button_delete.png\">
					</button>
				</div>
				
				
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
						MemberID: $memID
						<br />
						<strong>$memCallsign</strong>
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
						<div class=\"clickableRow_memRank_inner\">
							<div class=\"shipDetails_ownerInfo_tableRow_ImgContainer\" style=\"
								height: 24px;
								width: 24px;
								padding-left: 0px;
								padding-right: 0px;
							\">
								<img class=\"divinfo_rankImg\" align=\"center\" style=\"
									height:16px;
									width:16px;
									display: table-cell;
									vertical-align: middle;
								\"src=\"../images/ranks/TS3/$rank_image.png\" />					
							</div>
							<div class=\"rank_image_text\">
								$memRankLevel - $memRankName
							</div>
							<button class=\"adminButton adminButtonEditRank\" style=\"
									display: table-cell;
									margin-left: 0px;
								\" title=\"Edit Member Rank\">
								<img height=\"20px\" class=\"adminButtonImage\" src=\"../images/misc/button_edit.png\">
							</button>
						</div>
					</div>
					<button class=\"adminButton adminButtonPassReset\" title=\"Reset Member Password\" style=\"
						padding-right: 4px;
						float: right;
					\">
						PassReset
					</button>
					<div class=\"player_qual_row_name\" style=\"
						margin-bottom:8px;
						padding-left:8px;
					\">
						VVAR PlayerName
						<br />
						<a href=\"../player/$memID\" target=\"_blank\"><strong>$memName</strong></a>
					</div>
					<div class=\"player_qual_row_name\" style=\"
						margin-bottom:8px;
						padding-left:8px;
					\">
						VVAR Database MemberID
						<br /><strong>$memVVarID</strong>
					</div>
					<div class=\"player_qual_row_name\" style=\"
						margin-bottom:8px;
						padding-left:8px;
					\">
						Status
						<br />
						<strong>$memStatus</strong>
					</div>
					<div class=\"player_qual_row_name\" style=\"
						margin-bottom:8px;
						padding-left:8px;
					\">
						Membership Type
						<br />
						<strong>$displayMembershipType</strong>
					</div>	
					<div class=\"player_qual_row_name\" style=\"
						margin-bottom:8px;
						padding-left:8px;
					\">
						InfoSec Level
						<br />
						<strong>$infoSecLevelShortName</strong>
					</div>
				</div>
			</div>
			
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
		$('#adminCreateMember').click(function() {
			var dialog = $('#dialog-form-create');
			
			dialog.find('#Rank').find('#Rank-default').prop('selected',true);
			dialog.find('#Division').find('#Division-default').prop('selected',true);
			dialog.find('#MembershipType').find('#MembershipType-default').prop('selected',true);
			dialog.find('#InfoSecLevel').find('#InfoSecLevel-default').prop('selected',true);
			dialog.find('#Status').find('#Status-default').prop('selected',true);
			
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
			
			var memID = $self.parent().parent().data("id");
			var vvarID = $self.parent().parent().data("vvarid");
			var memName = $self.parent().parent().data("name");
			var memCallsign = $self.parent().parent().data("callsign");
			var memDivisionInfo = $self.parent().parent().data("divinfo");
			var memTypeInfo = $self.parent().parent().data("memtype");
			var memInfoSecLevelInfo = $self.parent().parent().data("infoseclevel");
			var memStatusLevel = $self.parent().parent().data("memstatus");
			
			dialog.find('#ID').val(memID).text();
			dialog.find('#VVarID').val(vvarID).text();
			dialog.find('#Name').val(memName).text();
			dialog.find('#Callsign').val(memCallsign).text();
			
			dialog.find('#Division').find('option').prop('selected',false);
			dialog.find('#Division').find('#' + memDivisionInfo).prop('selected',true);
			
			dialog.find('#Status').find('option').prop('selected',false);
			dialog.find('#Status').find('#Status-' + memStatusLevel).prop('selected',true);
			
			dialog.find('#MembershipType').find('option').prop('selected',false);
			dialog.find('#MembershipType').find('#MembershipType-' + memTypeInfo).prop('selected',true);
			
			dialog.find('#InfoSecLevel').find('option').prop('selected',false);
			dialog.find('#InfoSecLevel').find('#InfoSecLevel-' + memInfoSecLevelInfo).prop('selected',true);
			
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
			
			var memID = $self.parent().parent().data("id");
			var vvarID = $self.parent().parent().data("vvarid");
			var memName = $self.parent().parent().data("name");
			var memCallsign = $self.parent().parent().data("callsign");
			var memRankID = $self.parent().parent().data("rankid");
			var memDivisionInfo = $self.parent().parent().data("divinfo");
			var memTypeInfo = $self.parent().parent().data("memtype");
			
			dialog.find('#ID').val(memID).text();
			dialog.find('#VVarID').val(vvarID).text();
			dialog.find('#Name').val(memName).text();
			dialog.find('#Callsign').val(memCallsign).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
		});
		
		//Password Reset
		$('.adminButton.adminButtonPassReset').click(function() {
			var dialog = $('#dialog-form-passReset');
			
			var $self = jQuery(this);
			
			var memID = $self.parent().parent().data("id");
			var memName = $self.parent().parent().data("name");
			var memEmail = $self.parent().parent().data("email");
			var memCallsign = $self.parent().parent().data("callsign");
			
			dialog.find('#ID').val(memID).text();
			dialog.find('#Name').val(memName).text();
			dialog.find('#Callsign').val(memCallsign).text();
			dialog.find('#Email').val(memEmail).text();
			
			dialog.show();
			overlay.show();
			$('.adminTable').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
		});
		
		//Edit Rank
		$('.adminButton.adminButtonEditRank').click(function() {
			var dialog = $('#dialog-form-editRank');
			
			var $self = jQuery(this);
			
			var memID = $self.parent().parent().parent().parent().data("id");
			var memName = $self.parent().parent().parent().parent().data("name");
			var memCallsign = $self.parent().parent().parent().parent().data("callsign");
			var memRankID = $self.parent().parent().parent().parent().data("rankid");
			
			dialog.find('#ID').val(memID).text();
			dialog.find('#Name').val(memName).text();
			dialog.find('#Callsign').val(memCallsign).text();
			
			dialog.find('#Rank').find('option').prop('selected',false);
			dialog.find('#Rank').find('#' + memRankID).prop('selected',true);
			
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
			$('#dialog-form-edit').hide();
			$('#dialog-form-editRank').hide();
			$('#dialog-form-delete').hide();
			$('#dialog-form-passReset').hide();
			
			overlay.hide();
			$('.adminTable').css({
				filter: 'none'
			});
			$('#MainPageHeaderText').css({
				filter: 'none'
			});
		});
		
		//ESC Key Pressed
		$(document).on('keyup',function(e) {
			if (e.keyCode == 27) {
				//Clear DropDown Selections
				$('.adminDiaglogFormFieldset').find('#Rank').find('option').prop('selected',false);
				$('.adminDiaglogFormFieldset').find('#Division').find('option').prop('selected',false);
				$('.adminDiaglogFormFieldset').find('#MembershipType').find('option').prop('selected',false);
				
				//Hide All Dialog Containers
				$('#dialog-form-create').hide();
				$('#dialog-form-edit').hide();
				$('#dialog-form-editRank').hide();
				$('#dialog-form-delete').hide();
				$('#dialog-form-passReset').hide();
				
				overlay.hide();
				$('.adminTable').css({
					filter: 'none'
				});
				$('#MainPageHeaderText').css({
					filter: 'none'
				});
			};
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
</script>

<br />
<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="?page=admin">&#8672; Back to Admin Home</a>
	</div>
	<div class="div_filters_entry">
		<a href="../admin/?page=admin_mem_sync">Sync Members from Main Website</a>
	</div>
</div>
<h2 id="MainPageHeaderText">Member Management</h2>
<div id="TEXT">
	<div id="adminMemberTableContainer" class="adminTableContainer">
		<button id="adminCreateMember" class="adminButton adminButtonCreate" title="Add New Member" style="
			margin-left: 0px;
			margin-right: 2%;
			width: 100%;
			text-align: right;
		">
			<img height="20px" class="adminButtonImage" src="../images/misc/button_add.png">
			Add New Member
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
				<? echo $displayMembers ?>
			</div>
		</div>
		
		<!--Create Form-->
		<div id="dialog-form-create" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Enter new Member Information Below.</p>
			<p class="validateTips">NOTE: This is creating a member outside of the normal "sync" process between the two sites. If this member is subsequently registered on the main site, their VVarID will have to be updated on here.</p>
			<form class="adminDialogForm" action="../functions/function_mem_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="VVarID" class="adminDialogInputLabel">
						VVarMachine ID (from Main Website)
					</label>
					<input type="text" name="VVarID" id="VVarID" value="" class="adminDialogTextInput">
					
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
					
					<label for="Status" class="adminDialogInputLabel">
						Status
					</label>
					<select name="Status" id="Status" class="adminDialogDropDown" required>
						<option selected disabled value="default" id="Status-default">
							- Select a Status -
						</option>
						<option value="Active" id="Status-Active">
							Active
						</option>
						<option value="Active Reserve" id="Status-Active Reserve">
							Active Reserve
						</option>
						<option value="Inactive" id="Status-Inactive">
							Inactive
						</option>
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
		
		<!--Edit Form-->
		<div id="dialog-form-edit" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Update Member Information</p>
			<form class="adminDialogForm" action="../functions/function_mem_Edit.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
					<label for="VVarID" class="adminDialogInputLabel">
						VVarMachine ID (from Main Website)
					</label>
					<input type="text" name="VVarID" id="VVarID" value="" class="adminDialogTextInput">
					
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus>
					
					<label for="Callsign" class="adminDialogInputLabel">
						Callsign (RSI Handle)
					</label>
					<input type="text" name="Callsign" id="Callsign" value="" class="adminDialogTextInput" required>
					
					<label for="Status" class="adminDialogInputLabel">
						Status
					</label>
					<select name="Status" id="Status" class="adminDialogDropDown" required>
						<option selected disabled value="default" id="Status-default">
							- Select a Status -
						</option>
						<option value="Active" id="Status-Active">
							Active
						</option>
						<option value="Active Reserve" id="Status-Active Reserve">
							Active Reserve
						</option>
						<option value="Inactive" id="Status-Inactive">
							Inactive
						</option>
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
	
		<!--Update Rank Form-->
		<div id="dialog-form-editRank" class="adminDialogFormContainer">	
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Update Member Rank</p>
			<form class="adminDialogForm" action="../functions/function_mem_EditRank.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					<label for="ID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
					<label for="Name" class="adminDialogInputLabel">
						Name
					</label>
					<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required readonly>
					
					<label for="Callsign" class="adminDialogInputLabel">
						Callsign (RSI Handle)
					</label>
					<input type="text" name="Callsign" id="Callsign" value="" class="adminDialogTextInput" required readonly>
					
					<label for="Rank" class="adminDialogInputLabel">
						Rank
					</label>
					<select name="Rank" id="Rank" class="adminDialogDropDown">
						<? echo $displayRanks ?>
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
			<form class="adminDialogForm" action="../functions/function_mem_Delete.php" method="POST" role="form">
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
		
		<!--Password Reset Form-->
		<div id="dialog-form-passReset" class="adminDialogFormContainer">
			<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
				Cancel
			</button>
			<p class="validateTips">Confirmation Required!</p>
			<p class="validateTips">Are you sure you want to reset this member's Password?</p>
			<p class="validateTips">If the email address below is blank or not correct,<br />you will have to edit the member and update it<br />before submitting the password change.</p>
			<form class="adminDialogForm" action="../functions/function_mem_pass.php" method="POST" role="form">
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
					
					<label for="Email" class="adminDialogInputLabel">
						Email to send new password
					</label>
					<input type="text" name="Email" id="Email" value="" class="adminDialogTextInput" readonly>
				</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Confirm Password Reset
					</button>
				</div>
			</form>
		</div>
	</div>
</div>