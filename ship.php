<?php include_once('functions/function_auth_user.php'); ?>

<?php 
	$ship_id = strip_tags(isset($_GET[pid]) ? $_GET[pid] : '');
	$infoSecLevelID = $_SESSION['sess_infoseclevel'];
	
	$display_ship_title;
	$display_ship_info1;
	$display_ship_info2;
	$display_ship_info3;
	$display_ship_info4;
    
    if(is_numeric($ship_id))
	{
    	$ship_query = "
		select
			s.ship_id
			,s.ship_name
			,s.ship_pname
			,s.ship_model_designation
			,s.ship_role_primary
			,s.ship_role_secondary
			,REPLACE(s.ship_classification,'_',' ') as `ship_classification`
			,s.ship_link
			,s.ship_image_link
			,s.ship_brochure_link
			,s.ship_silo
			,s.ship_desc
			,s.ship_price
			,s.ship_status
			,sed.ship_length
			,sed.ship_width
			,sed.ship_height
			,sed.ship_mass
			,sed.ship_cargo_capacity
			,sed.ship_max_crew
			,sed.ship_max_powerPlant
			,sed.ship_max_mainThruster
			,sed.ship_max_maneuveringThruster
			,sed.ship_max_shield
			,sed.ship_hardpoint_fixed
			,sed.ship_hardpoint_gimbal
			,sed.ship_hardpoint_pylon
			,sed.ship_hardpoint_unmannedTurret
			,sed.ship_hardpoint_mannedTurret
			,sed.ship_hardpoint_class6
			,sed.ship_hardpoint_class7
			,sed.ship_hardpoint_class8
			,sed.ship_hardpoint_class9
			,m.manu_name
			,m.manu_shortName
			,m.manu_smallImage
            ,ss.Focus
            ,ss.ProductionState
            ,ss.Length
            ,ss.Beam
            ,ss.Height
            ,ss.Size
            ,ss.Mass
            ,ss.CargoCapacity
            ,ss.MinCrew
            ,ss.MaxCrew
            ,ss.Radar
            ,ss.Computers
            ,ss.FuelIntake
            ,ss.FuelTank
            ,ss.QuantumDrive
            ,ss.JumpModules
            ,ss.QuantumFuelTank
            ,ss.MainThrusters
            ,ss.ManueveringThrusters
            ,ss.PowerPlants
            ,ss.Coolers
            ,ss.ShieldGenerators
            ,ss.Weapons
            ,ss.Turrets
            ,ss.Missiles
            ,ss.UtilityItems
            ,ss.Link
		from projectx_vvarsc2.ships s
		join projectx_vvarsc2.manufacturers m
			on m.manu_id = s.manufacturers_manu_id
		left join projectx_vvarsc2.ship_extended_data sed
			on sed.ships_ship_id = s.ship_id
		left join projectx_vvarsc2.ships_has_members shm
			on shm.ships_ship_id = s.ship_id
		left join projectx_vvarsc2.members mem
			on shm.members_mem_id = mem.mem_id
			and mem.mem_sc = 1
		left join projectx_vvarsc2.ShipStats_v2 ss
			on ss.ShipID = s.ship_id
		where s.ship_id = $ship_id";
    	
        $ship_query_results = $connection->query($ship_query);
		
		$row1 = $ship_query_results->fetch_assoc();
		
		//Title
		$ship_name = $row1['ship_name'];
		$ship_classification = $row1['Focus'];
		$manu_smallImage = $row1['manu_smallImage'];
		
		//Info1 - General
		$ship_role_primary = $row1['ship_role_primary'];
		$ship_role_secondary = $row1['ship_role_secondary'];
		$ship_desc = $row1['ship_desc'];
		$ship_image_link = $row1['ship_image_link'];
		$manu_name = $row1['manu_name'];
		$ship_model_designation = $row1['ship_model_designation'];
		
		//Info2 - Specifications
		$ship_length = $row1['Length'];
		$ship_width = $row1['Beam'];
		$ship_height = $row1['Height'];
		$ship_mass = number_format($row1['Mass']);
		$ship_cargo_capacity = number_format($row1['CargoCapacity']);
		$ship_min_crew = $row1['MinCrew'];
		$ship_max_crew = $row1['MaxCrew'];
		$ship_silo = $row1['ship_silo'];
		
		//Info 3 - Propulsion $ Thrusters
		$ship_fuelIntake = $row1['FuelIntake'];
		$ship_fuelTank = $row1['FuelTank'];
		$ship_quantumDrive = $row1['QuantumDrive'];
		$ship_jumpModules = $row1['JumpModules'];
		$ship_quantumFuelTank = $row1['QuantumFuelTank'];
		$ship_max_mainThruster = $row1['MainThrusters'];
		$ship_max_maneuveringThruster = $row1['ManueveringThrusters'];
		
		//Info 4 - Avionics, Systems, Weapons
		$ship_radar = $row1['Radar'];
		$ship_computers = $row1['Computers'];
		$ship_powerPlants = $row1['PowerPlants'];
		$ship_coolers = $row1['Coolers'];
		$ship_shieldGenerators = $row1['ShieldGenerators'];
		$ship_weapons = $row1['Weapons'];
		$ship_turrets = $row1['Turrets'];
		$ship_missiles = $row1['Missiles'];
		$ship_utility = $row1['UtilityItems'];
		
		//Info 5
		$ship_link = $row1['Link'];
		$ship_price = $row1['ship_price'];
		$ship_brochure = $row1['ship_brochure_link'];
		$ship_status = $row1['ProductionState'];
		
		$display_ship_title .= "
			<div class=\"shipDetails_shipTitle_container\">
				<div class=\"corner corner-top-left\">
				</div>
				<div class=\"corner corner-top-right\">
				</div>
				<div class=\"corner corner-bottom-left\">
				</div>
				<div class=\"corner corner-bottom-right\">
				</div>
				<img class=\"shipyard_shipTitle_manuImage\" align=\"center\" src=\"$link_base/images/manu_logo/$manu_smallImage\" />	
				<div class=\"shipDetails_shipTitle_text\">
					$ship_name
				</div>
			</div>
			<div class=\"shipDetails_shipTitle2_container\">	
				<div class=\"shipDetails_shipTitle2_text_key\">
					Ship Classification:
				</div>
				<div class=\"shipDetails_shipTitle2_text_value\">
					$ship_classification
				</div>				
			</div>			
		";
    	
		$display_ship_info1 .= "
			<div class=\"shipDetails_info1_content\">
				<img class=\"shipDetails_info1_shipImage\" align=\"center\" src=\"$link_base/images/ship_large/$ship_image_link\" />
				<div class=\"shipDetails_info1_content_main\">
					<table class=\"shipDetails_info1_table\">
						<tr class=\"shipDetails_info1_table_row\">
							<td class=\"shipDetails_info1_table_row_td_key\">
								Manuafacturer
							</td>
							<td class=\"shipDetails_info1_table_row_td_value\">
								$manu_name
							</td>
						</tr>
						<tr class=\"shipDetails_info1_table_row\">
							<td class=\"shipDetails_info1_table_row_td_key\">
								Primary Role
							</td>
							<td class=\"shipDetails_info1_table_row_td_value\">
								$ship_role_primary
							</td>
						</tr>
						<tr class=\"shipDetails_info1_table_row\">
							<td class=\"shipDetails_info1_table_row_td_key\">
								Secondary Role
							</td>
							<td class=\"shipDetails_info1_table_row_td_value\">
								$ship_role_secondary
							</td>
						</tr>
						";
						
						if ($ship_model_designation != NULL) {
							$display_ship_info1 .="
								<tr class=\"shipDetails_info1_table_row\">
									<td class=\"shipDetails_info1_table_row_td_key\">
										Model Designation
									</td>
									<td class=\"shipDetails_info1_table_row_td_value\">
										$ship_model_designation
									</td>
								</tr>
							";
						}
						
		$display_ship_info1 .= "
					</table>
					<div class=\"shipDetails_info1_table_ship_desc\">
						<div class=\"corner corner-top-left\">
						</div>
						<div class=\"corner corner-top-right\">
						</div>
						<div class=\"corner corner-bottom-left\">
						</div>
						<div class=\"corner corner-bottom-right\">
						</div>
						$ship_desc
					</div>
				</div>
			</div>
		";
		
		$display_ship_info2 .="
			<div class=\"shipDetails_info2_content\">
				<!--
				<img class=\"shipDetails_info2_shipImage\" align=\"center\" alt=\"$ship_name\" src=\"$link_base/images/silo_topDown/$ship_silo\" />
				-->
				<div class=\"shipDetails_info2_content_main\">
					<table class=\"shipDetails_info2_table\">
						<tr class=\"shipDetails_info2_table_row\">
							<td class=\"shipDetails_info2_table_row_td_key\">
								Length
							</td>
							<td class=\"shipDetails_info2_table_row_td_value\">
								$ship_length m
							</td>
						</tr>
						<tr class=\"shipDetails_info2_table_row\">
							<td class=\"shipDetails_info2_table_row_td_key\">
								Width
							</td>
							<td class=\"shipDetails_info2_table_row_td_value\">
								$ship_width m
							</td>
						</tr>
						<tr class=\"shipDetails_info2_table_row\">
							<td class=\"shipDetails_info2_table_row_td_key\">
								Height
							</td>
							<td class=\"shipDetails_info2_table_row_td_value\">
								$ship_height m
							</td>
						</tr>
						<tr class=\"shipDetails_info2_table_row\">
							<td class=\"shipDetails_info2_table_row_td_key\">
								Mass
							</td>
							<td class=\"shipDetails_info2_table_row_td_value\">
								$ship_mass Kg
							</td>
						</tr>
						<tr class=\"shipDetails_info2_table_row\">
							<td class=\"shipDetails_info2_table_row_td_key\">
								Cargo Capacity
							</td>
							<td class=\"shipDetails_info2_table_row_td_value\">
								$ship_cargo_capacity SCU
							</td>
						</tr>
						<tr class=\"shipDetails_info2_table_row\">
							<td class=\"shipDetails_info2_table_row_td_key\">
								Min Crew
							</td>
							<td class=\"shipDetails_info2_table_row_td_value\">
								$ship_min_crew
							</td>
						</tr>
						<tr class=\"shipDetails_info2_table_row\">
							<td class=\"shipDetails_info2_table_row_td_key\">
								Max Crew
							</td>
							<td class=\"shipDetails_info2_table_row_td_value\">
								$ship_max_crew
							</td>
						</tr>
					</table>
				</div>
			</div>
		";
		
		$display_ship_info3 .="
			<div class=\"shipDetails_info3_content\">
				<div class=\"shipDetails_info3_content_main\">
					<table class=\"shipDetails_info3_table\">
						<tr class=\"shipDetails_info3_table_row\">
							<td class=\"shipDetails_info3_table_row_td_key\">
								Fuel Intake
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_fuelIntake
							</td>
						</tr>
						<tr class=\"shipDetails_info3_table_row\">
							<td class=\"shipDetails_info3_table_row_td_key\">
								Fuel Tank
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_fuelTank
							</td>
						</tr>
						<tr class=\"shipDetails_info3_table_row\">
							<td class=\"shipDetails_info3_table_row_td_key\">
								 Quantum Drive
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_quantumDrive
							</td>
						</tr>
						<tr class=\"shipDetails_info3_table_row\">
							<td class=\"shipDetails_info3_table_row_td_key\">
								Quantum Fuel Tank
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_quantumFuelTank
							</td>
						</tr>
						<tr class=\"shipDetails_info3_table_row\">
							<td class=\"shipDetails_info3_table_row_td_key\">
								Jump Module
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_jumpModules
							</td>
						</tr>
						<tr class=\"shipDetails_info3_table_row\">
							<td class=\"shipDetails_info3_table_row_td_key\">
								Main Thrusters
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_max_mainThruster
							</td>
						</tr>
						<tr class=\"shipDetails_info3_table_row\">
							<td class=\"shipDetails_info3_table_row_td_key\">
								Manuevering Thrusters
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_max_maneuveringThruster
							</td>
						</tr>
					</table>
				</div>
			</div>
		";	
		
		$display_ship_info4 .= "
			<div class=\"shipDetails_info4_content\">
				<div class=\"shipDetails_info4_content_main\">
					<table class=\"shipDetails_info4_table\">
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Radar
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_radar
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Computers
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_computers
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Power Plants
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_powerPlants
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Coolers
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_coolers
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Shield Generators
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_shieldGenerators
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Weapons
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_weapons
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Turrets
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_turrets
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								 Missiles
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_missiles
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Utility Items
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_utility
							</td>
						</tr>
					</table>
				</div>
			</div>		
		";
		
		$display_ship_info5 .="
			<div class=\"shipDetails_info5_content_main\">
				<table class=\"shipDetails_info5_table\">
					<tr class=\"shipDetails_info5_table_row\">
						<td class=\"shipDetails_info5_table_row_td_key\">
							<a href=$ship_link target=\"_blank\">RSI Website Ship Link</a>
						</td>
						<td class=\"shipDetails_info5_table_row_td_value\">
							&nbsp;
						</td>
					</tr>";
					
			if ($ship_brochure != NULL) {
				$display_ship_info5 .="
					<tr class=\"shipDetails_info5_table_row\">
						<td class=\"shipDetails_info5_table_row_td_key\">
							<a href=$ship_brochure target=\"_blank\">Download Ship Brochure</a>
						</td>
						<td class=\"shipDetails_info5_table_row_td_value\">
							&nbsp;
						</td>
					</tr>
				";
			}
					
		$display_ship_info5 .="
					<tr class=\"shipDetails_info5_table_row\">
						<td class=\"shipDetails_info5_table_row_td_key\">
							Purchase Price (Standalone)
						</td>
						<td class=\"shipDetails_info5_table_row_td_value\">
							$$ship_price
						</td>
					</tr>
					<tr class=\"shipDetails_info5_table_row\">
						<td class=\"shipDetails_info5_table_row_td_key\">
							In-Game Ship Status
						</td>
						<td class=\"shipDetails_info5_table_row_td_value\">
							$ship_status
						</td>
					</tr>
				</table>
			</div>
		";
		
		$owners_query ="
			select distinct
				m.mem_id
				,m.mem_callsign as mem_name
				,m.mem_avatar_link
				,DATE_FORMAT(DATE(m.CreatedOn),'%d %b %Y') as mem_CreatedOn
				,r.rank_abbr
				,r.rank_image
				,r.rank_tinyImage
				,r.rank_level
				,r.rank_name
				,r.rank_groupName
				,d.div_name
				,(
					select
						r2.role_name
					from projectx_vvarsc2.UnitMembers um
					join projectx_vvarsc2.roles r2
						on r2.role_id = um.MemberRoleID
						and r2.isPrivate = 0
					where um.MemberID = m.mem_id
					order by
						r2.role_orderby
					limit 1
				) as role_name
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
					where m2.mem_id = m.mem_id
					order by
						r2.role_orderby
				limit 1
				) as FullTitle	
			from projectx_vvarsc2.ships s
			join projectx_vvarsc2.ships_has_members shm
				on shm.ships_ship_id = s.ship_id
			join projectx_vvarsc2.members m
				on shm.members_mem_id = m.mem_id
				and m.mem_sc = 1
			left join projectx_vvarsc2.ranks r
				on r.rank_id = m.ranks_rank_id
			left join projectx_vvarsc2.divisions d
				on d.div_id = m.divisions_div_id
			where s.ship_id = $ship_id
			order by
				r.rank_orderby
				,m.mem_name
		";
	
        $owners_query_results = $connection->query($owners_query);
		$display_owners = "";
		
		if ($infoSecLevelID > 1)
		{
			$display_owners .= "
				<div class=\"shipDetails_rightInfo_Container\">
					<div class=\"shipDetails_rightInfo_ownerInfo_Container\">
						<div class=\"shipDetails_info_title\">
							Owners
						</div>
						<table class=\"shipDetails_ownerInfo_table\">
			";
		}
	
		
		while (($row2 = $owners_query_results->fetch_assoc()) != false)
		{
			$div_name = $row2['div_name'];
			$rank_abbr = $row2['rank_abbr'];
			$rank_image = $row2['rank_image'];
			$rank_tinyImage = $row2['rank_tinyImage'];
			$rank_level = $row2['rank_level'];
			$rank_name = $row2['rank_name'];
			$rank_groupName = $row2['rank_groupName'];
			$mem_id = $row2['mem_id'];
			$mem_name = $row2['mem_name'];
			$mem_avatar = $row2['mem_avatar_link'];
			$mem_role = $row2['role_name'];
			$mem_CreatedOn = $row2['mem_CreatedOn'];
			$full_title = $row2['FullTitle'];
			
			if ($div_name == "n/a") {
				$div_name = "No Division Assigned";
			}
			else {
				$div_name .= " Division";
			}
			
			if ($mem_role == null || $mem_role == "") {
				$mem_role = "- No Role Assigned -";
			}
			
			if ($infoSecLevelID > 1)
			{
				$display_owners .="
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
								<img class=\"divinfo_rankImg\" align=\"center\" alt=\"$rank_abbr\" src=\"$link_base/images/ranks/$rank_image.png\" />
							</div>
							<div class=\"shipDetails_ownerInfo_tableRow_memInfoContainer\">
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo1\">
									<a href=\"$link_base/player/$mem_id\" target=\"_top\">$full_title</a>
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo3\">
									$mem_role
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo4\">
									Enlisted $mem_CreatedOn
								</div>
								<div class=\"shipDetails_ownerInfo_tableRow_memInfo5\">
									$rank_abbr // $rank_level
								</div>
							</div>
						</td>
					</tr>
				";
			}
		}
		if ($infoSecLevelID > 1)
		{
			$display_owners .= "
						</table>
					</div>
				</div>
			";
		}
	
		$connection->close();
	}
	else
	{
		header("Location: ".$link_base."/?page=shipyard");
	}
