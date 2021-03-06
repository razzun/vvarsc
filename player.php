<?php include_once('functions/function_auth_user.php'); ?>
<?php include_once('inc/OptionQueries/lk_qualificationCategories_query.php'); ?>

<?php 
	$player_id = strip_tags(isset($_GET['pid']) ? $_GET['pid'] : '');
	
	include_once('inc/OptionQueries/qualifications_query.php');
	include_once('inc/OptionQueries/awards_query.php');
	
	$display_player_ships;
	$display_player_qualifications;
	$ship_count;
	
	$loggedInPlayerID = $_SESSION['sess_user_id'];
	$loggedInPlayerName = $_SESSION['sess_username'];
    
    if(is_numeric($player_id)) {
		$player_query = "
		
		";
		
		
		$mem_title = "";
	
    	$playerShips_query = "SELECT
			members.mem_id
			,members.mem_id as player_id
    	    ,members.mem_name
    		,members.mem_callsign
			,members.mem_status
    		,members.mem_avatar_link
			,members.membership_type
			,TRIM(LEADING '\t' from members.member_bio) as member_bio
			,DATE_FORMAT(DATE(members.CreatedOn),'%d %b %Y') as MemberCreatedOn
			,ranks.rank_id
    		,ranks.rank_groupName
    		,ranks.rank_image
			,ranks.rank_level
			,ranks.rank_name
			,DATE_FORMAT(DATE(members.RankModifiedOn),'%d %b %Y') as RankModifiedOn
			,d.div_id
    		,d.div_name
    		,manufacturers.manu_shortName
    		,ships.ship_name
			,ships.ship_model_designation
			,ships.ship_model_visible
    		,ships.ship_link
			,ships.ship_silo
			,ships.ship_id
			,ships.ship_price
			,ships.ship_canBeNamed
    		,shm.shm_lti
			,shm.shm_package
    		,DATE_FORMAT(DATE(shm.CreatedOn),'%d %b %Y') as CreatedOn
    		,DATE_FORMAT(DATE(shm.ModifiedOn),'%d %b %Y') as ModifiedOn
			,shm.rowID AS ship_vvarID
			,shm.shm_assetName
			,ships.ship_asset_designationCode
			,(
				select
					CASE
						when rc.RatingCode is not null 
							and ra.rank_suffix is not null then CONCAT(rc.RatingCode, ra.rank_suffix, ' ', m2.mem_callsign) ##For Navy Enlisted, with Unit Override of RatingCode for Role
						when r2.rating_designation is not null 
							and ra.rank_suffix is not null then CONCAT(r2.rating_designation, ra.rank_suffix, ' ', m2.mem_callsign) ##For Navy Enlisted, without Override of RatingCode
						else CONCAT(ra.rank_abbr, ' ', m2.mem_callsign) ##For Officers, un-assigned members, and Enlisted Marines
					end
				from projectx_vvarsc2.members m2
				join projectx_vvarsc2.ranks ra
					on ra.rank_id = m2.ranks_rank_id
				left join projectx_vvarsc2.UnitMembers um
					on m2.mem_id = um.MemberID
				left join projectx_vvarsc2.UnitRoles ur
					on ur.UnitID = um.UnitID
					and ur.RoleID = um.MemberRoleID
				left join projectx_vvarsc2.roles r2
					on r2.role_id = um.MemberRoleID
				left join projectx_vvarsc2.RatingCodes rc
					on rc.RatingCode = ur.RatingCodeOverride
				where m2.mem_id = members.mem_id
				order by
					r2.role_orderby
			limit 1
			) as FullTitle
        FROM projectx_vvarsc2.members
		JOIN projectx_vvarsc2.ranks
			ON members.ranks_rank_id = ranks.rank_id
		LEFT JOIN projectx_vvarsc2.ships_has_members shm
			ON members.mem_id=shm.members_mem_id
		LEFT JOIN projectx_vvarsc2.ships
			ON shm.ships_ship_id = ships.ship_id
		LEFT JOIN projectx_vvarsc2.manufacturers
			ON ships.manufacturers_manu_id = manufacturers.manu_id
		left JOIN projectx_vvarsc2.divisions d
			ON members.divisions_div_id = d.div_id
        WHERE members.mem_sc = 1
			AND members.mem_id = $player_id
        ORDER BY manufacturers.manu_name,ships.ship_pname,ships.ship_name";
    	
        $playerShips_query_results = $connection->query($playerShips_query);
		
		$total_ship_value = 0;
		$display_player_ships = "";
		$ship_count = 0;
    	
    	while(($row = $playerShips_query_results->fetch_assoc()) != false) {
    	    $mem_id = $row['mem_id'];
    	    $mem_name = $row['mem_name'];
    		$mem_callsign = $row['mem_callsign'];
    		$mem_avatar_link = $row['mem_avatar_link'];
			$mem_status = $row['mem_status'];
    		$mem_createdOn = $row['MemberCreatedOn'];
			$rank_id = $row['rank_id'];
    		$rank_groupName = $row['rank_groupName'];
    		$rank_image = $row['rank_image'];
			$rank_level = $row['rank_level'];
			$rank_name = $row['rank_name'];
			$rankModifiedOn = $row['RankModifiedOn'];
    		$div_id = $row['div_id'];
    		$div_name = $row['div_name'];
    		$manu_name = $row['manu_shortName'];
    		$ship_name = $row['ship_name'];
    		$ship_link = $row['ship_link'];
			$ship_silo = $row['ship_silo'];
			$ship_cost = $row['ship_price'];
			$ship_id = $row['ship_id'];
			$ship_canBeNamed = $row['ship_canBeNamed'];
    		$shm_lti = $row['shm_lti'];
    		$shm_package = $row['shm_package'];
			$shm_createdOn = $row['CreatedOn'];
			$shm_modifiedOn = $row['ModifiedOn'];
			$shm_ship_vvarID = $row['ship_vvarID'];
			$ship_assetName = $row['shm_assetName'];
			$ship_assetDesignation = $row['ship_asset_designationCode'];
			$temp_player_id = $row['player_id'];
			$ship_model_designation = $row['ship_model_designation'];
			$ship_model_visible = $row['ship_model_visible'];
			$MemberBio = $row['member_bio'];
			$MembershipType = $row['membership_type'];
			$displayMembershipType = "";
			$full_title = $row['FullTitle'];
			
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
			
			$DisplayMemberBio = "";
			if ($MemberBio == NULL || $MemberBio == "")
			{
				$DisplayMemberBio = "- No Biography Information Found -";
			}
			else
			{
				$DisplayMemberBio = strip_tags($MemberBio, '<a><b><i><s><u>');
				/*$DisplayMemberBio = strip_tags_and_attrs($MemberBio, array("html", "body", "a", "b", "i", "s", "u"), array("href", "target"));*/
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
					";
					if ($loggedInPlayerID == $player_id && $loggedInPlayerName != "guest")
					{
						$display_player_ships .= "
							<div class=\"player_ships_entry_buttons_buttonContainer\">
								<button class=\"adminButton adminButtonEdit playerEditShip\" style=\"		margin-right:0px\" title=\"Edit Ship\">
									<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
								</button>
								<button class=\"adminButton adminButtonDelete playerDeleteShip\" style=\"margin-left:0px\" title=\"Delete Ship\">
									<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
								</button>
							</div>
						";
					}
					$display_player_ships .= "	
							<div class=\"player_ships_shipTitleContainer\">
								<a src=\"$link_base/ship/$ship_id\" >
									<div class=\"player_ships_shipTitleText\">
										$manu_name $full_ship_name
									</div>
								</a>
							</div>
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
					";
					if ($ship_assetDesignation != null && $ship_assetDesignation != "")
					{
						//For Named Ships, Display Differently
						if ($ship_canBeNamed == 1
							&& $ship_assetName != null && $ship_assetName != "")
						{
							$display_player_ships .= "
										<tr>
											<td class=\"tooltip_shipTable2_key\">
												<div class=\"tooltip_shipTable2_key_inner\">
												Asset Designation
												</div>
											</td>
											<td class=\"tooltip_shipTable2_value\">
												<div class=\"tooltip_shipTable2_value_inner\" style=\"
													text-shadow: 0px 0px 4px #CCA300;
												\">
												$ship_assetDesignation-$shm_ship_vvarID
												</div>
											</td>
										</tr>
										<tr>
											<td class=\"tooltip_shipTable2_key\">
												<div class=\"tooltip_shipTable2_key_inner\">
												Asset Name
												</div>
											</td>
											<td class=\"tooltip_shipTable2_value\">
												<div class=\"tooltip_shipTable2_value_inner\" style=\"
													text-shadow: 0px 0px 4px #CCA300;
												\">
												VMNS $ship_assetName
												</div>
											</td>
										</tr>
							";
						}
						else
						{
							$display_player_ships .= "
										<tr>
											<td class=\"tooltip_shipTable2_key\">
												<div class=\"tooltip_shipTable2_key_inner\">
												Asset Designation
												</div>
											</td>
											<td class=\"tooltip_shipTable2_value\">
												<div class=\"tooltip_shipTable2_value_inner\" style=\"
													text-shadow: 0px 0px 4px #CCA300;
												\">
												$ship_assetDesignation-$shm_ship_vvarID
												</div>
											</td>
										</tr>
							";					
						}
					}
					else
					{
					
						$display_player_ships .= "
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
						";	
					}
						$display_player_ships .= "
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
								</table>
							</div>
						</div>
					</td>

					<td class=\"player_ships_entry_ship\">
						<div class=\"player_ships_entry_ship_inner\">
							<div class=\"player_ships_entry_ship_inner_imageContainer\">
								<a href=\"$link_base/ship/$ship_id\" >";
								if ($ship_silo != null)
								{
									$display_player_ships .= "
										<img class=\"player_fleet\" align=\"center\" src=\"$link_base/images/silo_topDown/$ship_silo\" />
									";
								}
								$display_player_ships .= "	
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
		
		if ($infoSecLevelID == 4 || $role == "Admin" || $loggedInPlayerID == $player_ID)
		{
			$roles_query = "
				SELECT
					ranks.rank_id
					,ranks.rank_groupName
					,ranks.rank_image
					,ranks.rank_level
					,ranks.rank_name
					,DATE_FORMAT(DATE(members.RankModifiedOn),'%d %b %Y') as RankModifiedOn
					,d.div_id
					,d.div_name
					,case
						when roles.role_displayName != '' then roles.role_displayName
						when roles.role_id is null then 'n/a'
						else roles.role_name
					end as role_name
					,roles.IsPrivate as role_isPrivate
					,u.UnitID
					,u.UnitLevel
					,u.UnitDesignation
					,case
						when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
						else u.UnitFullName
					end as UnitName
					,u.UnitEmblemImage
				FROM projectx_vvarsc2.members
				JOIN projectx_vvarsc2.ranks
					ON members.ranks_rank_id = ranks.rank_id
				LEFT JOIN projectx_vvarsc2.divisions d
					ON members.divisions_div_id = d.div_id
				LEFT JOIN projectx_vvarsc2.UnitMembers um
					on um.MemberID = members.mem_id
				LEFT JOIN projectx_vvarsc2.Units u
					on u.UnitID = um.UnitID
				left JOIN projectx_vvarsc2.roles
					ON um.MemberRoleID = roles.role_id
				WHERE members.mem_sc = 1
					AND members.mem_id = $player_id
				ORDER BY
					roles.role_orderby
			";
		}
		else
		{
			$roles_query = "
				SELECT
					ranks.rank_id
					,ranks.rank_groupName
					,ranks.rank_image
					,ranks.rank_level
					,ranks.rank_name
					,DATE_FORMAT(DATE(members.RankModifiedOn),'%d %b %Y') as RankModifiedOn
					,d.div_id
					,d.div_name
					,case
						when roles.role_displayName != '' then roles.role_displayName
						when roles.role_id is null then 'n/a'
						else roles.role_name
					end as role_name
					,roles.IsPrivate as role_isPrivate
					,u.UnitID
					,u.UnitLevel
					,u.UnitDesignation
					,case
						when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
						else u.UnitFullName
					end as UnitName
					,u.UnitEmblemImage
				FROM projectx_vvarsc2.members
				JOIN projectx_vvarsc2.ranks
					ON members.ranks_rank_id = ranks.rank_id
				LEFT JOIN projectx_vvarsc2.divisions d
					ON members.divisions_div_id = d.div_id
				LEFT JOIN projectx_vvarsc2.UnitMembers um
					on um.MemberID = members.mem_id
				LEFT JOIN projectx_vvarsc2.Units u
					on u.UnitID = um.UnitID
				left JOIN projectx_vvarsc2.roles
					ON um.MemberRoleID = roles.role_id
					AND roles.isPrivate = 0
				WHERE members.mem_sc = 1
					AND members.mem_id = $player_id
				ORDER BY
					roles.role_orderby	
			";
		}
		
		$roles_query_results = $connection->query($roles_query);
		$displayRoles = "";
		
		while(($row = $roles_query_results->fetch_assoc()) != false) {
    		$role_name = $row['role_name'];
			$role_isPrivate = $row['role_isPrivate'];
			$UnitID = $row['UnitID'];
			$UnitDesignation = $row['UnitDesignation'];
			$UnitLevel = $row['UnitLevel'];
			
			$UnitName = $row['UnitName'];
			$UnitEmblemImage = $row['UnitEmblemImage'];
			
			$full_role_name;
			$full_unit_name;
			
			if ($UnitLevel = "Squadron" && $UnitDesignation != null
				&& $UnitDesignation != "")
			{
				$full_unit_name = $UnitDesignation." ".substr($UnitName,6);
				
			}
			else
			{
				$full_unit_name = $UnitName;
			}
			
			if ($UnitEmblemImage == null || $UnitEmblemImage == '')
			{
				$UnitEmblemImage = $link_base."/images/logos/vvar-logo2.png";
			}		
			
			if ($role_name == NULL || $role_name == "n/a") {
				$full_role_name = "- No Assigned Role -";
			}
			else
			{
				if ($full_unit_name != NULL)
				{
					$full_role_name = $role_name."<br /><a href=\"".$link_base."/unit/".$UnitID."\"> ".$full_unit_name." </a>";
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
			
			if ($role_isPrivate == 1)
			{
				$displayRoles .= "
					<div class =\"p_rank_role_name redactedRole\" style=\"
						display: inline-table;
						vertical-align: middle;
						text-align: right;
						width: 100%;
					\">
				";
			}
			else
			{
				$displayRoles .= "
					<div class =\"p_rank_role_name\" style=\"
						display: inline-table;
						vertical-align: middle;
						text-align: right;
						width: 100%;
					\">
				";			
			}
			$displayRoles .= "
					<div class=\"p_rank_role_text\">
						$full_role_name
					</div>
					<div class=\"player_unitImgContainer\"
					\">
						<div class=\"corner corner-top-left\">
						</div>
						<div class=\"corner corner-top-right\">
						</div>
						<div class=\"corner corner-bottom-left\">
						</div>
						<div class=\"corner corner-bottom-right\">
						</div>
						<img class=\"player_unitImg\" align=\"center\" src=\"$UnitEmblemImage\" /></a>
					</div>					
				</div>
			";
		}	
		
		//QUALIFICATIONS
		$qualification_query = "
			select
				mq.RowID
				,q.qualification_id
				,q.qualification_name
				,q.qualification_categoryID
				,lk.CategoryName as qualification_category
				,q.qualification_image
				,IFNULL(mq.qualification_level_id,0) as `qualification_level`
				,q.level1_reqs
				,q.level2_reqs
				,q.level3_reqs
			from projectx_vvarsc2.qualifications q
			join projectx_vvarsc2.LK_QualificationCategories lk
				on lk.CategoryID = q.qualification_categoryID
			join projectx_vvarsc2.member_qualifications mq
				on mq.qualification_id = q.qualification_id
				and mq.member_id = $player_id
			order by
				q.qualification_name
		";
		
		$qualification_query_results = $connection->query($qualification_query);
		$display_player_qualifications = "";
		
		while(($row2 = $qualification_query_results->fetch_assoc()) != false)
		{
			$rowID = $row2['RowID'];
			$qual_id = $row2['qualification_id'];
			$qual_name = $row2['qualification_name'];
			$qual_categoryID = $row2['qualification_categoryID'];
			$qual_category = $row2['qualification_category'];
			$qual_image = $link_base."/images/qualifications/".$row2['qualification_image'];
			$qual_level_id = $row2['qualification_level'];
			$level1_reqs = $row2['level1_reqs'];
			$level2_reqs = $row2['level2_reqs'];
			$level3_reqs = $row2['level3_reqs'];
			
			if ($level1_reqs == null || $level1_reqs == "")
				$level1_reqs = "- No Requirements Found -";
				
			if ($level2_reqs == null || $level2_reqs == "")
				$level2_reqs = "- No Requirements Found -";
				
			if ($level3_reqs == null || $level3_reqs == "")
				$level3_reqs = "- No Requirements Found -";
			
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
				<tr class=\"player_qual_row\"
					data-rowid=$rowID
					data-id=$qual_id
					data-level=$qual_level_id
					data-category=$qual_categoryID
				>
					<td class=\"player_qual_row_name\">$qual_category<br /><strong>$qual_name</strong></td>
					<td class=\"player_qual_row_image_container tooltip-wrap\">
						<img class=\"$imageClassName1\" src=\"$qual_image\" height=\"30px\" width=\"30px\">
						<div class=\"rsi-tooltip\">
							<div class=\"rsi-tooltip-content\">
								<strong>$qual_name - Level 1</strong>
								<br />
								$level1_reqs
							</div>
							<span class=\"rsi-tooltip-bottom\"></span>
						</div>
					</td>
					<td class=\"player_qual_row_image_container tooltip-wrap\">
						<img class=\"$imageClassName2\" src=\"$qual_image\" height=\"30px\" width=\"30px\">
						<div class=\"rsi-tooltip\">
							<div class=\"rsi-tooltip-content\">
								<strong>$qual_name - Level 2</strong>
								<br />
								$level2_reqs
							</div>
							<span class=\"rsi-tooltip-bottom\"></span>
						</div>
					</td>
					<td class=\"player_qual_row_image_container tooltip-wrap\">
						<img class=\"$imageClassName3\" src=\"$qual_image\" height=\"30px\" width=\"30px\">
						<div class=\"rsi-tooltip\">
							<div class=\"rsi-tooltip-content\">
								<strong>$qual_name - Level 3</strong>
								<br />
								$level3_reqs
							</div>
							<span class=\"rsi-tooltip-bottom\"></span>
						</div>
					</td>";
					
			if ($_SESSION['sess_userrole'] == "admin")
			{
				$display_player_qualifications .= "
					<td>
						<div class=\"player_qual_entry_buttons_buttonContainer\">
							<button class=\"adminButton adminButtonEdit adminEditQual\" style=\"		margin-right:0px\" title=\"Edit Qualification\">
								<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
							</button>
							<button class=\"adminButton adminButtonDelete adminDeleteQual\" style=\"margin-left:0px\" title=\"Delete Qualification\">
								<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
							</button>
						</div>
					</td>
				";
			}
					
			$display_player_qualifications .= "
				</tr>
			";
		}
		
		$display_qual_edit = "";
		if ($_SESSION['sess_userrole'] == "admin")
			$display_qual_edit = "
				<button id=\"adminAddQualification\" class=\"adminButton adminButtonCreate\" title=\"Add Qualification\"style=\"
					float: right;
					margin-left: 0px;
					margin-right: 2%;
				\">
					<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
					Add Qualification
				</button>
				<br />
			";
		
		//Awards
		$awards_query = "
			select distinct
				a.AwardID
				,a.AwardName
				,a.AwardImage
				,a.AwardRequirements
				,a.AwardOrderBy
				,COUNT(distinct ma.RowID) as AwardCount
			from projectx_vvarsc2.member_Awards ma
			join projectx_vvarsc2.Awards a
				on a.AwardID = ma.AwardID
			where ma.MemberID = $player_id
			group by
				a.AwardID
				,a.AwardName
				,a.AwardImage
				,a.AwardRequirements
				,a.AwardOrderBy
			order by
				a.AwardOrderBy
				,a.AwardName
		";
		$awards_query_results = $connection->query($awards_query);
		$display_player_awards = "";
		
		while(($row2 = $awards_query_results->fetch_assoc()) != false)
		{
			$award_count = $row2['AwardCount'];
			$award_id = $row2['AwardID'];
			$award_name = $row2['AwardName'];
			$award_image = $link_base."/images/awards/".$row2['AwardImage'];
			$device_image = $link_base."/images/awards/serviceStar.png";
			$level1_reqs = $row2['AwardRequirements'];
			$award_modifiedOn = $row2['ModifiedOn'];
			
			if ($level1_reqs == null || $level1_reqs == "")
				$level1_reqs = "- No Requirements Found -";
				
			$display_player_awards .= "
				<div class=\"p_award tooltip-wrap\">
					<img src=\"$award_image\" height=\"22px\" width=\"80px\" style=\"
						vertical-align: middle;
					\">
			";
			
			if ($award_count > 1)
			{
				if ($award_count > 5)
				{
					$award_count_calculated = 5;
				}
				else
				{
					$award_count_calculated = $award_count;
				}
					
				$display_player_awards .= "
					<div class=\"awardDeviceContainer\">
						<div class=\"awardDevices\">
				";
				for ($i=0; $i < $award_count_calculated - 1; $i++)
				{
					$display_player_awards .= "
							<div class=\"awardDeviceImageContainer\">
								<img class=\"awardDeviceImg\"src=\"$device_image\" height:\"12px\" width=\"13px\" style=\"
									vertical-align: middle;
								\">
							</div>
					";
				}
				$display_player_awards .= "
						</div>
					</div>
				";
			}
			
			
			$display_player_awards .= "
					<div class=\"rsi-tooltip\" style=\"
						bottom: 36px;
					\">
						<div class=\"rsi-tooltip-content\">
							<strong>$award_name 
			";
			if ($award_count > 1)
			{
				$display_player_awards .= "
					(x$award_count)
				";
			}
			
			$display_player_awards .= "</strong>
							<br />
							$level1_reqs
						</div>
						<span class=\"rsi-tooltip-bottom\"></span>
					</div>
				</div>
			";

		}
		$display_awards_edit = "";
		if ($_SESSION['sess_userrole'] == "admin")
			$display_awards_edit = "
				<button id=\"adminAddAward\" class=\"adminButton adminButtonCreate\" title=\"Add Award\"style=\"
					float: right;
					margin-left: 0px;
					margin-right: 2%;
				\">
					<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
					Add Award
				</button>
				<br />
			";		
		
		//MISSION STATISTICS		
		$missionParticipationStats_query = "
			select
				um.RoleCategory
				,COUNT(distinct um.MissionID) as Missions
				,COUNT(case when um.IsLeadership = 1 then 1 else null end) as MissionsAsLeader
			from
			(
				select
					um.MissionID
					,r.RoleCategory
					,r.RoleName
					,r.IsLeadership
				from projectx_vvarsc2.MissionUnitMembers um
				join projectx_vvarsc2.OpUnitTypeMemberRoles r
					on r.OpUnitMemberRoleID = um.OpUnitMemberRoleID
				join projectx_vvarsc2.Missions m
					on m.MissionID = um.MissionID
				join projectx_vvarsc2.LK_MissionStatus lk
					on lk.MissionStatus = m.MissionStatus
					and lk.Description = 'Completed'
				where um.MemberID = $player_id

				union

				select
					sm.MissionID
					,r.RoleCategory
					,r.RoleName
					,r.IsLeadership
				from projectx_vvarsc2.MissionShipMembers sm
				join projectx_vvarsc2.OpUnitTypeMemberRoles r
					on r.OpUnitMemberRoleID = sm.OpUnitMemberRoleID
				join projectx_vvarsc2.Missions m
					on m.MissionID = sm.MissionID
				join projectx_vvarsc2.LK_MissionStatus lk
					on lk.MissionStatus = m.MissionStatus
					and lk.Description = 'Completed'
				where sm.MemberID = $player_id
			) um
			GROUP BY
				um.RoleCategory
			ORDER BY
				um.RoleCategory
							
		";
		$display_player_missionStats = null;
		$missionParticipationStats_query_results = $connection->query($missionParticipationStats_query);
		
		while(($row = $missionParticipationStats_query_results->fetch_assoc()) != false)
		{
			$roleCategory = $row['RoleCategory'];
			$completedMissions = $row['Missions'];
			$completedMissionsAsLeader = $row['MissionsAsLeader'];
			
			$display_player_missionStats .="
				<div class=\"p_rank_stats_entry_header\" style=\"
					
				\">
					<div class=\"player_qual_row_name noPadding\">
						<strong>$roleCategory</strong>
					</div>
				</div>
				<div class=\"p_rank_stats_entry\" style=\"
					display: table-cell;
					padding-left: 4px;
				\">
					<div class=\"p_rank_stats_entry_key\">
						Total
					</div>
					<div class=\"p_rank_stats_entry_value\">
						$completedMissions
					</div>
				</div>
				<div class=\"p_rank_stats_entry\" style=\"
					display: table-cell;
				\">
					<div class=\"p_rank_stats_entry_key\">
						Leadership
					</div>
					<div class=\"p_rank_stats_entry_value\">
						$completedMissionsAsLeader
					</div>
				</div>
			";			
		}
		
		if ($display_player_missionStats == null)
		{
			$display_player_missionStats = "- no mission records found -";
		}
		
		if ($rank_groupName == "Officer" || $rank_groupName == "Senior Officer" || $rank_groupName == "Flag Officer" || $rank_groupName == "Officer Candidate")
		{
			$missionsCreatedStats_query = "
				select
					COUNT(distinct m.MissionID) as MissionsPlanned
				from projectx_vvarsc2.Missions m
				join projectx_vvarsc2.LK_MissionStatus lk1
					on lk1.MissionStatus = m.MissionStatus
					and lk1.Description = 'Completed'
				where m.CreatedBy = $player_id		
			";
			
			$missionsCreatedStats_query_results = $connection->query($missionsCreatedStats_query);
			
			while(($row = $missionsCreatedStats_query_results->fetch_assoc()) != false)
			{
				$plannedCompletedMissions = $row['MissionsPlanned'];
			}
			
			$display_player_missionStats .= "
				<div class=\"p_rank_stats_entry_header\">
					<div class=\"player_qual_row_name noPadding\">
						<strong>Mission Planning</strong>
					</div>
				</div>
				<div class=\"p_rank_stats_entry\" style=\"
					display: table-cell;
					padding-left: 4px;
				\">
					<div class=\"p_rank_stats_entry_key\">
						Missions Planned
					</div>
					<div class=\"p_rank_stats_entry_value\">
						$plannedCompletedMissions
					</div>
				</div>		
			";
		}
		
		//PLAYER STATISTICS
		$playerStats_query = "
			select
				m.CreatedOn
				,m.RankModifiedOn
				,DATE_ADD(UTC_TIMESTAMP(),INTERVAL 930 YEAR) as 'CurrentDate'
			from projectx_vvarsc2.members m
			where m.mem_id = $player_id			
		";
		
		$playerStats_query_results = $connection->query($playerStats_query);
		
		while(($row = $playerStats_query_results->fetch_assoc()) != false)
		{
			$mem_createdOn = $row['CreatedOn'];
			$mem_rankModifiedOn = $row['RankModifiedOn'];
			$currentDate = $row['CurrentDate'];
		
			$datetime_mem_createdOn = date_create($mem_createdOn);
			$datetime_mem_rankModifiedOn = date_create($mem_rankModifiedOn);
			$datetime_sc_today = date_create($currentDate);
			
			$interval_tis = date_diff($datetime_mem_createdOn, $datetime_sc_today);
			$interval_tig = date_diff($datetime_mem_rankModifiedOn, $datetime_sc_today);
			
			$interval_tis_formatted = $interval_tis->format('|%y Years, |%m Months, |%d Days');
			$interval_tig_formatted = $interval_tig->format('|%y Years, |%m Months, |%d Days');
			
			//Clean up TIS
			$interval_tis_formatted = str_replace("|0 Years, ","",$interval_tis_formatted);
			$interval_tis_formatted = str_replace("|1 Years, ","1 Year, ",$interval_tis_formatted);
			
			$interval_tis_formatted = str_replace("|0 Months, ","",$interval_tis_formatted);
			$interval_tis_formatted = str_replace("|1 Months, ","1 Month, ",$interval_tis_formatted);
			
			$interval_tis_formatted = str_replace(", |0 Days","",$interval_tis_formatted);
			$interval_tis_formatted = str_replace("|1 Days","1 Day",$interval_tis_formatted);
			
			$interval_tis_formatted = str_replace("|","",$interval_tis_formatted);
			
			//Clean up TIG
			$interval_tig_formatted = str_replace("|0 Years, ","",$interval_tig_formatted);
			$interval_tig_formatted = str_replace("|1 Years, ","1 Year, ",$interval_tig_formatted);
			
			$interval_tig_formatted = str_replace("|0 Months, ","",$interval_tig_formatted);
			$interval_tig_formatted = str_replace("|1 Months, ","1 Month, ",$interval_tig_formatted);
			
			$interval_tig_formatted = str_replace(", |0 Days","",$interval_tig_formatted);
			$interval_tig_formatted = str_replace("|1 Days","1 Day",$interval_tig_formatted);
			
			$interval_tig_formatted = str_replace("|","",$interval_tig_formatted);
		}
		
		$display_playerStats .= "
			<div class=\"p_rank_stats_entry\">
				<div class=\"p_rank_stats_entry_key\">
					Time-In-Service
				</div>
				<div class=\"p_rank_stats_entry_value\">
					$interval_tis_formatted
				</div>
			</div>
			<div class=\"p_rank_stats_entry\">
				<div class=\"p_rank_stats_entry_key\">
					Time-In-Grade
				</div>
				<div class=\"p_rank_stats_entry_value\">
					$interval_tig_formatted
				</div>
			</div>
		";
		
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
					<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
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
					<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
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
                ,case
					when s.ship_model_designation is not null and s.ship_model_visible = 1 then CONCAT(s.ship_model_designation, ' ', s.ship_name)
                    else s.ship_name
				end as ship_name
			from projectx_vvarsc2.ships s
			join projectx_vvarsc2.manufacturers m
				on m.manu_id = s.manufacturers_manu_id
			order by
				m.manu_name
                ,s.ship_pname
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
		
		/*KILL STATS QUERY*/
		$killStats_query = "
			select
				COUNT(case when k.KillType = 'Aerial' then 1 else null end) as 'AerialKillCount'
				,COUNT(case when k.KillType = 'Aerial' and IsSoloKill = 1 then 1 else null end) as 'SoloAerialKillCount'
				,COUNT(case when k.KillType = 'Infantry' then 1 else null end) as 'InfantryKillCount'
				,COUNT(case when k.KillType = 'Infantry' and IsSoloKill = 1 then 1 else null end) as 'SoloInfantryKillCount'
			from projectx_vvarsc2.MemberKills k
			where k.MemberID = $player_id		
		";
		
		$killStats_query_results = $connection->query($killStats_query);
		
		while(($row = $killStats_query_results->fetch_assoc()) != false)
		{
			$aerialKills = $row['AerialKillCount'];
			$soloAerialKills = $row['SoloAerialKillCount'];
			$infantryKills = $row['InfantryKillCount'];
			$soloInfantryKills = $row['SoloInfantryKillCount'];
		}		
		
		if (($aerialKills != null && $aerialKills != 0) ||
			($infantryKills != null && $infantryKills != 0))
		{
			$display_player_killStats = "
				<div class=\"p_rank_stats_entry\">
					<div class=\"p_rank_stats_entry_key\">
						Confirmed Air-to-Air Kills (solo)
					</div>
					<div class=\"p_rank_stats_entry_value\">
						$soloAerialKills
					</div>
				</div>
				<div class=\"p_rank_stats_entry\">
					<div class=\"p_rank_stats_entry_key\">
						Confirmed Infantry Kills (solo)
					</div>
					<div class=\"p_rank_stats_entry_value\">
						$soloInfantryKills
					</div>
				</div>
			";
		}
		else
		{
			$display_player_killStats = "- no combat records found -";
		}
		
    	$connection->close();
    } else {
        header("Location: ".$link_base."/?page=members");
    }
?>

<h2>Star Citizen Player Profile</h2>
<div id="TEXT">
	<div class="player_topTable_Container">
		<? echo $display_edit_options_profile ?>
		<div class="table_header_block">
		</div>
		<div class="play">
			<!--Row1-->
			<div class="play_row" style="
				border-top: none;
				margin-top: 0px;
			">
				<div class="pavatar">
					<div class="p_section_header" style="
						position: absolute;
						top: 0px;
						width: 100%;
						padding-left: 0px;
					">
						<? echo $full_title; ?>
					</div>
					<div class="pavatar_image_container">
						<div class="corner corner-top-left">
						</div>
						<div class="corner corner-top-right">
						</div>
						<div class="corner corner-bottom-left">
						</div>
						<div class="corner corner-bottom-right">
						</div>
						<img height="200" width="200" alt="<? echo $mem_name; ?>" src="<? $link_base; ?>/images/player_avatars/<? echo $mem_avatar_link; ?>.png" />
					</div>
					
					<div class="p_info" valign="top" align="left">
						<!--Rank-->
						<div id="p_rank_container">
							<div class="partialBorder-left-blue border-left border-top border-4px">
							</div>			
							<div class="partialBorder-right-blue border-right border-top border-4px">
							</div>
							<div id="p_rank_outer">
								<div class="p_rankDetails">
									<div class ="p_rankname">
										<? echo $rank_groupName; ?>
									</div>
								</div>
								<div id="p_rank" align="left" valign="top">
									<div class="p_rankimage_container">
										<img class = "p_rankimage" align="left" alt="<? echo $rank_groupName; ?>" src="<? $link_base; ?>/images/ranks/<? echo $rank_image; ?>.png" />
									</div>
									<div class= "p_rankExtendedData">
										<span class="p_rankExtendedData_rank_name">
											<a href="/wiki/?page=ranks#<?echo $rank_level;?>" target="_blank" style="
												text-decoration: none;
												color: inherit;
												font-size: inherit;
											">
												<? echo $rank_level; ?>
												<br />
												<? echo $rank_name; ?>
											</a>
										</span>
										<br />
										<span class="p_rankExtendedData_rank_date">
											Grade Assigned: <? echo $rankModifiedOn; ?>
										</span>
									</div>
								</div>
								<div class="p_rankDetails" style="margin-bottom:2px;">
									<? echo $displayRoles ?>
								</div>
							</div>
							<div class="partialBorder-right-blue border-right border-bottom border-4px">
							</div>	
							<div class="partialBorder-left-blue border-left border-bottom border-4px">
							</div>	
						</div>

					</div>					
				</div>
				
				<div class="pbio">
					<!--Member Info-->
					<div class="p_details_container">
						<!--General Info-->
						<div id="p_general_stats_container">
							<h4>
								Player Information
							</h4>
							<div id="p_general_stats">
								<div class="player_qual_row_name noPadding">
									VVAR Player Name: <strong><? echo $mem_name; ?></strong>
								</div>
								<div class="player_qual_row_name noPadding">
									Player ID: <strong><? echo $temp_player_id; ?></strong>
								</div>
								<div class="player_qual_row_name noPadding">
									Enlisted: <strong><? echo $mem_createdOn; ?></strong>
								</div>
								<div class="player_qual_row_name noPadding">
									Callsign / RSI Handle: <strong><a href="https://robertsspaceindustries.com/citizens/<? echo $mem_callsign; ?>" target="_blank"><? echo $mem_callsign; ?></a></strong>
								</div>
								<div class="player_qual_row_name noPadding">
									Membership Type: <strong><? echo $displayMembershipType; ?></strong>
								</div>
								<div class="player_qual_row_name noPadding">
									Ships Owned: <strong><? echo $ship_count; ?></strong>
								</div>
								<div class="player_qual_row_name noPadding" style="margin-bottom: 4px;">
									Status: <strong><? echo $mem_status; ?></strong>
								</div>
							</div>
						</div>
						<!--Rank Stats-->
						<div id="p_rank_stats_container">
							<h4 style="
								padding-top: 4px;
							">
								Rank Statistics
							</h4>
							<div id="p_rank_stats">
								<? echo $display_playerStats; ?>
							</div>
						</div>
						<!--Mission Stats-->
						<div id="p_mission_stats_container">
							<h4 style="
								padding-top: 4px;
							">
								Completed Missions
							</h4>
							<div id="p_mission_stats">
								<? echo $display_player_missionStats; ?>
							</div>
						</div>
						<!--Combat Stats-->
						<div id="p_combat_stats_container">
							<h4 style="
								padding-top: 4px;
							">
								Combat Statistics
							</h4>
							<div id="p_combat_stats">
								<? echo $display_player_killStats; ?>
								<a href="../../combatRecord/<? echo $player_id; ?>" style="">View/Edit Combat Records</a>
							</div>
						</div>
					</div>
					

				</div>				
				
			</div>
		</div>

				<!--BIOGRAPHY-->
				<h4 style="padding-left: 0px; margin-left: 0px">
					Member Biography
				</h4>
				<div class="unit_description_container" style="
					margin-bottom: 8px;
					margin-left: 0px;
					margin-right: 0px;
					font-size: 10pt;
				">
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
		

		<!--QUALIFICATIONS-->
		<h4 style="padding-left: 0px; margin-left: 0px">
			Qualifications
		</h4>	
		<div class="table_header_block">
		</div>	
		<div id="p_qual_container" class="play" valign="top" align="left">
			<div class="p_qual" valign="top">
				<div class="p_section_header" style="float:left">
					<a href="<? echo $link_base; ?>/wiki/?page=qual" target="_blank" style="
						text-decoration: none;
						font-size: 1.1em;
						font-weight: normal;
						color: #FFF;
					">
						Qualifications
					</a>
				</div>
				<? echo $display_qual_edit ?>
				<div style="
					width:100%;
				">
					<table class="player_qualifications">
						<? echo $display_player_qualifications; ?>
					</table>
				</div>
			</div>
			<div class="p_awards">
				<div class="p_section_header" style="float:left">
					<a href="<? echo $link_base; ?>/wiki/?page=awards" target="_blank" style="
						text-decoration: none;
						font-size: 1.1em;
						font-weight: normal;
						color: #FFF;
					">
						Awards
					</a>
				</div>
				<? echo $display_awards_edit ?>
				<div class="p_info" valign="top" align="left">

					<div id="p_awards_container" style="
						font-size:0;
						text-align: center;
						margin-bottom: 4px;
					">
						<div class="partialBorder-left-blue border-left border-top border-4px">
						</div>			
						<div class="partialBorder-right-blue border-right border-top border-4px">
						</div>
						
						<div id="p_awards_inner">
							<? echo $display_player_awards; ?>
						</div>

						<div class="partialBorder-left-blue border-left border-bottom border-4px">
						</div>			
						<div class="partialBorder-right-blue border-right border-bottom border-4px">
						</div>							
					</div>
				</div>
			</div>
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
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/playerFunctions/function_mem_EditProfile.php" method="POST" role="form">
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
				<textarea name="Biography" id="Biography" class="adminDialogTextArea"><? echo $MemberBio ?></textarea>			
				
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
					<input type="submit" value="Submit"/>
				</div>	
		</form>
	</div>	

	<!--Add Ship Form-->
	<div id="dialog-form-add-ship" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Add a new Ship to your Fleet!</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/playerFunctions/function_playerShip_Create.php" method="POST" role="form">
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
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/playerFunctions/function_playerShip_Edit.php" method="POST" role="form">
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
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/playerFunctions/function_playerShip_Delete.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel">
					RowID
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" readonly>
				
				<label for="MemberID" class="adminDialogInputLabel">
					Member
				</label>
				<input type="none" name="MemberID" id="MemberID" value="" class="adminDialogTextInput" readonly>
				
				<label for="ShipID" class="adminDialogInputLabel">
					Ship
				</label>
				<input type="none" name="ShipName" id="ShipName" value="" class="adminDialogTextInput" readonly>
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
	</div>

	<!--Add Qualification Form-->
	<div id="dialog-form-add-qual" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update Qualification</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/playerFunctions/function_qualification_Create.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="MemID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="MemID" id="MemID" value="" class="adminDialogTextInput" style="display: none" readonly>				
				
				<label for="Category" class="adminDialogInputLabel">
					Category
				</label>
				<select name="Category" id="Category" class="adminDialogDropDown">
					<option selected="true" disabled="true" value="default" id="CategoryID-default">
						--Select a Category--
					</option>
					<? echo $displayQualificationCategorySelectors ?>
				</select>
				
				<label for="QualificationID" class="adminDialogInputLabel">
					Qualification Name
				</label>
				<select name="QualificationID" id="QualificationID" class="adminDialogDropDown" required>
					<option selected="true" disabled="true" value="default" id="QualID-default">
						--Select a Qualification--
					</option>
					<? echo $displayQualificationsSelectors ?>
				</select>
					
				
				<label for="Level" class="adminDialogInputLabel">
					Qualification Level
				</label>
				<select name="Level" id="Level" class="adminDialogDropDown" required>
					<option selected="true" disabled="true" value="default" id="Level-default">
						--Select a Level--
					</option>
					<option value="1" id="Level-1">
						1 (Basic)
					</option>
					<option value="2" id="Level-2">
						2 (Advanced)
					</option>
					<option value="3" id="Level-3">
						3 (Mastery)
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
	
	<!--Edit Qualification Form-->
	<div id="dialog-form-edit-qual" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update Qualification</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/playerFunctions/function_qualification_Edit.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" style="display: none" readonly>			
			
				<label for="ID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="MemID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="MemID" id="MemID" value="" class="adminDialogTextInput" style="display: none" readonly>				
				
				<label for="Name" class="adminDialogInputLabel">
					Name
				</label>
				<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required readonly>
				
				<label for="Level" class="adminDialogInputLabel">
					Qualification Level
				</label>
				<select name="Level" id="Level" class="adminDialogDropDown" required>
					<option selected="true" disabled="true" value="default" id="Level-default">
						--Select a Level--
					</option>
					<option value="1" id="Level-1">
						1 (Basic)
					</option>
					<option value="2" id="Level-2">
						2 (Advanced)
					</option>
					<option value="3" id="Level-3">
						3 (Mastery)
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
	
	<!--Delete Qualification Form-->
	<div id="dialog-form-delete-qual" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Delete Qualification</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/playerFunctions/function_qualification_Delete.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" style="display: none" readonly>			
			
				<label for="ID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="ID" id="ID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="MemID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="MemID" id="MemID" value="" class="adminDialogTextInput" style="display: none" readonly>				
				
				<label for="Name" class="adminDialogInputLabel">
					Name
				</label>
				<input type="text" name="Name" id="Name" value="" class="adminDialogTextInput" required readonly>
				
				<label for="Level" class="adminDialogInputLabel">
					Qualification Level
				</label>
				<select name="Level" id="Level" class="adminDialogDropDown" required disabled>
					<option selected="true" disabled="true" value="default" id="Level-default">
						--Select a Level--
					</option>
					<option value="1" id="Level-1">
						1 (Basic)
					</option>
					<option value="2" id="Level-2">
						2 (Advanced)
					</option>
					<option value="3" id="Level-3">
						3 (Mastery)
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
		
	<!--Add Award Form-->
	<div id="dialog-form-add-award" class="adminDialogFormContainer">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Add Award to Member</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/playerFunctions/function_award_Create.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="MemID" class="adminDialogInputLabel" style="display: none">
				</label>
				<input type="none" name="MemID" id="MemID" value="" class="adminDialogTextInput" style="display: none" readonly>
				
				<label for="AwardID" class="adminDialogInputLabel">
					Award Name
				</label>
				<select name="AwardID" id="AwardID" class="adminDialogDropDown" required>
					<option selected="true" disabled="true" value="default" id="AwardID-default">
						--Select an Award--
					</option>
					<? echo $displayAwardsSelectors ?>
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
  
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
		$('body').css('background','url(../SC_background_pic_02.png) no-repeat fixed center center transparent');
	});
</script>
-->

<!--Form Controls-->
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
			var rowID = $self.parent().parent().parent().parent().find('.player_ships_entry').data("rowid");
			var shipID = $self.parent().parent().parent().parent().find('.player_ships_entry').data("shipid");
			var ispackage = $self.parent().parent().parent().parent().find('.player_ships_entry').data("package");
			var islti = $self.parent().parent().parent().parent().find('.player_ships_entry').data("lti");
			
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
			
			var rowID = $self.parent().parent().parent().parent().find('.player_ships_entry').data("rowid");
			var shipName = $self.parent().parent().parent().parent().find('.player_ships_entry').data("shipname");
			var ispackage = $self.parent().parent().parent().parent().find('.player_ships_entry').data("package");
			var islti = $self.parent().parent().parent().parent().find('.player_ships_entry').data("lti");
			
			dialog.find('#MemberID').val(memID).text();
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#ShipName').val(shipName).text();
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});
		});

		//Add Qualification
		$('#adminAddQualification').click(function() {
			var dialog = $('#dialog-form-add-qual');
			
			var $self = jQuery(this);
			
			var memID = "<? echo $mem_id ?>";
			
			dialog.find('#MemID').val(memID).text();

			dialog.find('select').find('option').prop('selected',false);
			
			dialog.find('#Category').find('#CategoryID-default').prop('selected',true);
			dialog.find('#QualificationID').find('#QualID-default').prop('selected',true);
			dialog.find('#Level').find('#Level-default').prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});
			
			dialog.find('#Category').change(function() {
				if(typeof $(this).data('options') === "undefined"){
					$(this).data('options',dialog.find(('#QualificationID option')));
				}
				var select2 = dialog.find('#QualificationID');
				var id = $(this).val();
				var filter = id + '_';
				var defaultOption = select2.find('#QualID-default');
				var options = $(this).data('options').filter('[value^=' + filter + ']');
				var optionsList = defaultOption.add(options);
				
				select2.html(optionsList);
				select2.find('#QualID-default').prop('selected',true);				
			});
			dialog.find('#Category').trigger('change');

		});
		
		//Edit Qualification
		$('.adminButton.adminButtonEdit.adminEditQual').click(function() {
			var dialog = $('#dialog-form-edit-qual');
			
			var $self = jQuery(this);
			
			var memID = "<? echo $mem_id ?>";
			
			var rowID = $self.parent().parent().parent().data("rowid");
			var qualID = $self.parent().parent().parent().data("id");
			var qualLevel = $self.parent().parent().parent().data("level");
			var qualName = $self.parent().parent().parent().find('.player_qual_row_name strong').text().trim();
			
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#MemID').val(memID).text();
			dialog.find('#ID').val(qualID).text();
			dialog.find('#Name').val(qualName).text();
			
			dialog.find('#Level').find('option').prop('selected',false);
			dialog.find('#Level').find('#Level-' + qualLevel).prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});

		});

		//Delete Qualification
		$('.adminButton.adminButtonDelete.adminDeleteQual').click(function() {
			var dialog = $('#dialog-form-delete-qual');
			
			var $self = jQuery(this);
			
			var memID = "<? echo $mem_id ?>";
			
			var rowID = $self.parent().parent().parent().data("rowid");
			var qualID = $self.parent().parent().parent().data("id");
			var qualLevel = $self.parent().parent().parent().data("level");
			var qualName = $self.parent().parent().parent().find('.player_qual_row_name strong').text().trim();
			
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#MemID').val(memID).text();
			dialog.find('#ID').val(qualID).text();
			dialog.find('#Name').val(qualName).text();
			
			dialog.find('#Level').find('option').prop('selected',false);
			dialog.find('#Level').find('#Level-' + qualLevel).prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});

		});

		//Add Award
		$('#adminAddAward').click(function() {
			var dialog = $('#dialog-form-add-award');
			
			var $self = jQuery(this);
			
			var memID = "<? echo $mem_id ?>";
			
			dialog.find('#MemID').val(memID).text();

			dialog.find('select').find('option').prop('selected',false);
			
			dialog.find('#AwardID').find('#AwardID-default').prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.player_topTable_Container').css({
				filter: 'blur(2px)'
			});
			$('.player_shipsTable_Container').css({
				filter: 'blur(2px)'
			});

		});
		
		//Delete Award
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('#MemberID').val("").text();
			$('.adminDiaglogFormFieldset').find('#RowID').val("").text();
			$('.adminDiaglogFormFieldset').find('select').find('option').prop('selected',false);
			
			//Hide All Dialog Containers
			$('#dialog-form-edit-profile').hide();
			
			$('#dialog-form-add-ship').hide();
			$('#dialog-form-edit-ship').hide();
			$('#dialog-form-remove-ship').hide();
			
			$('#dialog-form-add-qual').hide();
			$('#dialog-form-edit-qual').hide();
			$('#dialog-form-delete-qual').hide();
			
			$('#dialog-form-add-award').hide();
			
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

<!--Script to Show/Hide Rank Detail Elements-->
<!--
<script>
    $(document).ready(function() {
        $('.p_rankimage').hover(function() {
            $('.p_rankExtendedData_rank_level').addClass("opaque");
            $('.p_rankExtendedData_rank_name').addClass("opaque");
            $('.p_rankExtendedData_rank_date').addClass("opaque");
        },
        function() {
            $('.p_rankExtendedData_rank_level').removeClass("opaque");
            $('.p_rankExtendedData_rank_name').removeClass("opaque");
            $('.p_rankExtendedData_rank_date').removeClass("opaque");
        });
    });
</script>
-->