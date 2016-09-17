<?php include_once('functions/function_auth_user.php'); ?>
<?php include_once('functions/function_getUnitsForUser.php'); ?>

<?
	$UnitID = strip_tags(isset($_GET[pid]) ? $_GET[pid] : '');
	
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
					echo '<div class="unitHierarchy level'.$level.'" style="margin-left: '.($realLevel * 8).'px;">';
					$level++;
					$realLevel++;
				}

				#Unit Header
				echo '<div class="unitHierarchyHeader">';
					
					if ($value['DivisionName'] == "Economy")
					{
						if ($value['UnitLevel'] == "Division")
						{
							echo '<img class="unitHierarchy_row_header_arrow" align="center" src="http://vvarmachine.com/uploads/galleries/SC_Button01.png" />';
						}
						else
						{
							echo '<div class="unitHierarchy_row_header_arrow_empty">';
							echo '</div>';
						}
					}
					else if ($value['DivisionName'] == "Military")
					{
						#If This Is Lowest-Level Unit, Don't Display Expand-Arrow
						if ($value['UnitLevel'] != "Squadron" && $value['UnitLevel'] != "Platoon")
						{
							echo '<img class="unitHierarchy_row_header_arrow" align="center" src="http://vvarmachine.com/uploads/galleries/SC_Button01.png" />';
						}
						else
						{
							echo '<div class="unitHierarchy_row_header_arrow_empty">';
							echo '</div>';
						}
					}
					else
					{
						echo '<div class="unitHierarchy_row_header_arrow_empty">';
						echo '</div>';					
					}
					
					echo '<div class="unitHierarchyHeader_mainContainer">';
					
						echo '<div class="unitHierarchyHeader_textContainer">';
							if ($value['IsActive'] == "Active")
							{
								echo '<div class="unitHierarchyHeader_unitName">';
									echo '<a href="http://sc.vvarmachine.com/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitName'].'</a>';
								echo '</div>';				
							}
							else
							{
								echo '<div class="unitHierarchyHeader_unitName inactive">';
									echo '<a href="http://sc.vvarmachine.com/unit/'.$value['UnitID'].'" target="_top">'.$value['UnitName'].'</a>';
								echo '</div>';
							}
							
							#CreatedOn Div for Active Units (not Team,Flight,Division,Fleet)
							if($value['UnitLevel'] != "Division" && 
								$value['UnitLevel'] != "Fleet")
							{
								if ($value['IsActive'] == "Active")
								{
									echo '<div class="unitHierarchyHeader_unitDate">';
										echo 'Established '.$value['UnitCreatedOn'];
									echo '</div>';
								}
							}
							

							#Unit Commander (not applied to Inactive units, or Economy "Group" within the Division
							echo '<div class="unitHierarchyHeader_unitCO">';
								echo '<div class="unitHierarchyHeader_key">';
									if ($value['UnitLevel'] == "Group" && $value['DivisionName'] == "Economy")
									{
										echo '';
									}
									else if ($value['IsActive'] == "Active")
									{
										echo 'Commanding Officer';
									}
								echo '</div>';
								
								echo '<div class="unitHierarchyHeader_value">';
									echo '<div class="unitHierarchyHeader_value_container">';
									if ($value['UnitLevel'] == "Group" && $value['DivisionName'] == "Economy")
									{
										echo '';
									}
									else if ($value['IsActive'] == "Active")
									{										
										if ($value['UnitLeaderID'] != null)
										{
											echo '<img class="unitHierarchyContent_rankTinyImage" align="center" src="http://sc.vvarmachine.com/images/ranks/TS3/'.$value['LeadeRankImage'].'.png" />';
											echo '<a href="http://sc.vvarmachine.com/player/'.$value['UnitLeaderID'].'" target="_top">'.$value['LeaderRankAbbr'].' '.$value['UnitLeaderName'].'</a>';
										}
										else
											echo '- No Leader Assigned -';
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
		$unit_query = "select
							case
								when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
								else u.UnitFullName
							end as UnitName
							,u.UnitCallsign
							,u.UnitDepth
							,u.UnitLevel
							,TRIM(LEADING '\t' from u.UnitDescription) as UnitDescription
							,u.ParentUnitID
							,u.UnitSlogan
							,u.UnitBackgroundImage
							,DATE_FORMAT(DATE(u.CreatedOn),'%d %b %Y') as UnitCreatedOn
							,d.div_name
							,m.mem_id
							,m.mem_callsign as mem_name
							,r.rank_name
							,r.rank_abbr
							,r.rank_image
							,r.rank_tinyImage
							,r.rank_level
							,r.rank_groupName
							,case
								when r2.isPrivate = 0 and r2.role_shortName = '' then r2.role_name
								when r2.isPrivate = 0 and r2.role_shortName != '' then r2.role_shortName
								when r2.role_id is null then 'n/a'
								else '[Redacted]'
							end as role_name
							,DATE_FORMAT(DATE(um.CreatedOn),'%d %b %Y') as MemberAssigned
							,m2.mem_id UnitLeaderID
							,m2.mem_callsign UnitLeaderName
							,r3.rank_abbr UnitLeaderRank
						from projectx_vvarsc2.Units u
						left join projectx_vvarsc2.UnitMembers um
							on um.UnitID = u.UnitID
						left join projectx_vvarsc2.members m
							on m.mem_id = um.MemberID
						left join projectx_vvarsc2.ranks r
							on r.rank_id = m.ranks_rank_id
						left join projectx_vvarsc2.roles r2
							on r2.role_id = um.MemberRoleID
						left join projectx_vvarsc2.divisions d
							on d.div_id = u.DivisionID
						left join projectx_vvarsc2.members m2
							on m2.mem_id = u.UnitLeaderID
						left join projectx_vvarsc2.ranks r3
							on r3.rank_id = m2.ranks_rank_id
						where u.UnitID = $UnitID
						order by
							r.rank_orderby
							,m.mem_callsign";	
		
		$unit_query_result = $connection->query($unit_query);
		
		$display_details = "";
		$display_members = "";
		
		while(($row1 = $unit_query_result->fetch_assoc()) != false) {
		
			$depth = $row1['UnitDepth'];
			$unitLevel = $row1['UnitLevel'];
			$divName = $row1['div_name'];
			$unitName = $row1['UnitName'];
			$unitCallsign = $row1['UnitCallsign'];
			$unitSlogan = $row1['UnitSlogan'];
			$unitDescription = $row1['UnitDescription'];
			$unitBackgroundImage = $row1['UnitBackgroundImage'];
			$unitCreatedOn = $row1['UnitCreatedOn'];
			$unitLeaderID = $row1['UnitLeaderID'];
			$unitLeaderRank = $row1['UnitLeaderRank'];
			$unitLeaderName = $row1['UnitLeaderName'];
			$parentUnitID = $row1['ParentUnitID'];
			$rank_abbr = $row1['rank_abbr'];
			$rank_image = $row1['rank_image'];
			$rank_tinyImage = $row1['rank_tinyImage'];
			$rank_level = $row1['rank_level'];
			$rank_groupName = $row1['rank_groupName'];
			$mem_name = $row1['mem_name'];
			$mem_role = $row1['role_name'];
			$mem_assigned_date = $row1['MemberAssigned'];
			$mem_id = $row1['mem_id'];
			
			if ($unitBackgroundImage == null || $unitBackgroundImage == '')
			{
				$unitBackgroundImage = 'http://vvarmachine.com/uploads/galleries/Gladius_01.jpg';
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
								<img class=\"divinfo_rankImg\" align=\"center\" alt=\"$rank_abbr\" src=\"http://sc.vvarmachine.com/images/ranks/$rank_image.png\" />
							</div>
							<div class=\"shipDetails_ownerInfo_tableRow_memInfoContainer\">
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo1\">
									<a href=\"http://sc.vvarmachine.com/player/$mem_id\" target=\"_top\">$mem_name</a>
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo2\">
									- $rank_groupName
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo3\">
									- $mem_role
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo4\">
									Assigned $mem_assigned_date
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo5\">
									$rank_abbr // $rank_level
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
		<div class=\"shipDetails_info2\" style=\"width: auto\">
			<table class=\"shipDetails_info1_table\">
				<tr class=\"shipDetails_info1_table_row\">
					<td class=\"shipDetails_info1_table_row_td_key\">
						Slogan
					</td>
					<td class=\"shipDetails_info1_table_row_td_value\">
						$unitSlogan
					</td>
				</tr>
				<tr class=\"shipDetails_info1_table_row\">
					<td class=\"shipDetails_info1_table_row_td_key\">
						Radio Callsign
					</td>
					<td class=\"shipDetails_info1_table_row_td_value\">
						<i>$unitCallsign</i>
					</td>
				</tr>
				<tr class=\"shipDetails_info1_table_row\">
					<td class=\"shipDetails_info1_table_row_td_key\">
						Commanding Officer
					</td>
					<td class=\"shipDetails_info1_table_row_td_value\">
						<a style=\"text-decoration: none\" href=\"http://sc.vvarmachine.com/player/$unitLeaderID\" target=\"_top\">$unitLeaderRank $unitLeaderName</a>
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
		</div>
		";		
		
		$displayUnitDescription1 = "";
		$displayUnitDescription2 = "";
		
		if ($depth < 5)
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
							,DATE_FORMAT(DATE(u.CreatedOn),'%d %b %Y') as UnitCreatedOn
							,m.mem_id as UnitLeaderID
							,m.mem_callsign as UnitLeaderName
							,m.rank_tinyImage as LeadeRankImage
							,m.rank_abbr as LeaderRankAbbr
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
						order by
							u.UnitID";	
		
		$units_query_results = $connection->query($units_query);
		
		while(($row = $units_query_results->fetch_assoc()) != false) {
		
			$units[$row['UnitID']] = array(
				'UnitID' => $row['UnitID']
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
			);
		}
		
		
		if (($divName == "Military" && $depth < 4)
			|| ($divName == "Economy" && $depth < 2)
			|| $divName == "Command")
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
						<a href=\"http://sc.vvarmachine.com/units\">&#8672; Back to Parent</a>
					</div>
				</div>
			";		
		}
		else
		{
			$display_selectors .= "
				<div class=\"div_filters_container\">
					<div class=\"div_filters_entry\">
						<a href=\"http://sc.vvarmachine.com/unit/$parentUnitID\">&#8672; Back to Parent</a>
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
				,sed.ship_max_crew
				,us.Purpose
			from projectx_vvarsc2.UnitShips us
			join projectx_vvarsc2.ships s
				on s.ship_id = us.ShipID
			join projectx_vvarsc2.manufacturers m
				on m.manu_id = s.manufacturers_manu_id
			join projectx_vvarsc2.ship_extended_data sed
				on sed.ships_ship_id = s.ship_id
			where us.UnitID = $UnitID
			order by
				m.manu_shortName
				,s.ship_name
		";
		
		$unitShips_query_results = $connection->query($unitShipsQuery);
		
		$displayUnitShips = "";
		
		if($unitLevel == "Squadron")
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
				$ship_max_crew = $row2['ship_max_crew'];
				
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
				
				if($unitLevel == "Squadron")
				{
					$displayUnitShips .= "
					<tr class=\"player_ships_row\">
						<td class=\"player_ships_entry\">
						
							<div class=\"player_ships_shipTitle\">
								<a href=\"http://sc.vvarmachine.com/ship/$ship_id\" >
									<div class=\"player_ships_shipTitleText\">
										$manu_shortName $full_ship_name
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
									<a href=\"http://sc.vvarmachine.com/ship/$ship_id\" >
										<img class=\"player_fleet\" align=\"center\" src=\"http://sc.vvarmachine.com/images/silo_topDown/$ship_silo\" />
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
			if ($unitLevel == "Squadron")
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
		
		if($unitLevel == "Squadron")
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
				<button class=\"adminButton adminButtonEdit\" style=\"float: right\">
					Edit Unit
				</button>
				<br />
			";
		}
		
		$connection->close();
	}
	else
	{
        header("Location: http://sc.vvarmachine.com/units");
    }

?>

<? echo $display_selectors ?>
<h2>
	<? echo $unitName ?>
</h2>
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
<!--
<h3>
	Unit Radio Callsign: <i><? echo $unitCallsign ?></i>
</h3>
-->
		
		<? echo $displayUnitDescription1 ?>
		<? echo nl2br($displayUnitDescriptionContent) ?>
		<? echo $displayUnitDescription2 ?>		
		
		<br />
		<h3>
			Unit Personnel
		</h3>
		<div class="table_header_block">
		</div>
		<div class="unit_details_container">
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
		$(".unitHierarchyHeader_unitName.inactive").parent().parent().parent().children(".unitHierarchy_row_header_arrow").toggleClass('rotate90CW');
		
        $(".unitHierarchy_row_header_arrow").click(function () {
		
			var websiteClass = $('#TEXT');
			var currentContentHeight = websiteClass.height();
			
            $(this).parent().parent().children(".unitHierarchyChildren").slideToggle();
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
	
	$(function() {
		//Edit
		$('.adminButton.adminButtonEdit').click(function() {
			var unitID = <?php echo $UnitID ?>;
			
			//Launch Unit Edit Page
			window.location.href = "http://sc.vvarmachine.com/admin/?page=admin_unit&pid=" + unitID;
			
		});
	});

</script>