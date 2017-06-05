<?php include_once('functions/function_auth_user.php'); ?>

<?php
	$player_id = strip_tags(isset($_GET['pid']) ? $_GET['pid'] : '');

	$kills_query = "
		select
			k.MemberID
			,m.mem_Callsign
			,k.RowID
			,k.KillType
			,s1.ship_id as PlayerShipID
			,s1.ship_name as PlayerShipName
			,k.TargetPlayer
			,s2.ship_id as TargetShipID
			,s2.ship_name as TargetShipName
			,k.TimeOfEngagement
			,m2.mem_id as ConfirmingMemberID
			,m2.mem_callsign as ConfirmingMemberName
			,k.IsSoloKill
			,k.Notes
		from projectx_vvarsc2.members m
		left join projectx_vvarsc2.MemberKills k
			on k.MemberID = m.mem_id
		left join projectx_vvarsc2.ships s1
			on s1.ship_id = k.ShipID
		left join projectx_vvarsc2.ships s2
			on s2.ship_id = k.TargetPlayerShip
		left join projectx_vvarsc2.members m2
			on m2.mem_id = k.ConfirmedBy
		where m.mem_id = $player_id
		order by
			k.TimeOfEngagement desc
	";
	
	$kills_query_results = $connection->query($kills_query);
	$displayKills = "";
	$mem_Callsign = "";
	
	while(($row = $kills_query_results->fetch_assoc()) != false)
	{
		$MemberID = $row['MemberID'];
		$mem_Callsign = $row['mem_Callsign'];
		$RowID = $row['RowID'];
		$KillType = $row['KillType'];
		$PlayerShipID = $row['PlayerShipID'];
		$PlayerShipName = $row['PlayerShipName'];
		$TargetPlayer = $row['TargetPlayer'];
		$TargetShipID = $row['TargetShipID'];
		$TargetShipName = $row['TargetShipName'];
		$TimeOfEngagement = $row['TimeOfEngagement'];
		$ConfirmingMemberID = $row['ConfirmingMemberID'];
		$ConfirmingMemberName = $row['ConfirmingMemberName'];
		$IsSoloKill = $row['IsSoloKill'];
		$KillNotes = $row['Notes'];
		
		if ($RowID != null)
		{
			if ($PlayerShipName == null || $PlayerShipName == "")
				$PlayerShipName = 'n/a';
				
			if ($TargetShipName == null || $TargetShipName == "")
				$TargetShipName = 'n/a';
				
			if ($IsSoloKill == 1)
				$IsSoloKill = 'Solo';
			else
				$IsSoloKill = 'Shared';
				
			if ($KillNotes == null || $KillNotes == "")
				$KillNotes = '- none -';
		
			$displayKills .= "
				<div class=\"table_header_block\">
				</div>
				<div class=\"yard_filters\" style=\"margin-bottom: 16px;\"
					data-id=\"$RowID\"
					data-killtype=\"$KillType\"
					data-playershipid=\"$PlayerShipID\"
					data-targetplayer=\"$TargetPlayer\"
					data-targetshipid=\"$TargetShipID\"
					data-time=\"$TimeOfEngagement\"
					data-confirmingmember=\"$ConfirmingMemberID\"
					data-issolokill=\"$IsSoloKill\"
				>
			";
			
			if ($_SESSION['sess_userrole'] == "admin" || $_SESSION['sess_userrole'] == "officer")
			{
				$displayKills .= "
					<div class=\"\" style=\"
						float: right;
						text-align: right;
						margin-right: 8px;
						width: 50%;
						margin-top: 4px;
					\">
						<button class=\"adminButton adminButtonEdit\" title=\"Edit Engagement Record\" style=\"
							margin-left: 0px;
							margin-right: 0px;
						\">
							<img height=\"20px\" class=\"adminButtonImage\" src=\"../images/misc/button_edit.png\">
						</button>
					</div>
				
				";
			}
			
			$displayKills .= "
					<div class=\"PayGradeDetails_Entry_Header\" style=\"
						vertical-align: middle;
						margin-top: 6px;
						display: table;
						padding-bottom: 4px;
					\">
						<div class=\"player_qual_row_name\" style=\"
							margin-top:8px;
							margin-bottom:8px;
							padding-left:8px;
							display: table-cell;
							vertical-align: middle;
						\">
							<strong>$KillType Kill - $IsSoloKill</strong>
							<br />
						</div>
					</div>
					<div class=\"shipyard_mainTable_row_content\" style=\"
						padding-top:0px;
						width: 100%;
					\">
						<div class=\"qual_reqs\" style=\"
							padding: 4px;
							font-size: 9pt;
							font-style: italic;
						\">
							<div class=\"qual_reqs_entry\">
								<p style=\"
									font-size: 9pt;
									font-style: italic;
									color: #DDD;
									margin-bottom: 0;
								\">
									Time of Engagement: <strong>$TimeOfEngagement</strong>
									<br />
									Ship Used: <strong>$PlayerShipName</strong>
									<br />
									Target Player: <strong><a href=\"http://www.robertsspaceindustries.com/citizens/$TargetPlayer\" target=\"_blank\">$TargetPlayer</a></strong>
									<br />
									Target Ship: <strong>$TargetShipName</strong>
									<br />
									Confirmed By: <strong><a href=\"../player/$ConfirmingMemberID\">$ConfirmingMemberName</a></strong>
									<br />
									<strong>Notes</strong>
									<br />
									<span class=\"KillNotes\">$KillNotes</span>
								</p>
							</div>
						</div>
					</div>
				</div>
			";
		}
		
		/*SHIPS QUERY FOR DROPDOWN MENU*/
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
		
			$displayShipsForPlayer .= "
				<option value=\"$ShipID\" id=\"ShipID-$ShipID\">
					$ManuName - $ShipName
				</option>
			";
			
			$displayShipsForEnemy .= "
				<option value=\"$ShipID\" id=\"TargetShipID-$ShipID\">
					$ManuName - $ShipName
				</option>
			";
		}
		/*END SHIPS QUERY*/
		
		/*OFFICERS QUERY FOR DROPDOWN MENU*/
		$officers_query = "
			select
				m.mem_id as ID
				,m.mem_callsign as Callsign
				,r.rank_level as PayGrade
				,r.rank_abbr
			from projectx_vvarsc2.members m
			join projectx_vvarsc2.ranks r
				on r.rank_id = m.ranks_rank_id
				and r.rank_groupName in ('Flag Officer','Senior Officer','Officer','Officer Candidate','Warrant Officer')
			where m.mem_sc = 1
				and m.mem_name not like '%guest%'
			order by
				r.rank_orderby
				,m.mem_callsign
		";
		
		$officers_query_results = $connection->query($officers_query);
		$displayOfficers = "";
		
		while(($row = $officers_query_results->fetch_assoc()) != false)
		{
			$ID = $row['ID'];
			$Callsign = $row['Callsign'];
			$PayGrade = $row['PayGrade'];
			$Rank_Abbr = $row['rank_abbr'];
		
			$displayOfficersSelectors .= "
				<option value=\"$ID\" id=\"ConfirmedBy-$ID\">
					[$PayGrade] - $Rank_Abbr $Callsign
				</option>
			";
		}
		/*END OFFICERS QUERY*/
		
		$display_addKill_button = "";
		if ($_SESSION['sess_userrole'] == "admin" || $_SESSION['sess_userrole'] == "officer")
		{
			$display_addKill_button .= "
				<button id=\"addMemberKill\" class=\"adminButton adminButtonCreate\" title=\"Add Engagement Record\" style=\"
					float: right;
					margin-left: 0px;
					margin-right: 2%;
				\" >
					<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
					Add Engagement Record
				</button>
				<br />
			";
		}		
	}
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<br />
<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="../../player/<? echo $player_id;?>">&#8672; Back to Member Details</a>
	</div>
