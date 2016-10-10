<?php include_once('functions/function_auth_user.php'); ?>

<?php 
	$player_id = strip_tags(isset($_GET[pid]) ? $_GET[pid] : '');
	
	$display_player_ships;
	$display_player_qualifications;
	$ship_count;
	
	$loggedInPlayerID = $_SESSION['sess_user_id'];
	$loggedInPlayerName = $_SESSION['sess_username'];
    
    if(is_numeric($player_id)) {
    	$play_query = "SELECT
			members.mem_id
			,members.mem_id as player_id
    	    ,members.mem_name
    		,members.mem_callsign
    		,members.mem_avatar_link
			,members.membership_type
			,TRIM(Replace(members.member_bio,'\t','')) as member_bio
			,DATE_FORMAT(DATE(members.CreatedOn),'%d %b %Y') as MemberCreatedOn
			,ranks.rank_id
    		,ranks.rank_groupName
    		,ranks.rank_image
			,ranks.rank_level
			,ranks.rank_name
			,DATE_FORMAT(DATE(members.RankModifiedOn),'%d %b %Y') as RankModifiedOn
			,d.div_id
    		,d.div_name
			,case
				when roles.isPrivate = 0 and roles.role_displayName != '' then roles.role_displayName
				when roles.isPrivate = 0 then roles.role_name
				when roles.role_id is null then 'n/a'
				else '- Role Information Classified - '
			end as role_name
    		,manufacturers.manu_shortName
			,specialties.spec_name
    		,ships.ship_name
			,ships.ship_model_designation
			,ships.ship_model_visible
    		,ships.ship_link
			,ships.ship_silo
			,ships.ship_id
			,ships.ship_price
    		,shm.shm_lti
			,shm.shm_package
			,u.UnitID
			,case
				when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
				else u.UnitFullName
			end as UnitName
    		,DATE_FORMAT(DATE(shm.CreatedOn),'%d %b %Y') as CreatedOn
    		,DATE_FORMAT(DATE(shm.ModifiedOn),'%d %b %Y') as ModifiedOn
			,shm.rowID AS ship_vvarID
        FROM projectx_vvarsc2.members
    		LEFT JOIN projectx_vvarsc2.ships_has_members shm
    			ON members.mem_id=shm.members_mem_id
    		LEFT JOIN projectx_vvarsc2.ships
    			ON shm.ships_ship_id = ships.ship_id
    		LEFT JOIN projectx_vvarsc2.manufacturers
    			ON ships.manufacturers_manu_id = manufacturers.manu_id
    		JOIN projectx_vvarsc2.ranks
    			ON members.ranks_rank_id = ranks.rank_id
    		JOIN projectx_vvarsc2.divisions d
    			ON members.divisions_div_id = d.div_id
			LEFT JOIN projectx_vvarsc2.specialties
				ON specialties.spec_id = members.specialties_spec_id
			LEFT JOIN projectx_vvarsc2.UnitMembers um
				on um.MemberID = members.mem_id
			LEFT JOIN projectx_vvarsc2.Units u
				on u.UnitID = um.UnitID
    		left JOIN projectx_vvarsc2.roles
    			ON um.MemberRoleID = roles.role_id
        WHERE members.mem_sc = 1 AND members.mem_id = $player_id
        ORDER BY manufacturers.manu_name,ships.ship_name";
    	
        $play_query_results = $connection->query($play_query);
		
		$total_ship_value = 0;
    	
    	while(($row = $play_query_results->fetch_assoc()) != false) {
    	    $mem_id = $row['mem_id'];
    	    $mem_name = $row['mem_name'];
    		$mem_callsign = $row['mem_callsign'];
    		$mem_gint = $row['mem_gint'];
    		$mem_avatar_link = $row['mem_avatar_link'];
    		$mem_createdOn = $row['MemberCreatedOn'];
			$rank_id = $row['rank_id'];
    		$rank_groupName = $row['rank_groupName'];
    		$rank_image = $row['rank_image'];
			$rank_level = $row['rank_level'];
			$rank_name = $row['rank_name'];
			$rankModifiedOn = $row['RankModifiedOn'];
    		$div_id = $row['div_id'];
    		$div_name = $row['div_name'];
    		$role_name = $row['role_name'];
			$spec_name = $row['spec_name'];
    		$manu_name = $row['manu_shortName'];
    		$ship_name = $row['ship_name'];
    		$ship_link = $row['ship_link'];
			$ship_silo = $row['ship_silo'];
			$ship_cost = $row['ship_price'];
			$ship_id = $row['ship_id'];
    		$shm_lti = $row['shm_lti'];
    		$shm_package = $row['shm_package'];
			$shm_createdOn = $row['CreatedOn'];
			$shm_modifiedOn = $row['ModifiedOn'];
			$shm_ship_vvarID = $row['ship_vvarID'];
			$temp_player_id = $row['player_id'];
			$ship_model_designation = $row['ship_model_designation'];
			$ship_model_visible = $row['ship_model_visible'];
			$UnitID = $row['UnitID'];
			$UnitName = $row['UnitName'];
			$MemberBio = $row['member_bio'];
			$MembershipType = $row['membership_type'];
			$displayMembershipType = "";
			
			if ($MembershipType == 1)
			{
				$displayMembershipType = 'Main';
			}
			else
			{
				$displayMembershipType = 'Affiliate';
			}
			
			if ($ship_model_designation != NULL && $ship_model_visible != "0") {
				$full_ship_name = "";
				$full_ship_name .= $ship_model_designation;
				$full_ship_name .= " \n";
				$full_ship_name .= $ship_name;
			}
			else
			{
				$full_ship_name = $ship_name;
			}			
    		
			if ($spec_name == NULL) {
				$spec_name = "- No Speciality Selected -";
			}
			
			if ($role_name == NULL || $role_name == "n/a") {
				$full_role_name = "- No Assigned Role -";
			}
			else
			{
				if ($UnitName != NULL)
				{
					$role_name .= " - ";
					$role_name .= $UnitName;
					$full_role_name = "<a href=\"http://sc.vvarmachine.com/unit/$UnitID\"> $role_name </a>";
				}
				else
				{
					$full_role_name = $role_name;
				}
			}
			
			if ($div_name == NULL || $div_name == "n/a") {
				$div_name = "- No Division Assigned -";
			}
			else
			{
				$div_name .= " Division";
			}
			
			$DisplayMemberBio = "";
			if ($MemberBio == NULL || $MemberBio == "")
			{
				$DisplayMemberBio = "- No Biography Information Found -";
			}
			else
			{
				$DisplayMemberBio = $MemberBio;
			}
			
    		if ($shm_lti == 1) {
    			$shm_lti_display = "Yes";
    		} else {
    			$shm_lti_display = "No";
    		}
    		
    		if ($shm_package == 1) {
    			$shm_package_display = "Yes";
    		} else {
    			$shm_package_display = "No";
    		}
			
			$total_ship_value += $ship_cost;
    		
    		if($manu_name == NULL & $ship_name == NULL) {
    		    $display_player_ships = "<tr><td><em>- No Ships Currently Registered -</em></td></tr>";
    		    $ship_count = 0;
    		} else {		
					
          		$display_player_ships .= "
				<tr class=\"player_ships_row\">
					<td class=\"player_ships_entry\"
						data-rowid=\"$shm_ship_vvarID\"
						data-shipid=\"$ship_id\" 
						data-manuname=\"$manu_name\" 
						data-shipname=\"$ship_name\" 
						data-package=\"$shm_package\"
						data-lti=\"$shm_lti\" >
					
						<div class=\"player_ships_shipTitle\">
							<a href=\"http://sc.vvarmachine.com/ship/$ship_id\" >
								<div class=\"player_ships_shipTitleText\">
									$manu_name $full_ship_name
								</div>
							</a>	
						</div>					
						<div class=\"player_ships_entry_details\">
							<div class=\"shipTable2_Container\">
								<div class=\"corner2 corner-top-left\">
								</div>
								<div class=\"corner2 corner-top-right\">
								</div>
								<div class=\"corner2 corner-bottom-left\">
								</div>
								<div class=\"corner2 corner-bottom-right\">
								</div>
								<table class=\"tooltip_shipTable2\">
									<tr>
										<td class=\"tooltip_shipTable2_key\">
											<div class=\"tooltip_shipTable2_key_inner\">
											ShipID
											</div>
										</td>
										<td class=\"tooltip_shipTable2_value\">
											<div class=\"tooltip_shipTable2_value_inner\">
											$shm_ship_vvarID
											</div>
										</td>
									</tr>									
									<tr>
										<td class=\"tooltip_shipTable2_key\">
											<div class=\"tooltip_shipTable2_key_inner\">
											Date Added
											</div>
										</td>
										<td class=\"tooltip_shipTable2_value\">
											<div class=\"tooltip_shipTable2_value_inner\">
											$shm_createdOn
											</div>
										</td>
									</tr>									
									<tr>
										<td class=\"tooltip_shipTable2_key\">
											<div class=\"tooltip_shipTable2_key_inner\">
											Last Modified
											</div>
										</td>
										<td class=\"tooltip_shipTable2_value\">
											<div class=\"tooltip_shipTable2_value_inner\">
											$shm_modifiedOn
											</div>
										</td>
									</tr>
									<tr>
										<td class=\"tooltip_shipTable2_key\">
											<div class=\"tooltip_shipTable2_key_inner\">
											Package
											</div>
										</td>
										<td class=\"tooltip_shipTable2_value\">
											<div class=\"tooltip_shipTable2_value_inner\">
											$shm_package_display
											</div>
										</td>
									</tr>
									<tr>
										<td class=\"tooltip_shipTable2_key\">
											<div class=\"tooltip_shipTable2_key_inner\">
											LTI
											</div>
										</td>
										<td class=\"tooltip_shipTable2_value\">
											<div class=\"tooltip_shipTable2_value_inner\">
											$shm_lti_display
											</div>
										</td>
									</tr>
									<!--
									<tr>
										<td class=\"tooltip_shipTable2_key\">
											<div class=\"tooltip_shipTable2_key_inner\">
											Ship Value
											</div>
										</td>
										<td class=\"tooltip_shipTable2_value\">
											<div class=\"tooltip_shipTable2_value_inner\">
											$$ship_cost
											</div>
										</td>
									</tr>
									-->
								</table>
							</div>
						</div>
					";
					if ($loggedInPlayerID == $player_id && $loggedInPlayerName != "guest")
					{
						$display_player_ships .= "
							<div class=\"player_ships_entry_buttons_buttonContainer\">
								<button class=\"adminButton adminButtonEdit playerEditShip\" style=\"margin-left:4px\" title=\"Edit Ship\">
									<img height=\"20px\" class=\"adminButtonImage\" src=\"http://sc.vvarmachine.com/images/misc/button_edit.png\">
								</button>
								<button class=\"adminButton adminButtonDelete playerDeleteShip\" style=\"margin-left:4px\" title=\"Delete Ship\">
									<img height=\"20px\" class=\"adminButtonImage\" src=\"http://sc.vvarmachine.com/images/misc/button_delete.png\">
								</button>
							</div>
						";
					}
					$display_player_ships .= "
					</td>

					<td class=\"player_ships_entry_ship\">
						<div class=\"player_ships_entry_ship_inner\">
							<div class=\"player_ships_entry_ship_inner_imageContainer\">
								<a href=\"http://sc.vvarmachine.com/ship/$ship_id\" >
									<img class=\"player_fleet\" align=\"center\" src=\"http://sc.vvarmachine.com/images/silo_topDown/$ship_silo\" />
								</a>
							</div>
						</div>
						<div class=\"playerShips_table_header_block2\">
						</div>
					</td>
				</tr>";      		
        		  $ship_count++;
    		}
    	}
		
		$qualification_query = "
		select
			q.qualification_name
			,q.qualification_image
			,IFNULL(mq.qualification_level_id,0) as `qualification_level`
			,IFNULL(ql.qualification_level_name,'-') as `qualification_level_name`
			,d.div_name
		from projectx_vvarsc2.qualifications q
		join projectx_vvarsc2.divisions d
			on d.div_id = q.divisions_div_id
		left join projectx_vvarsc2.member_qualifications mq
			on mq.qualification_id = q.qualification_id
			and mq.member_id = $player_id
		left join projectx_vvarsc2.qualification_levels ql
			on ql.qualification_level_id = mq.qualification_level_id
			and ql.qualification_id = q.qualification_id
		order by
			q.qualification_name
		";
		
		$qualification_query_results = $connection->query($qualification_query);
		
		while(($row2 = $qualification_query_results->fetch_assoc()) != false)
		{
			$qual_name = $row2['qualification_name'];
			$qual_image = $row2['qualification_image'];
			$qual_level_id = $row2['qualification_level'];
			$qual_level_name = $row2['qualification_level_name'];
			$qual_division = $row2['div_name'];
			
			$imageClassName1 = "player_qual_row_image";
			$imageClassName2 = "player_qual_row_image";
			$imageClassName3 = "player_qual_row_image";
			
			if ($qual_level_id == 1) {
				$imageClassName1 = "player_qual_row_image_highlighted";
				$imageClassName2 = "player_qual_row_image";
				$imageClassName3 = "player_qual_row_image";
			}
			else if ($qual_level_id == 2) {
				$imageClassName1 = "player_qual_row_image_highlighted";
				$imageClassName2 = "player_qual_row_image_highlighted";
				$imageClassName3 = "player_qual_row_image";
			}
			else if ($qual_level_id == 3) {
				$imageClassName1 = "player_qual_row_image_highlighted";
				$imageClassName2 = "player_qual_row_image_highlighted";
				$imageClassName3 = "player_qual_row_image_highlighted";
			}
			
			$display_player_qualifications .="
				<tr class=\"player_qual_row\">
					<td class=\"player_qual_row_name\">
						$qual_name
					</td>
					<td class=\"player_qual_row_image_container\">
						<img class=\"$imageClassName1\" src=\"$qual_image\">
					</td>
					<td class=\"player_qual_row_image_container\">
						<img class=\"$imageClassName2\" src=\"$qual_image\">
					</td>
					<td class=\"player_qual_row_image_container\">
						<img class=\"$imageClassName3\" src=\"$qual_image\">
					</td>
					<td class=\"player_qual_row_division\">
						$qual_division
					</td>
				</tr>
			";
		}
		
		$display_edit_options_profile = "";
		$display_edit_options_ships = "";
		if ($loggedInPlayerID == $player_id && $loggedInPlayerName != "guest")
		{
			$display_edit_options_profile .= "
				<button id=\"playerEditProfile\" class=\"adminButton adminButtonEdit\" title=\"Edit Profile\" style=\"
					float: right;
					margin-left: 0px;
					margin-right: 2%;
				\" >
					<img height=\"20px\" class=\"adminButtonImage\" src=\"http://sc.vvarmachine.com/images/misc/button_edit.png\">
					Edit Profile
				</button>
				<br />
				<br />
			";
			$display_edit_options_ships .= "
				<button id=\"playerAddShip\" class=\"adminButton adminButtonCreate\" title=\"Add Ship\" style=\"
					float: right;
					margin-left: 0px;
					margin-right: 2%;
				\" >
					<img height=\"20px\" class=\"adminButtonImage\" src=\"http://sc.vvarmachine.com/images/misc/button_add.png\">
					Add Ship
				</button>
				<br />
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
		
			$displayShips .= "
				<option value=\"$ShipID\" id=\"$ShipID\">
					$ManuName - $ShipName
				</option>
			";
		}
		/*END SHIPS QUERY*/
		
    	$connection->close();
    } else {
        header("Location: http://sc.vvarmachine.com/?page=members");
    }
