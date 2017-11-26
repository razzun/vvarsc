<?php include_once('functions/function_auth_user.php'); ?>
<?php include_once('functions/function_getUnitsForUser.php'); ?>

<?
	$UnitID = strip_tags(isset($_GET['pid']) ? $_GET['pid'] : '');
	
	//Function For Generating Child-Unit List
	function generate_list($array,$parent,$level,$realLevel)
	{
		foreach ($array as $value)
		{
			$has_children=false;
			if ($value['ParentUnitID']==$parent)
			{
				if ($has_children==false)
				{
					$has_children=true;
					if ($level == 0)
						echo '<div class="unitHierarchy level'.$level.' UnitLevel'.$value['UnitLevel'].'" style="margin-left: 0px;">';
					else
						echo '<div class="unitHierarchy level'.$level.' UnitLevel'.$value['UnitLevel'].'" style="margin-left: 8px;">';
					
					$level++;
					$realLevel++;
				}

				#Unit Header
				echo '<div class="unitHierarchyHeader">';
					#If This Is Lowest-Level Unit, Don't Display Expand-Arrow
					if ($value['UnitLevel'] != "Squadron" && $value['UnitLevel'] != "Platoon" && $value['UnitLevel'] != "QRF" && $value['UnitLevel'] != "Department")
					{
						echo '<div class="unitHierarchy_arrowContainer"  style="display:table-cell; vertical-align:middle;">';
							echo '<img class="unitHierarchy_row_header_arrow" align="center" src="'.$link_base.'/images/SC_Button01.png" />';
						echo '</div>';
					}
					else
					{
						echo '<div class="unitHierarchy_row_header_arrow_empty">';
						echo '</div>';
					}
					
					//Unit Image//
					if ($value['UnitLevel'] != "Department")
					{
						$temp_unitEmblemImage = "";
						if ($value['UnitEmblemImage'] == null || $value['UnitEmblemImage'] == "")
							$temp_unitEmblemImage = $link_base."/images/logos/vvar-logo2.png";
						else
							$temp_unitEmblemImage = $value['UnitEmblemImage'];
							
						echo '<div class="shipDetails_ownerInfo_tableRow_ImgContainer" style="height: 38px;	width: 38px; padding-left:8px; padding-right:0px; padding-top:2px; padding-bottom:2px; border:none; background:none;">';
						
						if ($value['IsActive'] == "Active")
							echo '<img class="divinfo_rankImg" align="center" style="height:30px;width:30px;vertical-align: middle;"src="'.$temp_unitEmblemImage.'" />';
						else
							echo '<img class="divinfo_rankImg image_inactive" align="center" style="height:30px;width:30px;vertical-align: middle;"src="'.$temp_unitEmblemImage.'" />';
							
						echo '</div>';
					}
					//End Unit Image//
					
					echo '<div class="unitHierarchyHeader_mainContainer">';
					
						echo '<div class="unitHierarchyHeader_textContainer">';
							if ($value['UnitLevel'] == 'Squadron' && $value['UnitDesignation'] != null && $value['UnitDesignation'] != '')
							{
								$formattedUnitName = substr($value['UnitName'],6);
							
								if ($value['IsActive'] == "Active")
								{
									echo '<div class="unitHierarchyHeader_unitName">';
										echo '<a href="'.$link_base.'/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitDesignation'].' '.$formattedUnitName.'</a>';
									echo '</div>';				
								}
								else
								{
									echo '<div class="unitHierarchyHeader_unitName inactive">';
										echo '<a href="'.$link_base.'/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitDesignation'].' '.$formattedUnitName.'</a>';
									echo '</div>';
								}
							}
							else
							{
								if ($value['IsActive'] == "Active")
								{
									echo '<div class="unitHierarchyHeader_unitName">';
										echo '<a href="'.$link_base.'/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitName'].'</a>';
									echo '</div>';				
								}
								else
								{
									echo '<div class="unitHierarchyHeader_unitName inactive">';
										echo '<a href="'.$link_base.'/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitName'].'</a>';
									echo '</div>';
								}							
							}

							#Unit Commander (not applied to Economy "Group" within the Division
							echo '<div class="unitHierarchyHeader_unitCO">';
								echo '<div class="unitHierarchyHeader_key">';
									if ($value['UnitLevel'] == "Group")
									{
										echo '';
									}
									else if ($value['IsActive'] == "Active"
										&& ($value['UnitLeaderID'] != null && $value['UnitLeaderID'] != '')
										&& $value['UnitLevel'] == "Squadron"
									)
									{
										echo 'Squadron Leader';
									}
									else if ($value['IsActive'] == "Active"
										&& ($value['UnitLeaderID'] != null && $value['UnitLeaderID'] != '')
										&& $value['UnitLevel'] == "Platoon"
									)
									{
										echo 'Platoon Leader';
									}
									else if ($value['IsActive'] == "Active" && ($value['UnitLeaderID'] != null && $value['UnitLeaderID'] != ''))
									{
										echo 'Commanding Officer';
									}
								echo '</div>';
								
								echo '<div class="unitHierarchyHeader_value">';
									echo '<div class="unitHierarchyHeader_value_container">';
									if ($value['UnitLevel'] == "Group")
									{
										echo '';
									}
									else if ($value['IsActive'] == "Active")
									{										
										if ($value['UnitLeaderID'] != null && $value['UnitLeaderID'] != '')
										{
											echo '<img class="unitHierarchyContent_rankTinyImage" align="center" src="'.$link_base.'/images/ranks/TS3/'.$value['LeadeRankImage'].'.png" />';
											echo '<a href="'.$link_base.'/player/'.$value['UnitLeaderID'].'" target="_top">'.$value['LeaderRankAbbr'].' '.$value['UnitLeaderName'].'</a>';
										}
										else
											echo '';
									}
									echo '</div>';
								echo '</div>';
							
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
				
				#Child-Units
				echo '<div class="unitHierarchyChildren">';
				generate_list($array,$value['UnitID'],$level,$realLevel);
				echo '</div>';
			}

			if ($has_children==true)
			{
				echo '</div>';
				$level--;
				$realLevel--;
			}
		}
	}
	
	//Function For Checking Unit Leaders of Parent Units For Edit Permissions on This Unit
	function generate_list2($array,$child,$Access,$memberUnits)
	{
		static $_access = 'false';
		foreach ($array as $value)
		{
			foreach ($memberUnits as $value2)
			{
				if ($_access == 'false')
				{
					if ($value['UnitID'] == $child)
					{
						if ($value['UnitID'] == $value2['UnitID'])
						{
							$Access = 'true';
							$_access = 'true';
						}
						//If We Didn't Find Access, check parent node.
						if ($Access == 'false')
						{
							if ($value['ParentUnitID'] != '0')
							{
								generate_list2($array,$value['ParentUnitID'],$Access,$memberUnits);
							}
						}
						else
							break;
					}
				}
			}
		}
		return $_access;
	}
	
	if(is_numeric($UnitID)) {
	
		if ($infoSecLevelID == 4 || $role == "Admin")
		{
			$unit_query = "
				select
					case
						when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
						else u.UnitFullName
					end as UnitName
					,u.UnitDesignation
					,u.UnitCallsign
					,u.UnitDepth
					,u.UnitLevel
					,TRIM(LEADING '\t' from u.UnitDescription) as UnitDescription
					,u.ParentUnitID
					,u.UnitSlogan
					,u.UnitBackgroundImage
					,u.UnitEmblemImage
					,DATE_FORMAT(DATE(u.CreatedOn),'%d %b %Y') as UnitCreatedOn
					,d.div_name
					,um.mem_id
					,um.mem_callsign as mem_name
					,um.rank_name
					,um.rank_abbr
					,um.rank_image
					,um.rank_tinyImage
					,um.rank_level
					,um.rank_name
					,um.rank_groupName
					,um.role_name
					,um.IsPrivate as role_isPrivate
					,um.MemberAssigned
					,m2.mem_id UnitLeaderID
					,m2.mem_callsign UnitLeaderName
					,r3.rank_abbr UnitLeaderRank
				from projectx_vvarsc2.Units u
				left join (
					select
						um.UnitID
						,DATE_FORMAT(DATE(um.CreatedOn),'%d %b %Y') as MemberAssigned
						,m.mem_id
						,r2.role_name
						,r2.role_orderby
						,r2.IsPrivate
						,r.rank_orderby
						,m.mem_callsign
						,r.rank_abbr
						,r.rank_image
						,r.rank_tinyImage
						,r.rank_level
						,r.rank_name
						,r.rank_groupName
					from projectx_vvarsc2.UnitMembers um
					join projectx_vvarsc2.members m
						on m.mem_id = um.MemberID
					join projectx_vvarsc2.ranks r
						on r.rank_id = m.ranks_rank_id
					join projectx_vvarsc2.roles r2
						on r2.role_id = um.MemberRoleID
				) um
					on um.UnitID = u.UnitID
				left join projectx_vvarsc2.divisions d
					on d.div_id = u.DivisionID
				left join projectx_vvarsc2.members m2
					on m2.mem_id = u.UnitLeaderID
				left join projectx_vvarsc2.ranks r3
					on r3.rank_id = m2.ranks_rank_id
				where u.UnitID = $UnitID
				order by
					um.role_orderby
					,um.rank_orderby
					,um.mem_callsign
			";
		}
		else
		{
			$unit_query = "
				select
					case
						when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
						else u.UnitFullName
					end as UnitName
					,u.UnitDesignation
					,u.UnitCallsign
					,u.UnitDepth
					,u.UnitLevel
					,TRIM(LEADING '\t' from u.UnitDescription) as UnitDescription
					,u.ParentUnitID
					,u.UnitSlogan
					,u.UnitBackgroundImage
					,u.UnitEmblemImage
					,DATE_FORMAT(DATE(u.CreatedOn),'%d %b %Y') as UnitCreatedOn
					,d.div_name
					,um.mem_id
					,um.mem_callsign as mem_name
					,um.rank_name
					,um.rank_abbr
					,um.rank_image
					,um.rank_tinyImage
					,um.rank_level
					,um.rank_name
					,um.rank_groupName
					,um.role_name
					,um.IsPrivate as role_isPrivate
					,um.MemberAssigned
					,m2.mem_id UnitLeaderID
					,m2.mem_callsign UnitLeaderName
					,r3.rank_abbr UnitLeaderRank
				from projectx_vvarsc2.Units u
				left join (
					select
						um.UnitID
						,DATE_FORMAT(DATE(um.CreatedOn),'%d %b %Y') as MemberAssigned
						,m.mem_id
						,r2.role_name
						,r2.role_orderby
						,r2.IsPrivate
						,r.rank_orderby
						,m.mem_callsign
						,r.rank_abbr
						,r.rank_image
						,r.rank_tinyImage
						,r.rank_level
						,r.rank_name
						,r.rank_groupName
					from projectx_vvarsc2.UnitMembers um
					join projectx_vvarsc2.members m
						on m.mem_id = um.MemberID
					join projectx_vvarsc2.ranks r
						on r.rank_id = m.ranks_rank_id
					join projectx_vvarsc2.roles r2
						on r2.role_id = um.MemberRoleID
						and r2.isPrivate = 0
				) um
					on um.UnitID = u.UnitID
				left join projectx_vvarsc2.divisions d
					on d.div_id = u.DivisionID
				left join projectx_vvarsc2.members m2
					on m2.mem_id = u.UnitLeaderID
				left join projectx_vvarsc2.ranks r3
					on r3.rank_id = m2.ranks_rank_id
				where u.UnitID = $UnitID
				order by
					um.role_orderby
					,um.rank_orderby
					,um.mem_callsign
			";
		
		}
		
		$unit_query_result = $connection->query($unit_query);
		
		$display_details = "";
		$display_members = "";
		
		while(($row1 = $unit_query_result->fetch_assoc()) != false) {
		
			$depth = $row1['UnitDepth'];
			$unitLevel = $row1['UnitLevel'];
			$divName = $row1['div_name'];
			$unitName = $row1['UnitName'];
			$unitDesignation = $row1['UnitDesignation'];
			$unitCallsign = $row1['UnitCallsign'];
			$unitSlogan = $row1['UnitSlogan'];
			$unitDescription = $row1['UnitDescription'];
			$unitBackgroundImage = $row1['UnitBackgroundImage'];
			$unitEmblemImage = $row1['UnitEmblemImage'];
			$unitCreatedOn = $row1['UnitCreatedOn'];
			$unitLeaderID = $row1['UnitLeaderID'];
			$unitLeaderRank = $row1['UnitLeaderRank'];
			$unitLeaderName = $row1['UnitLeaderName'];
			$parentUnitID = $row1['ParentUnitID'];
			$rank_abbr = $row1['rank_abbr'];
			$rank_image = $row1['rank_image'];
			$rank_tinyImage = $row1['rank_tinyImage'];
			$rank_level = $row1['rank_level'];
			$rank_name = $row1['rank_name'];
			$rank_groupName = $row1['rank_groupName'];
			$mem_name = $row1['mem_name'];
			$mem_role = $row1['role_name'];
			$mem_role_isPrivate = $row1['role_isPrivate'];
			$mem_assigned_date = $row1['MemberAssigned'];
			$mem_id = $row1['mem_id'];
			
			$unitLeaderTitle = "";
			
			if ($unitLevel == "Squadron")
				$unitLeaderTitle = "Squadron Leader";
			else if ($unitLevel == "Platoon")
				$unitLeaderTitle = "Platoon Leader";
			else
				$unitLeaderTitle = "Commanding Officer";
				
			
			if ($unitBackgroundImage == null || $unitBackgroundImage == '')
			{
				$unitBackgroundImage = $link_base.'/images/backgrounds/Gladius_01.jpg';
			}
			
			if ($unitEmblemImage == null || $unitEmblemImage == '')
			{
				$unitEmblemImage = $link_base.'/images/logos/vvar-logo2.png';
			}
			
			if ($unitCallsign == null)
			{
				$unitCallsign = "- No Callsign Created -";
			}
			
			if ($mem_id != null)
			{
				$display_members .="
					<tr class=\"shipDetails_ownerInfo_tableRow\">
						<td class=\"shipDetails_ownerInfo_tableRow_inner\">
							<div class=\"shipDetails_ownerInfo_tableRow_ImgContainer\">
								<div class=\"corner corner-top-left\">
								</div>
								<div class=\"corner corner-top-right\">
								</div>
								<div class=\"corner corner-bottom-left\">
								</div>
								<div class=\"corner corner-bottom-right\">
								</div>
								<img class=\"divinfo_rankImg\" align=\"center\" alt=\"$rank_abbr\" src=\"".$link_base."/images/ranks/$rank_image.png\" />
							</div>
							<div class=\"shipDetails_ownerInfo_tableRow_memInfoContainer\">
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo1\">
									<a href=\"".$link_base."/player/$mem_id\" target=\"_top\">$mem_name</a>
								</div>
				";
				if ($mem_role_isPrivate == 1)
				{
					$display_members .= "
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo3 redactedRole\">
									$mem_role
								</div>
					
					";
				}
				else
				{
					$display_members .= "
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo3\">
									$mem_role
								</div>
					
					";
				}
				$display_members .= "
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo4\">
									Assigned $mem_assigned_date
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo5\">
									$rank_abbr  // $rank_level
								</div>
							</div>
						</td>
					</tr>
				";
			}
			else
			{
				$display_members .="
					<tr class=\"shipDetails_ownerInfo_tableRow\">
						<td class=\"shipDetails_ownerInfo_tableRow_inner\">
							- No Assigned Personnel -
						</td>
					</tr>
				";				
			}
		}
		

		$display_details .= "
		<div class=\"play\" style=\"width: 100%; padding:0;\">				
			<div class=\"pavatar\" style=\"
				padding-top: 8px;
				width: 240px;
			\">
				<div class=\"pavatar_image_container\">
					<div class=\"corner corner-top-left\">
					</div>
					<div class=\"corner corner-top-right\">
					</div>
					<div class=\"corner corner-bottom-left\">
					</div>
					<div class=\"corner corner-bottom-right\">
					</div>
					<img height=\"200\" width=\"200\" alt=\"<? echo $mem_name; ?>\" src=\"$unitEmblemImage\" />
				</div>
			</div>
			<div class=\"p_info responsiveRow\" style=\"width: 100%; vertical-align: top\">
				<h2 style=\"margin-left: 0px; padding-left: 8px\">
					$unitName
				</h2>
				<div class=\"shipDetails_info2\">
					<table class=\"shipDetails_info1_table\">
		";
					
						if ($unitLevel != "Department")
						{
							if ($unitLevel == "Squadron" && $unitDesignation != null && $unitDesignation != "")
							{
								$display_details .= "
									<tr class=\"shipDetails_info1_table_row\">
										<td class=\"shipDetails_info1_table_row_td_key\">
											Unit Designation
										</td>
										<td class=\"shipDetails_info1_table_row_td_value\">
											$unitDesignation
										</td>
									</tr>
								";
							}
							if ($unitSlogan != null && $unitSlogan != "")
							{
							$display_details .= "
								<tr class=\"shipDetails_info1_table_row\">
									<td class=\"shipDetails_info1_table_row_td_key\">
										Slogan
									</td>
									<td class=\"shipDetails_info1_table_row_td_value\">
										$unitSlogan
									</td>
								</tr>
								";
							}
							$display_details .= "
								<tr class=\"shipDetails_info1_table_row\">
									<td class=\"shipDetails_info1_table_row_td_key\">
										Radio Callsign
									</td>
									<td class=\"shipDetails_info1_table_row_td_value\">
										<i>$unitCallsign</i>
									</td>
								</tr>
							";
						}
						$display_details .= "
						<tr class=\"shipDetails_info1_table_row\">
							<td class=\"shipDetails_info1_table_row_td_key\">
								$unitLeaderTitle
							</td>
							<td class=\"shipDetails_info1_table_row_td_value\">
								<a style=\"text-decoration: none\" href=\"$link_base/player/$unitLeaderID\" target=\"_top\">$unitLeaderRank $unitLeaderName</a>
							</td>
						</tr>
						<tr class=\"shipDetails_info1_table_row\">
							<td class=\"shipDetails_info1_table_row_td_key\">
								Established
							</td>
							<td class=\"shipDetails_info1_table_row_td_value\">
								$unitCreatedOn
							</td>
						</tr>
					</table>
				</div>";
				if ($unitLevel != "Department")
				{
					$display_details .= "				
						<div class=\"operationsListItem_MetaData_Right\" style=\"
							float: right;
							font-size: 10pt;
							margin-top: 4px;
						\">
							<div class=\"div_filters_container\">
								<div class=\"div_filters_entry\">
									<a href=\"$link_base/missions&unit=$UnitID\">View Missions for this Unit</a>
								</div>
							</div>
						</div>
					";
				}
				

				/*KILL STATS QUERY*/
				$killStats_query = "
					select
						COUNT(case when k.KillType = 'Aerial' then 1 else null end) as 'AerialKillCount'
						,COUNT(case when k.KillType = 'Aerial' and IsSoloKill = 1 then 1 else null end) as 'SoloAerialKillCount'
						,COUNT(case when k.KillType = 'Infantry' then 1 else null end) as 'InfantryKillCount'
						,COUNT(case when k.KillType = 'Infantry' and IsSoloKill = 1 then 1 else null end) as 'SoloInfantryKillCount'
					from projectx_vvarsc2.MemberKills k
					where k.MemberID in (
						select
							um.MemberID
						from projectx_vvarsc2.UnitMembers um
						where um.UnitID = $UnitID
					)
				";
				
				$killStats_query_results = $connection->query($killStats_query);
				
				while(($row = $killStats_query_results->fetch_assoc()) != false)
				{
					$aerialKills = $row['AerialKillCount'];
					$soloAerialKills = $row['SoloAerialKillCount'];
					$infantryKills = $row['InfantryKillCount'];
					$soloInfantryKills = $row['SoloInfantryKillCount'];
				}

				//AGGREGATE COMBAT STATISTICS
				if ($unitLevel == "Squadron" || $unitLevel == "Platoon" || $unitLevel == "QRF")
				{
					$display_details .= "
						<div class=\"pbio\" style=\"display: block;\">
							<div class=\"p_details_container\">
								<!--Combat Stats-->
								<div id=\"p_combat_stats_container\">
									<h4 style=\"
										padding-top: 4px;
									\">
										Combat Statistics
									</h4>
									<div id=\"p_combat_stats\">
										<div class=\"p_rank_stats_entry_header\" style=\"
											
										\">
											<div class=\"player_qual_row_name noPadding\">
												<strong>Confirmed Aerial KIA</strong>
											</div>
										</div>
										<div class=\"p_rank_stats_entry\" style=\"display: inline-block;\">
											<div class=\"p_rank_stats_entry_key\">
												Total
											</div>
											<div class=\"p_rank_stats_entry_value\">
												$aerialKills
											</div>
										</div>
										<br />
										<div class=\"p_rank_stats_entry_header\" style=\"
											
										\">
											<div class=\"player_qual_row_name noPadding\">
												<strong>Confirmed Infantry KIA</strong>
											</div>
										</div>
										<div class=\"p_rank_stats_entry\" style=\"display: inline-block;\">
											<div class=\"p_rank_stats_entry_key\">
												Total
											</div>
											<div class=\"p_rank_stats_entry_value\">
												$infantryKills
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					";
				}

				$display_details .= "				
			</div>
		</div>
		";		
		
		$displayUnitDescription1 = "";
		$displayUnitDescription2 = "";
		
		if ($depth < 4 && $unitLevel != "Platoon")
		{
			if ($unitDescription == null || $unitDescription == "")
			{
				$displayUnitDescription1 .= "
					<h3>
						Description
					</h3>
					<div class=\"unit_description_container\">
						<div class=\"top-line\">
						</div>
						<div class=\"corner4 corner-diag-blue-topLeft\">
						</div>
						<div class=\"corner4 corner-diag-blue-topRight\">
						</div>
						<div class=\"corner4 corner-diag-blue-bottomLeft\">
						</div>
						<div class=\"corner4 corner-diag-blue-bottomRight\">
						</div>
				";
						
				$displayUnitDescription2 .= "
					</div>
				";
				
				$displayUnitDescriptionContent = "- No Description Found -";
			}
			else
			{				
				$displayUnitDescription1 .= "
					<h3>
						Description
					</h3>
					<div class=\"unit_description_container\">
						<div class=\"top-line\">
						</div>
						<div class=\"corner4 corner-diag-blue-topLeft\">
						</div>
						<div class=\"corner4 corner-diag-blue-topRight\">
						</div>
						<div class=\"corner4 corner-diag-blue-bottomLeft\">
						</div>
						<div class=\"corner4 corner-diag-blue-bottomRight\">
						</div>
				";
						
				$displayUnitDescription2 .= "
					</div>
				";
				
				$displayUnitDescriptionContent = $unitDescription;
			}
		}
		else
		{
			$displayUnitDescription1 = "";
			$displayUnitDescription2 = "";
		}		
		
		$units_query = "select
							u.UnitID
							,u.UnitDesignation
							,u.UnitName
							,u.UnitShortName
							,u.UnitCallsign
							,u.DivisionID
							,d.div_name
							,u.IsActive
							,CASE
								when u.IsActive = 1 then 'Active'
								else 'Inactive'
							end as IsActive
							,u.UnitLevel
							,u.ParentUnitID
							,u.UnitEmblemImage
							,DATE_FORMAT(DATE(u.CreatedOn),'%d %b %Y') as UnitCreatedOn
							,m.mem_id as UnitLeaderID
							,m.mem_callsign as UnitLeaderName
							,m.rank_tinyImage as LeadeRankImage
							,m.rank_abbr as LeaderRankAbbr
							,u.UnitCode as SortOrder
						from projectx_vvarsc2.Units u
						left join (
							select
								m.mem_callsign
								,m.mem_id
								,r.rank_tinyImage
								,r.rank_abbr
							from projectx_vvarsc2.members m
							join projectx_vvarsc2.ranks r
								on r.rank_id = m.ranks_rank_id
						) m
							on m.mem_id = u.UnitLeaderID
						left join projectx_vvarsc2.divisions d
							on d.div_id = u.DivisionID
						where u.IsHidden = 0
						order by
							18
							,u.UnitName";	
		
		$units_query_results = $connection->query($units_query);
		
		while(($row = $units_query_results->fetch_assoc()) != false) {
		
			$units[$row['UnitID']] = array(
				'UnitID' => $row['UnitID']
				,'UnitDesignation' => $row['UnitDesignation']
				,'UnitName' => $row['UnitName']
				,'UnitShortName' => $row['UnitShortName']
				,'UnitCallsign' => $row['UnitCallsign']
				,'DivisionID' => $row['DivisionID']
				,'DivisionName' => $row['div_name']
				,'IsActive' => $row['IsActive']
				,'UnitLevel' => $row['UnitLevel']
				,'ParentUnitID' => $row['ParentUnitID']
				,'UnitCreatedOn' => $row['UnitCreatedOn']
				,'UnitLeaderName' => $row['UnitLeaderName']
				,'UnitLeaderID' => $row['UnitLeaderID']
				,'LeadeRankImage' => $row['LeadeRankImage']
				,'LeaderRankAbbr' => $row['LeaderRankAbbr']
				,'UnitEmblemImage' => $row['UnitEmblemImage']
			);
		}
		$displayChildren1 = "";
		$displayChildren2 = "";
		
		if ($unitLevel != "Department" & $unitLevel!= "Platoon" & $unitLevel != "Squadron")
		{
				$displayChildren1 .= "
				<br />
				<h3>
					Subordinate Units
				</h3>
				<div class=\"table_header_block\">
				</div>
				<div class=\"units_tree_container\">";
				
				$displayChildren2 .= "
				</div>";
		}
		else
		{
			$displayChildren1 = "";
			$displayChildren2 = "";
		}
		
		$display_selectors = "";
		
		if ($parentUnitID == 0)
		{
			$display_selectors .= "
				<div class=\"div_filters_container\">
					<div class=\"div_filters_entry\">
						<a href=\"".$link_base."/units\">&#8672; Back to Parent</a>
					</div>
				</div>
			";		
		}
		else
		{
			$display_selectors .= "
				<div class=\"div_filters_container\">
					<div class=\"div_filters_entry\">
						<a href=\"".$link_base."/unit/$parentUnitID\">&#8672; Back to Parent</a>
					</div>
				</div>
			";
		}
		
		$unitQualificationsQuery = "
			select
				r.role_name
				,lk.CategoryName
				,q.qualification_name
				,q.qualification_image
				,u.QualificationLevel
				,q.level1_reqs
				,q.level2_reqs
				,q.level3_reqs
			from projectx_vvarsc2.UnitQualifications u
			join projectx_vvarsc2.roles r
				on r.role_id = u.RoleID
			join projectx_vvarsc2.qualifications q
				on q.qualification_id = u.QualificationID
			join projectx_vvarsc2.LK_QualificationCategories lk
				on lk.CategoryID = q.qualification_categoryID
			where u.UnitID = $UnitID
				and u.IsActive = 1
			order by
				r.role_orderby desc
				,r.role_name
				,q.qualification_name
		";
		
		$unitQual_query_results = $connection->query($unitQualificationsQuery);
		
		$displayUnitQual = "";
		
		if(($unitLevel == "Squadron" || $unitLevel == "Platoon")
			&& mysqli_num_rows($unitQual_query_results) > 0)
		{
			$displayUnitQual .= "
				<br />
				<h3>
					Role Requirements
				</h3>
				<div class=\"table_header_block\">
				</div>
				<div class=\"unit_details_container blkBackground35\" style=\"
					display:inline-table;
				\">
			";
		}
		
		$previousGroup = "";
		$currentGroup = "";
		while(($row5 = $unitQual_query_results->fetch_assoc()) != false)
		{
			$roleName = $row5['role_name'];
			$categoryName = $row5['CategoryName'];
			$qualName = $row5['qualification_name'];
			$qualImage = $link_base."/images/qualifications/".$row5['qualification_image'];
			$qualLevel = $row5['QualificationLevel'];
			$level1_reqs = $row5['level1_reqs'];
			$level2_reqs = $row5['level2_reqs'];
			$level3_reqs = $row5['level3_reqs'];
			
			if ($level1_reqs == null || $level1_reqs == "")
				$level1_reqs = "- No Requirements Found -";
				
			if ($level2_reqs == null || $level2_reqs == "")
				$level2_reqs = "- No Requirements Found -";
				
			if ($level3_reqs == null || $level3_reqs == "")
				$level3_reqs = "- No Requirements Found -";
			
			$imageClassName1 = "player_qual_row_image";
			$imageClassName2 = "player_qual_row_image";
			$imageClassName3 = "player_qual_row_image";
			if ($qualLevel == 1) {
				$imageClassName1 = "player_qual_row_image_highlighted";
				$imageClassName2 = "player_qual_row_image";
				$imageClassName3 = "player_qual_row_image";
			}
			else if ($qualLevel == 2) {
				$imageClassName1 = "player_qual_row_image_highlighted";
				$imageClassName2 = "player_qual_row_image_highlighted";
				$imageClassName3 = "player_qual_row_image";
			}
			else if ($qualLevel == 3) {
				$imageClassName1 = "player_qual_row_image_highlighted";
				$imageClassName2 = "player_qual_row_image_highlighted";
				$imageClassName3 = "player_qual_row_image_highlighted";
			}
				
			$currentGroup = $roleName;
		
			//If This is a New Group, Open a New Row and Title
			if ($currentGroup != $previousGroup)
			{
				//If This is not 1st Row, Close Previous Row
				if ($previousGroup != "")
				{
					$displayUnitQual .= "
							</table>
						</div>
					";
				}
				
				//Open New Group
				$displayUnitQual .= "
					<div class=\"qual_block blkBackground35\">
						<div class=\"corner corner-top-left\">
						</div>
						<div class=\"corner corner-top-right\">
						</div>
						<div class=\"corner corner-bottom-left\">
						</div>
						<div class=\"corner corner-bottom-right\">
						</div>
						<div class=\"p_section_header\" style=\"
							margin-top: 0;
							text-align: center;
							padding-left: 0;
						\">
							$roleName
						</div>
						<table class=\"player_qualifications\">
				";
			}
			//Content of Group
			$displayUnitQual .= "
				<tr class=\"player_qual_row\" style=\"background:none;\">
					<td class=\"player_qual_row_image_container tooltip-wrap\">
						<img class=\"$imageClassName1\" src=\"$qualImage\" height=\"30px\" width=\"30px\">
						<div class=\"rsi-tooltip\">
							<div class=\"rsi-tooltip-content\">
								<strong>$qualName - Level 1</strong>
								<br />
								$level1_reqs
							</div>
							<span class=\"rsi-tooltip-bottom\"></span>
						</div>
					</td>
					<td class=\"player_qual_row_image_container tooltip-wrap\">
						<img class=\"$imageClassName2\" src=\"$qualImage\" height=\"30px\" width=\"30px\">
						<div class=\"rsi-tooltip\">
							<div class=\"rsi-tooltip-content\">
								<strong>$qualName - Level 2</strong>
								<br />
								$level2_reqs
							</div>
							<span class=\"rsi-tooltip-bottom\"></span>
						</div>
					</td>
					<td class=\"player_qual_row_image_container tooltip-wrap\">
						<img class=\"$imageClassName3\" src=\"$qualImage\" height=\"30px\" width=\"30px\">
						<div class=\"rsi-tooltip\">
							<div class=\"rsi-tooltip-content\">
								<strong>$qualName - Level 3</strong>
								<br />
								$level3_reqs
							</div>
							<span class=\"rsi-tooltip-bottom\"></span>
						</div>
					</td>
					<td class=\"player_qual_row_name\">$categoryName<br /><strong>$qualName</strong></td>
				</tr>			
			";
			$previousGroup = $currentGroup;
		}
		if(($unitLevel == "Squadron" || $unitLevel == "Platoon")
			&& mysqli_num_rows($unitQual_query_results) > 0)
		{
			//Close Last Group
			$displayUnitQual .= "
						</table>
					</div>
				</div>
			";		
		}			
		
		
		$unitShipsQuery = "
			select
				m.manu_shortName
				,s.ship_id
				,s.ship_name
				,s.ship_model_designation
				,s.ship_model_visible
				,s.ship_link
				,s.ship_silo
				,s.ship_price
				,REPLACE(s.ship_classification,'_',' ') as ship_classification
				,sed.MaxCrew
				,us.Purpose
			from projectx_vvarsc2.UnitShips us
			join projectx_vvarsc2.ships s
				on s.ship_id = us.ShipID
			join projectx_vvarsc2.manufacturers m
				on m.manu_id = s.manufacturers_manu_id
			left join projectx_vvarsc2.ShipStats_v2 sed
				on sed.ShipID = s.ship_id
			where us.UnitID = $UnitID
			order by
				m.manu_shortName
				,s.ship_name
		";
		
		$unitShips_query_results = $connection->query($unitShipsQuery);
		
		$displayUnitShips = "";
		
		if($unitLevel == "Squadron" || $unitLevel == "QRF")
		{
			$displayUnitShips .= "
				<br />
				<h3>
					Supported Equipment
				</h3>
				<div class=\"player_ships_container\">
					<div class=\"top-line-yellow\">
					</div>
					<table id=\"player_ships\">
			";
		}
		
		if(mysqli_num_rows($unitShips_query_results) > 0)
		{
			while(($row2 = $unitShips_query_results->fetch_assoc()) != false)
			{
				$manu_shortName = $row2['manu_shortName'];
				$ship_id = $row2['ship_id'];
				$ship_name = $row2['ship_name'];
				$ship_model_designation = $row2['ship_model_designation'];
				$ship_model_visible = $row2['ship_model_visible'];
				$ship_link = $row2['ship_link'];
				$ship_silo = $row2['ship_silo'];
				$ship_price = $row2['ship_price'];
				$ship_purpose = $row2['Purpose'];
				$ship_classification = $row2['ship_classification'];
				$ship_max_crew = $row2['MaxCrew'];
				
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
				
				if($unitLevel == "Squadron" || $unitLevel == "QRF")
				{
					$displayUnitShips .= "
					<tr class=\"player_ships_row\">
						<td class=\"player_ships_entry\">
						
							<div class=\"player_ships_shipTitle\">
								<div class=\"player_ships_shipTitleContainer\">
									<a href=\"".$link_base."/ship/$ship_id\" >
										<div class=\"player_ships_shipTitleText\">
											$manu_shortName $full_ship_name
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
										<tr>
											<td class=\"tooltip_shipTable2_key\">
												<div class=\"tooltip_shipTable2_key_inner\">
												Classification
												</div>
											</td>
											<td class=\"tooltip_shipTable2_value\">
												<div class=\"tooltip_shipTable2_value_inner\">
												$ship_classification
												</div>
											</td>
										</tr>										
										<tr>
											<td class=\"tooltip_shipTable2_key\">
												<div class=\"tooltip_shipTable2_key_inner\">
												Purpose
												</div>
											</td>
											<td class=\"tooltip_shipTable2_value\">
												<div class=\"tooltip_shipTable2_value_inner\">
												$ship_purpose
												</div>
											</td>
										</tr>
										<tr>
											<td class=\"tooltip_shipTable2_key\">
												<div class=\"tooltip_shipTable2_key_inner\">
												Maximum Crew
												</div>
											</td>
											<td class=\"tooltip_shipTable2_value\">
												<div class=\"tooltip_shipTable2_value_inner\">
												$ship_max_crew
												</div>
											</td>
										</tr>								
										<tr>
											<td class=\"tooltip_shipTable2_key\">
												<div class=\"tooltip_shipTable2_key_inner\">
												Purchase Price
												</div>
											</td>
											<td class=\"tooltip_shipTable2_value\">
												<div class=\"tooltip_shipTable2_value_inner\">
												$$ship_price
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
									<a href=\"".$link_base."/ship/$ship_id\" >
										<img class=\"player_fleet\" align=\"center\" src=\"".$link_base."/images/silo_topDown/$ship_silo\" />
									</a>
								</div>
							</div>
							<div class=\"playerShips_table_header_block2\">
							</div>
						</td>
					</tr>";
				}
			}
		}
		else
		{
			if ($unitLevel == "Squadron" || $unitLevel == "QRF")
			{
				$displayUnitShips .= "
					<tr>
						<td>
							<em>- No Ships Currently Registered -</em>
						</td>
					</tr>
				";		
			}
		}
		
		if($unitLevel == "Squadron" || $unitLevel == "QRF")
		{
			$displayUnitShips .= "
					</table>
				</div>
			";
		}
		
		if($_SESSION['sess_userrole'] == "officer")
		{
			$memberUnits = array();
			$memberUnits = getUnitsForUser($connection, $_SESSION['sess_user_id']);
			$access = generate_list2($units,$UnitID,'false',$memberUnits);
		}
		
		$displayEdit = "";
		IF(($_SESSION['sess_userrole'] == "admin") ||
			($_SESSION['sess_userrole'] == "officer") && $access == "true" )
		{
			$displayEdit = "
				<button class=\"adminButton adminButtonEdit\" title=\"Edit Unit\" style=\"
					float: right;
					margin-left: 0px;
					margin-right: 2%;
				\">
					<img height=\"20px\" class=\"adminButtonImage\" src=\"".$link_base."/images/misc/button_edit.png\">
					Edit Unit
				</button>
				<br />
			";
		}
		
		$connection->close();
	}
	else
	{
        header("Location: ".$link_base."/units");
    }