</div>
<h2 id="MainPageHeaderText">Confirmed Kills - <? echo $mem_Callsign; ?></h2>
<div id="TEXT">
	<? echo $display_addKill_button; ?>
	<br />
	<div id="adminManuTableContainer" class="adminTableContainer">
		<div id="adminawardsTable" class="unit_description_container">
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
				<? echo $displayKills ?>
			</div>
		</div>
	</div>
	
	<!--Add Combat Engagement Form-->
	<div id="dialog-form-add-combat-engagement" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Add new Combat Engagement - <?echo $mem_Callsign;?></p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/function_combatEngagement_Create.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					
					<label for="MemberID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="MemberID" id="MemberID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
					<label for="TargetPlayer" class="adminDialogInputLabel">
						Target Player (RSI Callsign)
					</label>
					<input type="text" name="TargetPlayer" id="TargetPlayer" value="" class="adminDialogTextInput">
					
					<label for="TimeOfEngagement" class="adminDialogInputLabel">
						Time of Engagement (UTC, Format: YYYY-MM-DD HH:MM:SS)
					</label>
					<input type="text" name="TimeOfEngagement" id="TimeOfEngagement" value="" class="adminDialogTextInput">
					
					<label for="IsSoloKill" class="adminDialogInputLabel">
						Solo or Group Kill
					</label>
					<select name="IsSoloKill" id="IsSoloKill" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="IsSoloKill-default">
							- Select an Option -
						</option>
						<option value="1" id="IsSoloKill-Solo">
							Solo Kill
						</option>
						<option value="0" id="IsSoloKill-Shared">
							Shared Group Kill
						</option>
					</select>
					
					<label for="KillType" class="adminDialogInputLabel">
						Engagement Type
					</label>
					<select name="KillType" id="KillType" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="KillType-default">
							- Select an Engagement Type -
						</option>
						<option value="Aerial" id="KillType-Aerial">
							Air-to-Air
						</option>
						<option value="Infantry" id="KillType-Infantry">
							Infantry
						</option>
					</select>
					
					<label for="ShipIDLabel" id="ShipIDLabel" class="adminDialogInputLabel">
						Ship used by Player
					</label>
					<select name="ShipID" id="ShipID" class="adminDialogDropDown">
						<option selected disabled value="default" id="ShipID-default">
							- Select a Ship -
						</option>	
						<? echo $displayShipsForPlayer ?>
					</select>
					
					<label for="TargetPlayerShipLabel" id="TargetPlayerShipLabel" class="adminDialogInputLabel">
						Ship used by Enemy
					</label>
					<select name="TargetPlayerShip" id="TargetPlayerShip" class="adminDialogDropDown">
						<option selected disabled value="default" id="ShipID-default">
							- Select a Ship -
						</option>	
						<? echo $displayShipsForEnemy ?>
					</select>
					
					<label for="ConfirmedBy" class="adminDialogInputLabel">
						Confirmed By
					</label>
					<select name="ConfirmedBy" id="ConfirmedBy" class="adminDialogDropDown">
						<option selected disabled value="default" id="ConfirmedBy-default">
							- Select an Officer -
						</option>	
						<? echo $displayOfficersSelectors ?>
					</select>
					
					<label for="KillNotesLabel" class="adminDialogInputLabel">
						Notes
					</label>
					<textarea name="KillNotes" id="KillNotes" value="" class="adminDialogTextInput"></textarea>
					
				</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
	</div>

	<!--Edit Combat Engagement Form-->
	<div id="dialog-form-edit-combat-engagement" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Edit Combat Engagement - <?echo $mem_Callsign;?></p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/function_combatEngagement_Edit.php" method="POST" role="form">
				<fieldset class="adminDiaglogFormFieldset">
					
					<label for="RowID" class="adminDialogInputLabel">
						Record ID
					</label>
					<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" readonly>					
					
					<label for="MemberID" class="adminDialogInputLabel" style="display: none">
					</label>
					<input type="none" name="MemberID" id="MemberID" value="" class="adminDialogTextInput" style="display: none" readonly>
					
					<label for="TargetPlayer" class="adminDialogInputLabel">
						Target Player (RSI Callsign)
					</label>
					<input type="text" name="TargetPlayer" id="TargetPlayer" value="" class="adminDialogTextInput">
					
					<label for="TimeOfEngagement" class="adminDialogInputLabel">
						Time of Engagement (UTC, Format: YYYY-MM-DD HH:MM:SS)
					</label>
					<input type="text" name="TimeOfEngagement" id="TimeOfEngagement" value="" class="adminDialogTextInput">
					
					<label for="IsSoloKill" class="adminDialogInputLabel">
						Solo or Group Kill
					</label>
					<select name="IsSoloKill" id="IsSoloKill" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="IsSoloKill-default">
							- Select an Option -
						</option>
						<option value="1" id="IsSoloKill-Solo">
							Solo Kill
						</option>
						<option value="0" id="IsSoloKill-Shared">
							Shared Group Kill
						</option>
					</select>
					
					<label for="KillType" class="adminDialogInputLabel">
						Engagement Type
					</label>
					<select name="KillType" id="KillType" class="adminDialogDropDown">
						<option selected="true" disabled="true" value="default" id="KillType-default">
							- Select an Engagement Type -
						</option>
						<option value="Aerial" id="KillType-Aerial">
							Air-to-Air
						</option>
						<option value="Infantry" id="KillType-Infantry">
							Infantry
						</option>
					</select>
					
					<label for="ShipIDLabel" id="ShipIDLabel" class="adminDialogInputLabel">
						Ship used by Player
					</label>
					<select name="ShipID" id="ShipID" class="adminDialogDropDown">
						<option selected disabled value="default" id="ShipID-default">
							- Select a Ship -
						</option>	
						<? echo $displayShipsForPlayer ?>
					</select>
					
					<label for="TargetPlayerShipLabel" id="TargetPlayerShipLabel" class="adminDialogInputLabel">
						Ship used by Enemy
					</label>
					<select name="TargetPlayerShip" id="TargetPlayerShip" class="adminDialogDropDown">
						<option selected disabled value="default" id="ShipID-default">
							- Select a Ship -
						</option>	
						<? echo $displayShipsForEnemy ?>
					</select>
					
					<label for="ConfirmedBy" class="adminDialogInputLabel">
						Confirmed By
					</label>
					<select name="ConfirmedBy" id="ConfirmedBy" class="adminDialogDropDown">
						<option selected disabled value="default" id="ConfirmedBy-default">
							- Select an Officer -
						</option>	
						<? echo $displayOfficersSelectors ?>
					</select>
					
					<label for="KillNotesLabel" class="adminDialogInputLabel">
						Notes
					</label>
					<textarea name="KillNotes" id="KillNotes" value="" class="adminDialogTextInput"></textarea>
				</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
	</div>	