?>

<h2>Star Citizen Player Profile</h2>
<div id="TEXT">
	<div class="player_topTable_Container">
		<? echo $display_edit_options_profile ?>
		<div class="table_header_block">
		</div>
		<div class="play">
			<div class="pavatar" height="200" width="200">		
				<img height="200" width="200" alt="<? echo $mem_name; ?>" src="http://sc.vvarmachine.com/images/player_avatars/<? echo $mem_avatar_link; ?>.png" />
			</div>
			<div class="p_info" valign="top" align="left">
				<div class="p_section_header">
					Citizen Dossier - <? echo $mem_callsign; ?>
				</div>
				<div class="p_rank" align="left" valign="top">
					<div class = "p_rankimage_container">
						<img class = "p_rankimage" align="left" alt="<? echo $rank_groupName; ?>" src="http://sc.vvarmachine.com/images/ranks/<? echo $rank_image; ?>.png" />
					</div>
					<div class = "p_rankDetails">
						<div class ="p_rankname">
							<? echo $rank_groupName; ?>
						</div>
						<div class ="p_rank_role_name">
							<? echo $full_role_name; ?>
						</div>
					</div>
				</div>
				<div class = "p_rankExtendedData">
					<span class="p_rankExtendedData_rank_level">
						<? echo $rank_level; ?> &nbsp;
					</span>
					<span class="p_rankExtendedData_rank_name">
						<? echo $rank_name; ?>
					</span>
					<br />
					<span class="p_rankExtendedData_rank_date">
						Grade Assigned: <? echo $rankModifiedOn; ?>
					</span>
				</div>
				<div class="p_details_container">
					<table class="p_details">
						<tr>
							<td class="members_detailsTable_key">
								<div class="members_detailsTable_key_inner">
								Player ID
								</div>
							</td>
							<td class="members_detailsTable_value">
								<div class="members_detailsTable_value_inner">
								<? echo $temp_player_id; ?>
								</div>
							</td>
						</tr>
						<tr>
							<td class="members_detailsTable_key">
								<div class="members_detailsTable_key_inner">
								VVAR Player Name
								</div>
							</td>
							<td class="members_detailsTable_value">
								<div class="members_detailsTable_value_inner">
								<? echo $mem_name; ?>
								</div>
							</td>
						</tr>
						<tr>
							<td class="members_detailsTable_key">
								<div class="members_detailsTable_key_inner">
								CallSign / RSI Handle
								</div>
							</td>
							<td class="members_detailsTable_value">
								<div class="members_detailsTable_value_inner">
								<a href="https://robertsspaceindustries.com/citizens/<? echo $mem_callsign; ?>" target="_blank"><? echo $mem_callsign; ?></a>
								</div>
							</td>
						</tr>
						<tr>
							<td class="members_detailsTable_key">
								<div class="members_detailsTable_key_inner">
								Enlisted
								</div>
							</td>
							<td class="members_detailsTable_value">
								<div class="members_detailsTable_value_inner">
								<? echo $mem_createdOn; ?>
								</div>
							</td>
						</tr>
						<tr>
							<td class="members_detailsTable_key">
								<div class="members_detailsTable_key_inner">
								Membership Type
								</div>
							</td>
							<td class="members_detailsTable_value">
								<div class="members_detailsTable_value_inner">
								<? echo $displayMembershipType; ?>
								</div>
							</td>
						</tr>
						<tr>
							<td class="members_detailsTable_key">
								<div class="members_detailsTable_key_inner">
								Ship Count
								</div>
							</td>
							<td class="members_detailsTable_value">
								<div class="members_detailsTable_value_inner">
								<? echo $ship_count; ?>
								</div>
							</td>
						</tr>
						<!--
						<tr>
							<td class="members_detailsTable_key">
								<div class="members_detailsTable_key_inner">
								Personal Fleet Value
								</div>
							</td>
							<td class="members_detailsTable_value">
								<div class="members_detailsTable_value_inner">
								$<? echo $total_ship_value; ?>
								</div>
							</td>
						</tr>
						-->
						<tr>
							<td class="members_detailsTable_key">
								<div class="members_detailsTable_key_inner">
								Speciality
								</div>
							</td>
							<td class="members_detailsTable_value">
								<div class="members_detailsTable_value_inner">
								<? echo $spec_name; ?>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="p_qual" valign="top" align="left">
				<div class="p_section_header">
					Player Qualifications (coming soon)
				</div>
				<table class="player_qualifications">
					<? echo $display_player_qualifications; ?>
				</table>
			</div>
		</div>
		
		<h4 style="padding-left: 0px; margin-left: 0px">
			Member Biography
		</h4>
		<div class="unit_description_container" style="margin-bottom: 16px">
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
			<? echo nl2br($DisplayMemberBio) ?>
		</div>
	</div>
	<!--PlayerShips-->
	<div class="player_shipsTable_Container">
		<? echo $display_edit_options_ships ?>
		<div class="p_section_header">
			Citizen Fleet Information
		</div>
		<div class="player_ships_container">
			<div class="top-line-yellow">
			</div>
			<table id="player_ships">
				<? echo $display_player_ships; ?>
			</table>
		</div>
	</div>
	
	<!--Edit Profile Form-->
	<div id="dialog-form-edit-profile" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update Member Information</p>
		<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/playerFunctions/function_mem_EditProfile.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="ID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="Name" class="adminDialogInputLabel">
					Name
				</label>
				<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required autofocus readonly>
				
				<label for="Callsign" class="adminDialogInputLabel">
					Callsign (RSI Handle)
				</label>
				<input type="text" name="Callsign" id="Callsign" value="" class="adminDialogTextInput" required readonly>
				
				<label for="Biography" class="adminDialogInputLabel">
					Biography Info
				</label>
				<textarea name="Biography" id="Biography" class="adminDialogTextArea"><? echo $MemberBio ?>
				</textarea>			
				
				<label for="CurrentPassword" class="adminDialogInputLabel">
					Current Password (required to make updates)
				</label>
				<input type="password" name="CurrentPassword" id="CurrentPassword" value="" class="adminDialogTextInput" required>
				
				<label for="NewPassword" class="adminDialogInputLabel">
					New Password (leaving this field empty means it won't be changed)
				</label>
				<input type="password" name="NewPassword" id="NewPassword" value="" class="adminDialogTextInput">
			</fieldset>
				<div class="adminDialogButtonPane">
					<input type="button" 
							value="Submit"
							class="adminDialogButton dialogButtonSubmit"
							onclick="formhash(this.form, this.form.CurrentPassword, this.form.NewPassword);" />
				</div>	
		</form>
	</div>	

	<!--Add Ship Form-->
	<div id="dialog-form-add-ship" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Add a new Ship to your Fleet!</p>
		<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/playerFunctions/function_playerShip_Create.php" method="POST" role="form">
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
	
	<!--Edit Ship Form-->
	<div id="dialog-form-edit-ship" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update PlayerShip Entry</p>
		<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/playerFunctions/function_playerShip_Edit.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel">
					RowID
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" readonly>
				
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
	
	<!--Remove Ship Form-->
	<div id="dialog-form-remove-ship" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Confirmation Required!</p>
		<p class="validateTips">Are you sure you want to Remove this Ship from your Fleet?</p>
		<form class="adminDialogForm" action="http://sc.vvarmachine.com/functions/playerFunctions/function_playerShip_Delete.php" method="POST" role="form">
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
					Submit
				</button>
			</div>
		</form>
	</div>
	
</div>
  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.jScale.js"></script>
<script type="text/javascript" src="/js/sha512.js"></script>

<!--Script to Resize Fleet Images-->
<script>

	$(document).ready(function() {
	
		var imageClass = $('.player_fleet');
		
		if(($( window ).width() < 800)) {
			imageClass.jScale({w: '20%'});
			imageClass.css({
					"margin": '0px'
				});
		}	
		else if(($( window ).width() < 1200)){
			imageClass.jScale({w: '40%'});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '60%'});
			imageClass.css({
					"margin": '2px'
				});		
		}
	});
	
	$(window).resize(function () {
	
		var imageClass = $('.player_fleet');
		
		if(($( window ).width() < 800)) {
			imageClass.jScale({w: '20%'});
			imageClass.css({
					"margin": '0px'
				});
			
		}	
		else if(($( window ).width() < 1200)){
			imageClass.jScale({w: '40%'});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '60%'});
			imageClass.css({
					"margin": '2px'
				});		
		}
	});	

