<?php include_once('functions/function_auth_user.php'); ?>

<?
	$infoSecLevelID = $_SESSION['sess_infoseclevel'];
	$display_fleet = "";
	
	if ($infoSecLevelID < 2)
	{
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=/login.php?err=5">';//This causes the browser to open the new page after 0 seconds, i.e immediately.
	}
	else
	{
		$fleet_query = "SELECT
							s.ship_pName
							,shm.RowID as ship_vvarID_info
							,s.ship_id as ship_id_info
							,s.ship_name as ship_name_info
							,COALESCE(s.ship_model_designation,'-') as ship_designation_info
							,m.mem_id as mem_id_info
							,m.mem_callsign as mem_name_info
							,DATE_FORMAT(DATE(shm.ModifiedOn),'%d %b %Y') as shm_modifiedOn_info
							,s.ship_silo as ship_silo_info
							,s.ship_role_primary as ship_primary_info
							,man.manu_name as ship_manu_info
							,man.manu_shortName as ship_manu_short_info
							,d.div_name as ship_division_info
							,r.rank_name as ship_rank_info
							,sed.ship_length as ship_length_info
							,sed.ship_width as ship_width_info
							,sed.ship_height as ship_height_info
							,sed.ship_mass as ship_mass_info
							,sed.ship_cargo_capacity as ship_cargo_info
							,sed.ship_max_crew  as ship_crew_info
							,sed.ship_max_powerPlant as ship_powerPlant_info
							,sed.ship_max_mainThruster as ship_mainThruster_info
							,sed.ship_max_maneuveringThruster as ship_maneuveringThruster_info
							,sed.ship_max_shield as ship_shield_info
						FROM projectx_vvarsc2.ships s
						JOIN projectx_vvarsc2.manufacturers man
							on man.manu_id = s.manufacturers_manu_id
						RIGHT JOIN projectx_vvarsc2.ships_has_members shm
							ON shm.ships_ship_id = s.ship_id
						LEFT JOIN projectx_vvarsc2.ship_extended_data sed
							ON sed.ships_ship_id = s.ship_id
						JOIN projectx_vvarsc2.members m
							ON shm.members_mem_id = m.mem_id
							AND m.mem_sc = 1
						JOIN projectx_vvarsc2.divisions d
							ON d.div_id = m.divisions_div_id
						JOIN projectx_vvarsc2.ranks r
							ON r.rank_id = m.ranks_rank_id
						ORDER BY
							s.ship_pname
							,s.ship_name
							,shm.RowID";
		
		$fleet_query_results = $connection->query($fleet_query);
			
		$previousGroup = "";
		$currentGroup = "";
		
		while (($row = $fleet_query_results->fetch_assoc()) != false) {
			$ship_pname = $row['ship_pName'];
			$ship_silo_info = $row['ship_silo_info'];
			$mem_id_info = $row['mem_id_info'];
			$mem_name_info = $row['mem_name_info'];
			$modifiedOn_info = $row['shm_modifiedOn_info'];
			$ship_name_info = $row['ship_name_info'];
			$ship_designation_info = $row['ship_designation_info'];
			$ship_primary_info = $row['ship_primary_info'];
			$ship_manu_info = $row['ship_manu_info'];
			$ship_division_info = $row['ship_division_info'];
			$ship_rank_info = $row['ship_rank_info'];
			$ship_vvarID_info = $row['ship_vvarID_info'];
			$ship_id_info = $row['ship_id_info'];
			$ship_manu_short_info = $row['ship_manu_short_info'];
			
			$ship_length_info = $row['ship_length_info'];
			$ship_width_info = $row['ship_width_info'];
			$ship_height_info = $row['ship_height_info'];
			$ship_mass_info = $row['ship_mass_info'];
			$ship_cargo_info = $row['ship_cargo_info'];
			$ship_crew_info = $row['ship_crew_info'];

			$ship_powerPlant_info = $row['ship_powerPlant_info'];
			$ship_mainThruster_info = $row['ship_mainThruster_info'];
			$ship_maneuveringThruster_info = $row['ship_maneuveringThruster_info'];
			$ship_shield_info = $row['ship_shield_info'];
			
			$currentGroup = $ship_pname;
			
			//If This is a New Group, Open a New Row and Title
			if ($currentGroup != $previousGroup)
			{
				//If This is not 1st Row, Close Previous Row
				if ($previousGroup != "")
				{
					$display_fleet .= "
									</div>
								</div>
							</td>
						</tr>
					";				
				}
				
				//Open New Row
				$display_fleet .= "
					<tr>
						<td>
							<div class=\"tbinfoTD_inner\">
								<div class=\"partialBorder-left-orange1 border-left border-top1px border-4px\">
								</div>
								<div class=\"tbinfo_shipTitle\">
									<div class=\"tbinfo_shipTitleText\">
										$ship_pname
									</div>
								</div>
								<div class=\"tbinfo_ships\">
				";
			}
			
			$display_fleet .= "
				<div class=\"fleet2\">
					<img class=\"fleet\" align=\"center\" src=\"http://sc.vvarmachine.com/images/silo_topDown/$ship_silo_info\" />
				
					<div class=\"cornerToggle2 corner-top-left\">
					</div>
					<div class=\"cornerToggle2 corner-top-right\">
					</div>
					<div class=\"cornerToggle2 corner-bottom-left\">
					</div>
					<div class=\"cornerToggle2 corner-bottom-right\">
					</div>
					<div class=\"fleet2_shipName_container\">
						<a class=\"fleet2_shipName\" href=\"ship/$ship_id_info\" >$ship_manu_short_info $ship_name_info
						</a>
					</div>
					
					<span class=\"tooltip\">
						<table class=\"tooltip_shipTable\">
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Designation
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_designation_info
									</div>
								</td>
							</tr>
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Primary Role
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_primary_info
									</div>
								</td>
							</tr>
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Length
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_length_info m
									</div>
								</td>
							</tr>
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Width
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_width_info m
									</div>
								</td>
							</tr>
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Height
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_height_info m
									</div>
								</td>
							</tr>
							<!--
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Null Cargo Mass
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_mass_info kg
									</div>
								</td>
							</tr>
							-->
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Cargo Capacity
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_cargo_info SCU
									</div>
								</td>
							</tr>
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Max Crew
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_crew_info
									</div>
								</td>
							</tr>
							<!--
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Max Power Plant
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_powerPlant_info
									</div>
								</td>
							</tr>									
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Main Thrusters
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_mainThruster_info
									</div>
								</td>
							</tr>									
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Maneuvering Thrusters
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_maneuveringThruster_info
									</div>
								</td>
							</tr>									
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Max Shield
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_shield_info
									</div>
								</td>
							</tr>
							-->
						</table>
					</span>
					<span class=\"tooltip2\">
						<table class=\"tooltip_shipTable\">
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									ShipID
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$ship_vvarID_info
									</div>
								</td>
							</tr>									
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Owner
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									<a href=\"player/$mem_id_info\" target=\"_top\">$mem_name_info</a>
									</div>
								</td>
							</tr>									
							<tr class=\"tooltip_shipTable_row\">
								<td class=\"tooltip_shipTable_key\">
									<div class=\"tooltip_shipTable_key_inner\">
									Last Modified
									</div>
								</td>
								<td class=\"tooltip_shipTable_value\">
									<div class=\"tooltip_shipTable_value_inner\">
									$modifiedOn_info
									</div>
								</td>
							</tr>
						</table>
					</span>
				</div>
				";

			$previousGroup = $currentGroup;
		}
		
		//Close Last Row
		$display_fleet .= "
				</td>
			</tr>
			";
		
		$display_stats;
		
		$stats_query = 
		"select
			COUNT(shm.rowID) as `Total_Ships`
			,SUM(sed.ship_mass) as `Total_Ship_Mass`
			,SUM(sed.ship_cargo_capacity) as `Total_Cargo_Capacity`
			,SUM(s.ship_price) as `Total_Fleet_Value`
		from projectx_vvarsc2.ships s
		join projectx_vvarsc2.ships_has_members shm
			on shm.ships_ship_id = s.ship_id
		join projectx_vvarsc2.ship_extended_data sed
			on sed.ships_ship_id = s.ship_id
		right join projectx_vvarsc2.members m
			on m.mem_id = shm.members_mem_id
		where m.mem_sc = 1";
			
		$stats_query_results = $connection->query($stats_query);
		
		$row3 = $stats_query_results->fetch_assoc();
		$Total_Ships = number_format($row3['Total_Ships']);
		$Total_Ship_Mass = number_format($row3['Total_Ship_Mass']);
		$Total_Cargo_Capacity = number_format($row3['Total_Cargo_Capacity']);
		$Total_Fleet_Value = number_format($row3['Total_Fleet_Value']);
		
		$display_stats .= "
			<tr>
				<th>
					Fleet Stats
				</th>
			</tr>
			<tr class=\"yard_stats_row\">
				<td class=\"yard_stats_row_td_key\">
					Total Ships:
				</td>
				<td class=\"yard_stats_row_td_value\">
					$Total_Ships
				</td>
			</tr>
			<tr class=\"yard_stats_row\">
				<td class=\"yard_stats_row_td_key\">
					Total Ship Mass:
				</td>
				<td class=\"yard_stats_row_td_value\">
					$Total_Ship_Mass Kg
				</td>
			</tr>
			<tr class=\"yard_stats_row\">
				<td class=\"yard_stats_row_td_key\">
					Total Ship Cargo Capacity:
				</td>
				<td class=\"yard_stats_row_td_value\">
					$Total_Cargo_Capacity SCU
				</td>
			</tr>
			<tr class=\"yard_stats_row\">
				<td class=\"yard_stats_row_td_key\">
					Total Fleet Value:
				</td>
				<td class=\"yard_stats_row_td_value\">
					$$Total_Fleet_Value
				</td>
			</tr>
		";
		
		$count_query = 
		"select
			REPLACE(s.ship_classification,'_',' ') as `ship_classification`
			,COUNT(shm.RowID) `number_of_ships`
		from projectx_vvarsc2.ships s
		join projectx_vvarsc2.ships_has_members shm
			on shm.ships_ship_id = s.ship_id
		group by
			s.ship_classification";
			
		$count_query_results = $connection->query($count_query);
		
		$display_counts .= "
			<tr>
				<th>
					Classification
				</th>
				<th>
					Ship Count
				</th>
			</tr>
		";
		
		while (($row4 = $count_query_results->fetch_assoc()) != false)
		{
			$ship_type = $row4['ship_classification'];
			$number_of_ships = $row4['number_of_ships'];
			
			$display_counts .= "
				<tr class=\"yard_stats_row\">
					<td class=\"yard_stats_row_td_key\">
						$ship_type
					</td>
					<td class=\"yard_stats_row_td_value\">
						$number_of_ships
					</td>
				</tr>
			";
		}
		
		$display_selectors;

		$display_selectors .= "
			<div class=\"tbinfo_zoomLevel_Container ZoomLevel_1\">
				<a>1</a>
			</div>
			<div class=\"tbinfo_zoomLevel_Container ZoomLevel_2\">
				<a>2</a>
			</div>
			<div class=\"tbinfo_zoomLevel_Container ZoomLevel_3 tbinfo_zlc_selected\">
				<a>3</a>
			</div>
			<div class=\"tbinfo_zoomLevel_Container ZoomLevel_4\">
				<a>4</a>
			</div>
		";

		$connection->close();
	}