</div>
  
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.jScale.js"></script>
<script type="text/javascript" src="/js/sha512.js"></script>

<!--Form Controls-->
<script>
	$(function() {

		var overlay = $('#overlay');
		
		//Add Combat Engagement
		$('#addMemberKill').click(function() {
			var dialog = $('#dialog-form-add-combat-engagement');
			var $self = jQuery(this);
			
			var memID = "<? echo $player_id ?>";
			
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#TargetPlayer').val("").text();
			dialog.find('#KillType').find('#KillType-default').prop('selected',true);
			dialog.find('#ShipID').find('#ShipID-default').prop('selected',true);
			dialog.find('#TargetPlayerShip').find('#ShipID-default').prop('selected',true);
			dialog.find('#ConfirmedBy').find('#ConfirmedBy-default').prop('selected',true);
			dialog.find('#IsSoloKill').find('#IsSoloKill-default').prop('selected',true);
			
			
			dialog.show();
			overlay.show();
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('.div_filters_container').css({
				filter: 'blur(2px)'
			});
			$('#adminManuTableContainer').css({
				filter: 'blur(2px)'
			});
			
			dialog.find('#KillType').change(function() {
				var id = $(this).val();
				
				var playerShipIDSelector = dialog.find('#ShipID');
				var playerShipIDSelectorLabel = dialog.find('#ShipIDLabel');
				var targetShipIDSelector = dialog.find('#TargetPlayerShip');
				var targetShipIDSelectorLabel = dialog.find('#TargetPlayerShipLabel');
				
				playerShipIDSelector.hide();
				playerShipIDSelectorLabel.hide();
				targetShipIDSelector.hide();
				targetShipIDSelectorLabel.hide();
			
				//Aerial
				if (id == "Aerial")
				{
					playerShipIDSelector.show();
					playerShipIDSelectorLabel.show();
					targetShipIDSelector.show();
					targetShipIDSelectorLabel.show();
				}
				//Infantry
				else
				{

				}
			});
			dialog.find('#KillType').trigger('change');
		});
		
		//Edit Combat Engagement
		$('.adminButton.adminButtonEdit').click(function() {
			var dialog = $('#dialog-form-edit-combat-engagement');
			var $self = jQuery(this);
			
			var rowID = $self.parent().parent().data("id");
			var memID = "<? echo $player_id ?>";
			var targetPlayer = $self.parent().parent().data("targetplayer");
			var killType = $self.parent().parent().data("killtype");
			var shipID = $self.parent().parent().data("playershipid");
			var targetShipID = $self.parent().parent().data("targetshipid");
			var time = $self.parent().parent().data("time");
			var confirmingMember = $self.parent().parent().data("confirmingmember");
			var isSoloKill = $self.parent().parent().data("issolokill");
			var killNotes = $self.parent().parent().find('.KillNotes').text();
			
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#TargetPlayer').val(targetPlayer).text();
			dialog.find('#KillType').find('#KillType-' + killType).prop('selected',true);
			dialog.find('#ShipID').find('#ShipID-' + shipID).prop('selected',true);
			dialog.find('#TargetPlayerShip').find('#TargetShipID-' + targetShipID).prop('selected',true);
			dialog.find('#TimeOfEngagement').val(time).text();
			dialog.find('#ConfirmedBy').find('#ConfirmedBy-' + confirmingMember).prop('selected',true);
			dialog.find('#IsSoloKill').find('#IsSoloKill-' + isSoloKill).prop('selected',true);
			dialog.find('#KillNotes').val(killNotes).text();
			
			
			dialog.show();
			overlay.show();
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('.div_filters_container').css({
				filter: 'blur(2px)'
			});
			$('#adminManuTableContainer').css({
				filter: 'blur(2px)'
			});
			
			dialog.find('#KillType').change(function() {
				var id = $(this).val();
				
				var playerShipIDSelector = dialog.find('#ShipID');
				var playerShipIDSelectorLabel = dialog.find('#ShipIDLabel');
				var targetShipIDSelector = dialog.find('#TargetPlayerShip');
				var targetShipIDSelectorLabel = dialog.find('#TargetPlayerShipLabel');
				
				playerShipIDSelector.hide();
				playerShipIDSelectorLabel.hide();
				targetShipIDSelector.hide();
				targetShipIDSelectorLabel.hide();
			
				//Aerial
				if (id == "Aerial")
				{
					playerShipIDSelector.show();
					playerShipIDSelectorLabel.show();
					targetShipIDSelector.show();
					targetShipIDSelectorLabel.show();
				}
				//Infantry
				else
				{

				}
			});
			dialog.find('#KillType').trigger('change');
		});		
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('#MemberID').val("").text();
			$('.adminDiaglogFormFieldset').find('#TargetPlayer').val("").text();
			$('.adminDiaglogFormFieldset').find('#TimeOfEngagement').val("").text();
			$('.adminDiaglogFormFieldset').find('#KillNotes').val("").text();
			$('.adminDiaglogFormFieldset').find('select').find('option').prop('selected',false);
			
			//Hide All Dialog Containers
			$('#dialog-form-add-combat-engagement').hide();
			$('#dialog-form-edit-combat-engagement').hide();
			
			overlay.hide();
			$('#adminManuTableContainer').css({
				filter: 'none'
			});
			$('.div_filters_container').css({
				filter: 'none'
			});
			$('#MainPageHeaderText').css({
				filter: 'none'
			});
		});	
	});
</script>