</script>


<!-- Script for changing background image-->
<!--
<script>
	$(document).ready(function() {
		$('body').css('background','url(http://vvarmachine.com/uploads/galleries/SC_background_pic_02.png) no-repeat fixed center center transparent');
	});
</script>
-->

<script>
	function resizeInput() {
		$(this).attr('size', $(this).val().length);
	}
	
	$(document).ready(function() {
		var dialog = $('#dialog-form');
		dialog.hide();
		
		//Set TextArea Input Height to Correct Values
		$("textarea").height( $("textarea")[0].scrollHeight );
		
		$('input[type="text"]')
		// event handler
		.keyup(resizeInput)
		// resize on page load
		.each(resizeInput);
	});
	
	$(function() {

		var overlay = $('#overlay');
		
		//Edit Profile
		$('#playerEditProfile').click(function() {
			var dialog = $('#dialog-form-edit-profile');
			
			var $self = jQuery(this);
			
			var memID = "<? echo $mem_id ?>";
			var memName = "<? echo $mem_name ?>";
			var memCallsign = "<? echo $mem_callsign ?>";
			
			dialog.find('#ID').val(memID).text();
			dialog.find('#Name').val(memName).text();
			dialog.find('#Callsign').val(memCallsign).text();
			dialog.find('#CurrentPassword').val("").text();
			dialog.find('#NewPassword').val("").text();
			
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});
		});
		
		//Add Ship
		$('#playerAddShip').click(function() {
			var dialog = $('#dialog-form-add-ship');
			var $self = jQuery(this);
			
			var memID = "<? echo $mem_id ?>";
			
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#ShipID').find('#ShipID-default').prop('selected',true);
			dialog.find('#Package').find('#Package-default').prop('selected',true);
			dialog.find('#LTI').find('#LTI-default').prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});
		});
		
		//Edit Ship
		$('.playerEditShip').click(function() {
			var dialog = $('#dialog-form-edit-ship');
			var $self = jQuery(this);
			
			var memID = "<? echo $mem_id ?>";
			var rowID = $self.parent().parent().parent().find('.player_ships_entry').data("rowid");
			var shipID = $self.parent().parent().parent().find('.player_ships_entry').data("shipid");
			var ispackage = $self.parent().parent().parent().find('.player_ships_entry').data("package");
			var islti = $self.parent().parent().parent().find('.player_ships_entry').data("lti");
			
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
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});
		});

		//Delete Ship
		$('.playerDeleteShip').click(function() {
			var dialog = $('#dialog-form-remove-ship');
			var $self = jQuery(this);
			
			var memID = "<? echo $mem_id ?>";
			var rowID = $self.parent().parent().parent().find('.player_ships_entry').data("rowid");
			var shipID = $self.parent().parent().parent().find('.player_ships_entry').data("shipid");
			var ispackage = $self.parent().parent().parent().find('.player_ships_entry').data("package");
			var islti = $self.parent().parent().parent().find('.player_ships_entry').data("lti");
			
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#ShipID').val(shipID).text();
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
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
			$('#dialog-form-edit-profile').hide();
			$('#dialog-form-add-ship').hide();
			$('#dialog-form-edit-ship').hide();
			$('#dialog-form-remove-ship').hide();
			
			overlay.hide();
			$('.player_topTable_Container').css({
				filter: 'none'
			});
			$('.player_shipsTable_Container').css({
				filter: 'none'
			});
		});	
	});
</script>


<script>
function formhash(form, currentPassword, newPassword){
    // Create a new element input, this will be our hashed password field. 
    var cp = document.createElement("input");
	var np = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(cp);
    cp.name = "cp";
    cp.type = "hidden";
    cp.value = hex_sha512(currentPassword.value);
	
	if (newPassword.value != "")
	{
		form.appendChild(np);
		np.name = "np";
		np.type = "hidden";
		np.value = hex_sha512(newPassword.value);
	}
 
    // Make sure the plaintext password doesn't get sent. 
    currentPassword.value = "";
    newPassword.value = "";
 
    // Finally submit the form. 
    form.submit();
}
</script>