?>
<h2>Star Citizen Fleet Infograph</h2>
<div id="TEXT">
	<div class="tbinfo_container">
		<div class="table_header_block2_long">
		</div>
		<table class="tbinfo">
			<? echo $display_fleet; ?>
		</table>
		<div class="tbinfo_right">
			<div id="tbinfo_right_inner"> <!--THIS IS THE DIV THAT REMAINS FIXED WHEN SCROLLING-->
				<!--
				<div class="tbinfo_zoomLevels_Container">
					<div class="tbinfo_zoomLevels_Label">
						Zoom Level
					</div>
					<? echo $display_selectors; ?>
				</div>
				<div class="table_header_block2">
				</div>
				-->
				<div class="tbinfo_right_items_container">
					<div class="tbinfo_right_item">
						<div class="tbinfo_right_shipTitle">
							<div class="tbinfo_right_shipTitleText">
								&nbsp;
							</div>						
						</div>
						<div class="table_header_block3">
						</div>
					</div>
					<div class="tbinfo_right_item">
						<div class="tbinfo_right_shipDetails1">
						</div>
						<div class="table_header_block4">
						</div>
					</div>
				</div>
				<div class="table_header_block2">
				</div>
				<div class="tbinfo_right_items_container">
					<div class="tbinfo_right_item">				
						<div class="tbinfo_right_shipDetails2">
						</div>
						<div class="table_header_block5">
						</div>
					</div>
				</div>
				<div class="tbinfo_right_yard_stats_container">
					<div class="table_header_block">
					</div>
					<div class="yard_stats_1">
						<table class="yard_stats">
							<? echo $display_stats; ?>
						</table>
					</div>
					<div class="yard_stats_2">
						<table class="yard_stats">
							<? echo $display_counts; ?>
						</table>					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.jScale.js"></script>