?>

<? echo $display_selectors ?>
<!--
<h4>
	<? echo $unitSlogan ?>
</h4>
-->
<div id="TEXT">

  <!-- Comment -->
	<div class="units_main_container">
		<? echo $displayEdit ?>
	
		<? echo $display_details ?>
		
		<? echo $displayUnitDescription1 ?>
		<? echo nl2br($displayUnitDescriptionContent) ?>
		<? echo $displayUnitDescription2 ?>	
		<? echo $displayUnitQual ?>		
		
		<br />
		<h3>
			Unit Personnel
		</h3>
		<div class="table_header_block">
		</div>
		<div class="unit_details_container blkBackground35">
			<table class="shipDetails_ownerInfo_table">
				<?echo $display_members; ?>
			</table>
		</div>
		
		<? echo $displayUnitShips ?>
		
		<? echo $displayChildren1 ?>
		<? generate_list($units,$UnitID,($depth + 1),0); ?>
		<? echo $displayChildren2 ?>

	</div>
</div> 

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="<? $link_base; ?>/js/jquery.jScale.js"></script>
  
<!--Script to Show/Hide Rows when Arrows are clicked on each row-->
<script language="javascript">
    $(document).ready(function () {
	
/*
		var mainClass = $('#MainWrapper');
		var footerClass = $('#FOOTER');
	
		$('.parallax__layer').css('height', mainClass.height() );
		
		var img = new Image;
		img.src = $('#parallax__layer--back3').css('background-image').replace(/url\(|\)$/ig, "");
		var bgImgWidth = img.width;
		var bgImgHeight = img.height;		
		
		var scaleFactor = mainClass.height() / bgImgHeight;
		
		$('#parallax__layer--back3').css({
			transform: 'translateZ(-4px) scale(5)'
		});
*/		
		
		<!--Hide All Other Nodes-->
        $(".unitHierarchyChildren").hide();
		
		<!--Open Division Nodes by Default -->
		$(".unitHierarchy.level0").children(".unitHierarchyChildren").show();
		$(".unitHierarchy.level0").children(".unitHierarchyHeader").children(".unitHierarchy_row_header_arrow").toggleClass('rotate90CW');
		
		<!--Open Inactive Nodes by Default->>
		$(".unitHierarchyHeader_unitName.inactive").parent().parent().parent().parent().children(".unitHierarchyChildren").show();
		$(".unitHierarchyHeader_unitName.inactive").parent().parent().parent().children(".unitHierarchy_arrowContainer").children(".unitHierarchy_row_header_arrow").toggleClass('rotate90CW');
		
        $(".unitHierarchy_row_header_arrow").click(function () {
		
			var websiteClass = $('#TEXT');
			var currentContentHeight = websiteClass.height();
			
            $(this).parent().parent().parent().children(".unitHierarchyChildren").slideToggle(500);
			$(this).toggleClass('rotate90CW');

        });	

		/*
		$("#MainWebsiteInner").resize(function () {
		
			var mainClass2 = $(this);
			$('.parallax__layer').css('height', mainClass2.height() );
		});
		*/
		
    });
	
</script>

<!-- Script for changing background image-->
<script>
	$(document).ready(function() {
		$('.background').css('background-image','url(<?php echo $unitBackgroundImage ?>)');
	});
</script>

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
		else if(($( window ).width() < 1200)) {
			imageClass.jScale({w: '40%'});
			imageClass.css({
					"margin": '0px'
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
		else if(($( window ).width() < 1200)) {
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
	
	$(function() {

		$('.adminButton.adminButtonEdit').click(function() {
			var unitID = "<?php echo $UnitID ?>";
			var link_base = "<?php echo $link_base ?>";

			window.location.href = link_base + "/admin/?page=admin_unit&pid=" + unitID;
			
		});
	});

</script>