?>

<div id="TEXT">
	<div class="shipDetails_main_container">
		<? echo $display_ship_title; ?>
		<br />
		<div class="shipDetails_info1_container"> <!--Main Image and Ship Description-->
			<div class="table_header_block">
			</div>
			<div class="shipDetails_info1">
				<div class="shipDetails_info_title">
					Overview & Description
				</div>
				<? echo $display_ship_info1; ?>
			</div>
		</div>
		<div class="shipDetails_lowerInfo_Container1">
			<div class="shipDetails_info2_container"> 
				<div class="table_header_block2">
				</div>
				<div class="shipDetails_info2"> <!--Structural Stats-->
					<div class="shipDetails_info_title">
						Specifications
					</div>
					<? echo $display_ship_info2; ?>
				</div>
				<div class="shipDetails_info3"> <!--Technical Stats - Powerplant, Engines, Shields-->
					<div class="shipDetails_info_title">
						Technical Stats - Propulsion & Thrusters
					</div>
					<? echo $display_ship_info3; ?>
				</div> 
			</div>
			<div class="shipDetails_info3_container">  <!--Technical Stats - Weapons/Hardpoints-->
				<div class="table_header_block2">
				</div>
				<div class="shipDetails_info4">
					<div class="shipDetails_info_title">
						Technical Stats - Avonics, Systems, & Weapons
					</div>
					<? echo $display_ship_info4; ?>
				</div>
			</div>
			<div class="shipDetails_info5_container">
				<div class="table_header_block2">
				</div>
				<div class="shipDetails_info5"> <!--Official Links and Info-->
					<div class="shipDetails_info_title">
						Official Links and Info
					</div>
					<div class="shipDetails_info5_content">
						<? echo $display_ship_info5; ?>
						<div class="shipDetails_info5_manuImage_container">
							<img class="shipyard_shipTitle_manuImage" align="center" src="<? $link_base; ?>/images/misc/SC_logo_yellow.png" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--Similar Ships-->
		<!--
		<div class="shipDetails_lowerInfo_Container2">
			<div class="corner2 corner-top-left">
			</div>
			<div class="corner2 corner-top-right">
			</div>
			<div class="corner2 corner-bottom-left">
			</div>
			<div class="corner2 corner-bottom-right">
			</div>
			<div class="shipDetails_similarShips_Container">
				<div class="shipDetails_info_title">
					Similar Ships
				</div>
				stuff goes here!
			</div>
		</div>
		-->
		<? echo $display_owners; ?>
	</div>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>

<script type="text/javascript" src="/js/jquery.jScale.js">
</script>

<!--Script to Re-Size Images-->
<script>
	$(document).ready(function() {
	
		var imageClass = $('.shipDetails_info1_shipImage');
	
		if((screen.width < 900)) {
			imageClass.jScale({w: '60%'});
			imageClass.css({
					"margin": '0px'
				});
		}	
		else if((screen.width < 1200)){
			imageClass.jScale({w: '80%'});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '100%'});
			imageClass.css({
					"margin": '2px'
				});		
		}
	});
	
	$(window).resize(function () {
	
		var imageClass = $('.shipDetails_info1_shipImage');
	
		if((screen.width < 900)) {
			imageClass.jScale({w: '60%'});
			imageClass.css({
					"margin": '0px'
				});
		}	
		else if((screen.width < 1200)){
			imageClass.jScale({w: '80%'});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '100%'});
			imageClass.css({
					"margin": '2px'
				});		
		}
	});	
</script>