<?php include_once('functions/function_auth_user.php'); ?>

<?php 
	$player_id = strip_tags(isset($_GET[pid]) ? $_GET[pid] : '');
	
	$display_player_ships;
	$display_player_qualifications;
	$ship_count;
    
    if(is_numeric($player_id)) {
    	$play_query = "SELECT
			members.mem_id
			,members.mem_id as player_id
    	    ,members.mem_name
    		,members.mem_callsign
    		,members.mem_avatar_link
    		,ranks.rank_groupName
    		,ranks.rank_image
			,ranks.rank_level
			,ranks.rank_name
    		,d.div_name
			,case
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
    		$rank_groupName = $row['rank_groupName'];
    		$rank_image = $row['rank_image'];
			$rank_level = $row['rank_level'];
			$rank_name = $row['rank_name'];
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
    		/*$shm_package = $row['shm_package'];*/
			$shm_createdOn = $row['CreatedOn'];
			$shm_modifiedOn = $row['ModifiedOn'];
			$shm_ship_vvarID = $row['ship_vvarID'];
			$temp_player_id = $row['player_id'];
			$ship_model_designation = $row['ship_model_designation'];
			$ship_model_visible = $row['ship_model_visible'];
			$UnitID = $row['UnitID'];
			$UnitName = $row['UnitName'];
			
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
			
    		if ($shm_lti == 1) {
    			$shm_lti = "Yes";
    		} else {
    			$shm_lti = "No";
    		}
    		
			/*
    		if ($shm_package == 1) {
    			$shm_package = "Yes";
    		} else {
    			$shm_package = "No";
    		}
			*/
			
			$total_ship_value += $ship_cost;
    		
    		if($manu_name == NULL & $ship_name == NULL) {
    		    $display_player_ships = "<tr><td><em>- No Ships Currently Registered -</em></td></tr>";
    		    $ship_count = 0;
    		} else {		
					
          		$display_player_ships .= "
				<tr class=\"player_ships_row\">
					<td class=\"player_ships_entry\">
					
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
									<!--
									<tr>
										<td class=\"tooltip_shipTable2_key\">
											<div class=\"tooltip_shipTable2_key_inner\">
											Package
											</div>
										</td>
										<td class=\"tooltip_shipTable2_value\">
											<div class=\"tooltip_shipTable2_value_inner\">
											$shm_package
											</div>
										</td>
									</tr>
									-->
									<tr>
										<td class=\"tooltip_shipTable2_key\">
											<div class=\"tooltip_shipTable2_key_inner\">
											LTI
											</div>
										</td>
										<td class=\"tooltip_shipTable2_value\">
											<div class=\"tooltip_shipTable2_value_inner\">
											$shm_lti
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
		
    	$connection->close();
    } else {
        header("Location: http://sc.vvarmachine.com/?page=members");
    }
?>

<h2>Star Citizen Player Profile</h2>
  <div id="TEXT">
	<div class="player_topTable_Container">
		<div class="table_header_block">
		</div>
		<div class="play">
			<div class="pavatar" height="200" width="200">		
				<img height="200" width="200" alt="<? echo $mem_name; ?>" src="http://sc.vvarmachine.com/images/player_avatars/<? echo $mem_avatar_link; ?>.png" />
			</div>
			<div class="p_info" valign="top" align="left">
				<div class="p_section_header">
					Citizen Dossier - <? echo $mem_name; ?>
				</div>
				<div class="p_rank" align="left" valign="top">
					<div class = "p_rankimage_container">
						<img class = "p_rankimage" align="left" alt="<? echo $rank_groupName; ?>" src="http://sc.vvarmachine.com/images/ranks/<? echo $rank_image; ?>.png" />
					</div>
					<div class = "p_rankDetails">
						<div class ="p_rankname">
							<? echo $rank_groupName; ?>
						</div>
						<!--
						<div class ="p_rank_div_name">
							<? echo $div_name; ?>
						</div>
						-->
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
	</div>
	<div class="player_shipsTable_Container">
		<div class="p_section_header">
			Citizen Fleet Information
		</div>
		<div class="player_ships_container">
			<table id="player_ships">
				<? echo $display_player_ships; ?>
			</table>
		<div>
	</div>
  </div>
  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.jScale.js"></script>

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

<!--Script to Show/Hide The Right-Hand-Detail Elements-->
<script>

	$(document).ready(function() {
		$('.p_rankimage').hover(function() {
			$('.p_rankExtendedData_rank_level').addClass("opaque");
			$('.p_rankExtendedData_rank_name').addClass("opaque");
		},
		function() {
			$('.p_rankExtendedData_rank_level').removeClass("opaque");
			$('.p_rankExtendedData_rank_name').removeClass("opaque");
		});
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