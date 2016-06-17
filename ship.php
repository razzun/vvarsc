<?php 
	$ship_id = strip_tags(isset($_GET[pid]) ? $_GET[pid] : '');
	
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
			,GROUP_CONCAT(distinct mem.`mem_id` order by mem.mem_name SEPARATOR '|') as mem_id_info
			,GROUP_CONCAT(distinct mem.`mem_name` order by mem.mem_name SEPARATOR '|') as mem_name_info
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
		where s.ship_id = $ship_id";
    	
        $ship_query_results = $connection->query($ship_query);
		
		$row1 = $ship_query_results->fetch_assoc();
		
		//Title
		$ship_name = $row1['ship_name'];
		$ship_classification = $row1['ship_classification'];
		$manu_smallImage = $row1['manu_smallImage'];
		
		//Info1
		$ship_role_primary = $row1['ship_role_primary'];
		$ship_role_secondary = $row1['ship_role_secondary'];
		$ship_desc = $row1['ship_desc'];
		$ship_image_link = $row1['ship_image_link'];
		$manu_name = $row1['manu_name'];
		$mem_id_info = $row1['mem_id_info'];
		$mem_name_info = $row1['mem_name_info'];
		$ship_model_designation = $row1['ship_model_designation'];
		
		//Info2
		$ship_length = number_format($row1['ship_length']);
		$ship_width = number_format($row1['ship_width']);
		$ship_height = number_format($row1['ship_height']);
		$ship_mass = number_format($row1['ship_mass']);
		$ship_cargo_capacity = number_format($row1['ship_cargo_capacity']);
		$ship_max_crew = $row1['ship_max_crew'];
		$ship_silo = $row1['ship_silo'];
		
		//Info 3
		$ship_max_powerPlant = $row1['ship_max_powerPlant'];
		$ship_max_mainThruster = $row1['ship_max_mainThruster'];
		$ship_max_maneuveringThruster = $row1['ship_max_maneuveringThruster'];
		$ship_max_shield = $row1['ship_max_shield'];
		
		//Info 4
		$ship_hardpoint_fixed = $row1['ship_hardpoint_fixed'];
		$ship_hardpoint_gimbal = $row1['ship_hardpoint_gimbal'];
		$ship_hardpoint_pylon = $row1['ship_hardpoint_pylon'];
		$ship_hardpoint_unmannedTurret = $row1['ship_hardpoint_unmannedTurret'];
		$ship_hardpoint_mannedTurret = $row1['ship_hardpoint_mannedTurret'];
		$ship_hardpoint_class6 = $row1['ship_hardpoint_class6'];
		$ship_hardpoint_class7 = $row1['ship_hardpoint_class7'];
		$ship_hardpoint_class8 = $row1['ship_hardpoint_class8'];
		$ship_hardpoint_class9 = $row1['ship_hardpoint_class9'];
		
		//Info 5
		$ship_link = $row1['ship_link'];
		$ship_price = $row1['ship_price'];
		$ship_brochure = $row1['ship_brochure_link'];
		$ship_status = $row1['ship_status'];
		
		
		$mem_id_array = explode('|', $mem_id_info);
		$mem_name_array = explode('|', $mem_name_info);
		
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
				<img class=\"shipyard_shipTitle_manuImage\" align=\"center\" src=\"$manu_smallImage\" />	
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
				<img class=\"shipDetails_info1_shipImage\" align=\"center\" alt=\"$ship_name\" src=\"$ship_image_link\" />
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
				<img class=\"shipDetails_info2_shipImage\" align=\"center\" alt=\"$ship_name\" src=\"http://sc.vvarmachine.com/images/silo_topDown/$ship_silo\" />
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
								Max Crew
							</td>
							<td class=\"shipDetails_info2_table_row_td_value\">
								$ship_max_crew Persons
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
								Max Power Plant
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_max_powerPlant
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
								 Maneuvering Thrusters
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_max_maneuveringThruster
							</td>
						</tr>
						<tr class=\"shipDetails_info3_table_row\">
							<td class=\"shipDetails_info3_table_row_td_key\">
								Max Shield
							</td>
							<td class=\"shipDetails_info3_table_row_td_value\">
								$ship_max_shield
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
								Fixed Mount
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_hardpoint_fixed
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Gimbal Mount
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_hardpoint_gimbal
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								 Pylon Mount (Missile)
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_hardpoint_pylon
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Un-Manned Turret
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_hardpoint_unmannedTurret
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Manned Turret
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_hardpoint_mannedTurret
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Class-6 Hardpoint
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_hardpoint_class6
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Class-7 Hardpoint
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_hardpoint_class7
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Class-8 Hardpoint
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_hardpoint_class8
							</td>
						</tr>
						<tr class=\"shipDetails_info4_table_row\">
							<td class=\"shipDetails_info4_table_row_td_key\">
								Class-9 Hardpoint
							</td>
							<td class=\"shipDetails_info4_table_row_td_value\">
								$ship_hardpoint_class9
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
			,m.mem_name
			,m.mem_avatar_link
			,DATE_FORMAT(DATE(m.CreatedOn),'%d %b %Y') as mem_CreatedOn
			,r.rank_abbr
			,r.rank_image
			,r.rank_tinyImage
			,r.rank_level
			,r.rank_groupName
			,d.div_name
			,case
				when r2.isPrivate = 0 then IFNULL(r2.role_shortName,r2.role_name)
				when r2.role_id is null then 'n/a'
				else '[Redacted]'
			end as role_name
		from projectx_vvarsc2.ships s
		left join projectx_vvarsc2.ships_has_members shm
			on shm.ships_ship_id = s.ship_id
		left join projectx_vvarsc2.members m
			on shm.members_mem_id = m.mem_id
			and m.mem_sc = 1
		left join projectx_vvarsc2.ranks r
			on r.rank_id = m.ranks_rank_id
		left join projectx_vvarsc2.divisions d
			on d.div_id = m.divisions_div_id
		left join projectx_vvarsc2.UnitMembers um
			on um.MemberID = m.mem_id
		left join projectx_vvarsc2.roles r2
			on r2.role_id = um.MemberRoleID
		where s.ship_id = $ship_id
		order by
			r.rank_orderby
			,m.mem_name";
		
        $owners_query_results = $connection->query($owners_query);
		
		while (($row2 = $owners_query_results->fetch_assoc()) != false)
		{
			$div_name = $row2['div_name'];
			$rank_abbr = $row2['rank_abbr'];
			$rank_image = $row2['rank_image'];
			$rank_tinyImage = $row2['rank_tinyImage'];
			$rank_level = $row2['rank_level'];
			$rank_groupName = $row2['rank_groupName'];
			$mem_id = $row2['mem_id'];
			$mem_name = $row2['mem_name'];
			$mem_avatar = $row2['mem_avatar_link'];
			$mem_role = $row2['role_name'];
			$mem_CreatedOn = $row2['mem_CreatedOn'];
			
			if ($div_name == "n/a") {
				$div_name = "No Division Assigned";
			}
			else {
				$div_name .= " Division";
			}
			
			if ($mem_role == "n/a") {
				$mem_role = "- No Assigned Role -";
			}
			
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
							<img class=\"divinfo_rankImg\" align=\"center\" alt=\"$rank_abbr\" src=\"http://sc.vvarmachine.com/images/ranks/$rank_image.png\" />
						</div>
						<div class=\"shipDetails_ownerInfo_tableRow_memInfoContainer\">
							<div class=\"shipDetails_ownerInfo_tableRow_memInfo1\">
								<a href=\"http://sc.vvarmachine.com/player/$mem_id\" target=\"_top\">$mem_name</a>
							</div>
							<div class=\"shipDetails_ownerInfo_tableRow_memInfo2\">
								- $rank_groupName // $mem_role
							</div>
							<div class=\"shipDetails_ownerInfo_tableRow_memInfo3\">
								- $div_name
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
	
		$connection->close();
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/?page=shipyard");
	}
?>

<? echo $display_ship_title; ?>
<div id="TEXT">
	<div class="shipDetails_main_container">
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
						Structural Stats
					</div>
					<? echo $display_ship_info2; ?>
				</div>
				<div class="shipDetails_info3"> <!--Technical Stats - Powerplant, Engines, Shields-->
					<div class="shipDetails_info_title">
						Technical Stats - Propulsion & Shields
					</div>
					<? echo $display_ship_info3; ?>
				</div> 
			</div>
			<div class="shipDetails_info3_container">  <!--Technical Stats - Weapons/Hardpoints-->
				<div class="table_header_block2">
				</div>
				<div class="shipDetails_info4">
					<div class="shipDetails_info_title">
						Technical Stats - Weapons
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
							<img class="shipyard_shipTitle_manuImage" align="center" src="http://sc.vvarmachine.com/images/misc/SC_logo_yellow.png" />
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
		<div class="shipDetails_rightInfo_Container">
			<div class="table_header_block">
			</div>
			<div class="shipDetails_info_title">
				Owners
			</div>
			<div class="shipDetails_rightInfo_ownerInfo_Container">
				<table class="shipDetails_ownerInfo_table">
					<?echo $display_owners; ?>
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
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