<script type="text/javascript" src="http://www.wduffy.co.uk/blog/wp-content/themes/agregado/js/jquery.jscroll.min.js"></script>

<!--Script for Re-Sizing Images-->
<script>
	$(document).ready(function() {
	
		var imageClass = $('.fleet');
		
		if(($( window ).width() < 900)) {
			imageClass.jScale({w: '20%'});
			$('.tbinfo_shipTitleText').css({
					"font-size": '0.85em',
					"display": 'none'
				});
			$('.tbinfo_ships').css({
					"padding-left": '4px'
				});
			imageClass.css({
					"margin": '0px'
				});
				
			$('.cornerToggle2.corner-top-left').jScale({w: '50%'});
			$('.cornerToggle2.corner-top-right').jScale({w: '50%'});
			$('.cornerToggle2.corner-bottom-left').jScale({w: '50%'});
			$('.cornerToggle2.corner-bottom-right').jScale({w: '50%'});	
		}	
		else if(($( window ).width() < 1200)){
			imageClass.jScale({w: '40%'});
			$('.tbinfo_shipTitleText').css({
					"font-size": '0.85em',
					"display": 'table-cell'
				});
			$('.tbinfo_ships').css({
					"padding-left": '8px'
				});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '60%'});
			$('.tbinfo_shipTitleText').css({
					"font-size": '1em',
					"display": 'table-cell'
				});
			$('.tbinfo_ships').css({
					"padding-left": '10px'
				});
			imageClass.css({
					"margin": '2px'
				});	
		}
	});
	
	$(window).resize(function () {
	
		var imageClass = $('.fleet');
		
		if(($( window ).width() < 900)) {
			imageClass.jScale({w: '20%'});
			$('.tbinfo_shipTitleText').css({
					"font-size": '0.85em',
					"display": 'none'
				});
			$('.tbinfo_ships').css({
					"padding-left": '4px'
				});
			imageClass.css({
					"margin": '0px'
				});
				
			$('.cornerToggle2.corner-top-left').jScale({w: '50%'});
			$('.cornerToggle2.corner-top-right').jScale({w: '50%'});
			$('.cornerToggle2.corner-bottom-left').jScale({w: '50%'});
			$('.cornerToggle2.corner-bottom-right').jScale({w: '50%'});	
		}	
		else if(($( window ).width() < 1200)){
			imageClass.jScale({w: '40%'});
			$('.tbinfo_shipTitleText').css({
					"font-size": '0.85em',
					"display": 'table-cell'
				});
			$('.tbinfo_ships').css({
					"padding-left": '8px'
				});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '60%'});
			$('.tbinfo_shipTitleText').css({
					"font-size": '1em',
					"display": 'table-cell'
				});
			$('.tbinfo_ships').css({
					"padding-left": '10px'
				});
			imageClass.css({
					"margin": '2px'
				});		
		}
	});	

</script>

<!--Script to Keep Right-Hand-Detail Elements Fixed While Scrolling Vertically-->
<script>
	var currentScroll;
	var fixmeTop = $('.tbinfo_right').offset().top;
	var divToScroll = $('#tbinfo_right_inner');
	
	$(window).on( 'scroll', function(){
		currentScroll = $(window).scrollTop();
		
		if (currentScroll <= fixmeTop)
		{
			divToScroll
				.stop()
				.css({"marginTop": (0 + 12) + "px"});			
		}
		else
		{
			divToScroll
				.stop()
				.css({"marginTop": (currentScroll - fixmeTop + 12) + "px"});			
		}
		

	});

</script>

<!--Script to Show/Hide The Right-Hand-Detail Elements-->
<script>

	$(document).ready(function() {
		
		//Items to set HTML Contents for Default
		$('.tbinfo_right_shipTitleText').html($(this).find('.fleet2_shipName_container').html());
		$('.tbinfo_right_shipDetails1').html($(this).find('span.tooltip').html());
		$('.tbinfo_right_shipDetails2').html($(this).find('span.tooltip2').html());
		
		$('.cornerToggle2').removeClass("opaque");

		$('html').click(function() {
			//Items to set to Opacity 0
			$('.tbinfo_right_shipTitleAngledSpacer').removeClass("opaque");
			$('.tbinfo_right_shipTitleText').removeClass("opaque");
			$('.tooltip_shipTable').removeClass("opaque");
			$('.tooltip_shipTable').removeClass("opaque");
			
			$('.cornerToggle2').removeClass("opaque");
			$('.fleet2').css({
						border: '1px solid transparent'
					});
					
			$('.tbinfo_shipTitle').removeClass("tbinfo_shipTitle_selected");
		});
		
		$('.fleet2').click(function(e){
			$('.fleet2').css({
						border: '1px solid transparent'
					});

			$('.cornerToggle2').removeClass("opaque");
			$('.tbinfo_shipTitle').removeClass("tbinfo_shipTitle_selected");
			
			$(this).css({
						border: '1px solid rgba(50, 50, 50, 0.7)'
					});

			$(this).find('.cornerToggle2').addClass("opaque");
			
			$('.tbinfo_right_shipTitleAngledSpacer').removeClass("opaque");
			$('.tbinfo_right_shipTitleText').removeClass("opaque");
			$('.tooltip_shipTable').removeClass("opaque");
			$('.tooltip_shipTable').removeClass("opaque");
			
			$(this).parent().parent().find('.tbinfo_shipTitle').addClass("tbinfo_shipTitle_selected");
			
			var $self = jQuery(this);
				 
			setTimeout(function(){	
				 //Items to set HTML Contents
				$('.tbinfo_right_shipTitleText').html($self.find('.fleet2_shipName_container').html());
				$('.tbinfo_right_shipDetails1').html($self.find('span.tooltip').html());
				$('.tbinfo_right_shipDetails2').html($self.find('span.tooltip2').html());
				
				//Items to set to Opacity 1
				$('.tbinfo_right_shipTitleAngledSpacer').addClass("opaque");
				$('.tbinfo_right_shipTitleText').addClass("opaque");
				
				$('.tooltip_shipTable').addClass("opaque");
				$('.tooltip_shipTable').addClass("opaque");
			}.bind(this), 500); 
			
			e.stopPropagation();
		});
	});

</script>

<!-- Script for changing background image-->
<!--
<script>
	$(document).ready(function() {
		$('body').css('background','url(http://vvarmachine.com/uploads/galleries/SC_background_pic_06.png) no-repeat fixed center center transparent');
	});
</script>
-->