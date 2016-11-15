<?php include_once('functions/function_auth_user.php'); ?>

<?
	$display_filters;
	
	$filter_query = 
	"select distinct
		m.`manu_id`
		,m.`manu_name`
		,m.`manu_shortName`
		,m.`manu_smallImage`
	from `projectx_vvarsc2`.`ships` s
	join `projectx_vvarsc2`.`manufacturers` m
		on m.`manu_id` = s.`manufacturers_manu_id`
	join `projectx_vvarsc2`.`ship_extended_data` sed
		on sed.`ships_ship_id` = s.`ship_id`
	join `projectx_vvarsc2`.`ships_has_members` shm
		on shm.`ships_ship_id` = s.`ship_id`
	order by
		m.`manu_name`";
		
	$filter_query_results = $connection->query($filter_query);
	
	$i=0;
	while(($row1 = $filter_query_results->fetch_assoc()) != false)
	{

		$manu_name1 = $row1['manu_name'];
		$manu_shortName1 = $row1['manu_shortName'];
		$manu_id1 = $row1['manu_id'];
		$manu_image1 = $row1['manu_smallImage'];
		
		$display_filters .= "
			<tr class=\"yard_filters_row\">
			<td class=\"yard_filters_entry $manu_shortName1\">
				<a class=\"yard_filters_entry_container\" />
					<div class=\"cornerToggle2 corner-top-left\">
					</div>
					<div class=\"cornerToggle2 corner-top-right\">
					</div>
					<div class=\"cornerToggle2 corner-bottom-left\">
					</div>
					<div class=\"cornerToggle2 corner-bottom-right\">
					</div>
					<img class=\"yard_filters_entry_manuImage\" src=\"$manu_image1\" />
					<div class=\"yard_filters_entry_manuName\">
						$manu_name1
					</div>
				</a>
			</td>
			</tr>
		";
	}

	$display_fleet;
	
   	$fleet_query = 
	"select
		s.`ship_id`
		,s.`ship_pname`
		,s.`ship_name`
		,s.ship_model_designation
		,s.ship_model_visible
		,s.`ship_role_primary`
		,s.`ship_role_secondary`
		,s.`ship_image_link`
		,s.`ship_desc`
		,s.`ship_classification`
		,sed.`ship_length`
		,sed.`ship_width`
		,sed.`ship_height`
		,sed.`ship_mass`
		,sed.`ship_cargo_capacity`
		,sed.`ship_max_crew`
		,sed.`ship_max_powerPlant`
		,sed.`ship_max_mainThruster`
		,sed.`ship_max_maneuveringThruster`
		,sed.`ship_max_shield`
		,m.`manu_id`
		,m.`manu_name`
		,m.`manu_shortName`
		,m.`manu_smallImage`
		,COUNT(distinct shm.rowID) as ship_count
	from `projectx_vvarsc2`.`ships` s
	join `projectx_vvarsc2`.`manufacturers` m
		on m.`manu_id` = s.`manufacturers_manu_id`
	join `projectx_vvarsc2`.`ship_extended_data` sed
		on sed.`ships_ship_id` = s.`ship_id`
	join `projectx_vvarsc2`.`ships_has_members` shm
		on shm.`ships_ship_id` = s.`ship_id`
	join `projectx_vvarsc2`.`members` mem
		on shm.`members_mem_id` = mem.`mem_id`
		and mem.mem_sc = 1
	group by
		s.`ship_id`
	order by
		s.`ship_pname`
		,s.`ship_name`";
	
    $fleet_query_results = $connection->query($fleet_query);
	
	while (($row2 = $fleet_query_results->fetch_assoc()) != false)
	{
		$ship_id2 = $row2['ship_id'];
		$ship_name2 = $row2['ship_name'];
		$ship_image_link2 = $row2['ship_image_link'];
		$manu_name2 = $row2['manu_name'];
		$manu_shortName2 = $row2['manu_shortName'];
		$ship_desc2 = $row2['ship_desc'];
		$manu_image2 = $row2['manu_smallImage'];
		$mem_id_info = $row2['mem_id_info'];
		$mem_name_info = $row2['mem_name_info'];
		$ship_classification = $row2['ship_classification'];
		$ship_model_designation = $row2['ship_model_designation'];
		$ship_model_visible = $row2['ship_model_visible'];
		$ship_count = $row2['ship_count'];
		
		$mem_id_array = explode('|', $mem_id_info);
		$mem_name_array = explode('|', $mem_name_info);
    		
		if ($ship_model_designation != NULL && $ship_model_visible != "0") {
			$full_ship_name = "";
			$full_ship_name .= $ship_model_designation;
			$full_ship_name .= " \n";
			$full_ship_name .= $ship_name2;
			
			$full_ship_name_formatted = nl2br($full_ship_name);
		}
		else
		{
			$full_ship_name = $ship_name2;
			$full_ship_name_formatted = $full_ship_name;
		}
	

		$display_fleet .= "
			<tr class=\"shipyard_mainTable_row $manu_shortName2 $ship_classification\">
				<th class=\"shipyard_mainTable_row_header\">
					<img class=\"shipyard_mainTable_row_header_arrow\" align=\"center\" src=\"http://vvarmachine.com/uploads/galleries/SC_Button01.png\" />
					<img class=\"shipyard_mainTable_row_header_manuImage\" align=\"center\" src=\"$manu_image2\" />
					<div class=\"shipyard_mainTable_row_header_shipTitle\">
						<a href=\"ship/$ship_id2\">$full_ship_name_formatted
						</a>
					</div>
				</th>
				<td class=\"shipyard_mainTable_row_td\" valign=\"top\">
					<div class=\"shipyard_mainTable_row_content\">
						<img class=\"shipyard_mainTable_row_shipImage\" alt=\"$ship_name2\" src=\"$ship_image_link2\" />
						<br />
						<div class=\"shipyard_mainTable_row_shipOwners\">
							Fleet Ship-Count: $ship_count 
						</div>
						<div class=\"shipyard_mainTable_row_shipDesc_container\">
							<div class=\"shipyard_mainTable_row_shipDesc\">
								<div class=\"corner corner-top-left\">
								</div>
								<div class=\"corner corner-top-right\">
								</div>
								<div class=\"corner corner-bottom-left\">
								</div>
								<div class=\"corner corner-bottom-right\">
								</div>
								$ship_desc2
							</div>
						</div>
					</div>
				</td>
			</tr>
		";
	}
	
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
		<tr>
			<td>
				&nbsp;
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
	
	$display_stats .= "
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
		
		$ship_type = str_replace("_"," ",$ship_type);
		
		$display_stats .= "
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

	$connection->close();
?>

<h2>Star Citizen Fleet Shipyard</h2>
<div id="TEXT">
	<div class="yard_outer_container">
		<div class="yard_left_container">
			<div class="yard_filter_header">
				<img class="filter_header_arrow" align="center" src="http://vvarmachine.com/uploads/galleries/SC_Button01.png"/>
				<div id="yard_filter_header_text">
					Filters
				</div>			
			</div>
			<div class="yard_filter_container">
				<div class="table_header_block">
				</div>
				<table class="yard_filters">
					<? echo $display_filters; ?>
				</table>
				<div class="yard_filters_clear_container">
					Reset Manufacturer Filters
				</div>
				<br />
				<br />
				<div class="yard_filters_expand_container">
					(+) Expand All
				</div>
				<div class="yard_filters_collapse_container">
					(-) Collapse All
				</div>
			</div>
		</div>
		<div class="yard_main_container">
			<div class="table_header_block">
			</div>
			<div class="yard_main">
				<table class="shipyard_mainTable">
					<? echo $display_fleet; ?>
				</table>
			</div>
		</div>
		<!--
		<div class="yard_right_container">
			<div class="yard_stats_container">
				<div class="table_header_block">
				</div>
				<table class="yard_stats">
					<? echo $display_stats; ?>
				</table>
			</div>
		</div>
		-->
	</div>
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.jScale.js"></script>
	
<!--Script to Show/Hide Rows when Arrows are clicked on each row-->
<script language="javascript">
    $(document).ready(function () {
        $(".shipyard_mainTable_row_content").hide();
        $(".shipyard_mainTable_row_header_arrow").click(function () {
            $(this).parent().parent().find(".shipyard_mainTable_row_content").slideToggle(500);
			$(this).toggleClass('rotate90CW');
        });		
    });
</script>

<!--Script to Re-Size Images-->
<script>
	$(document).ready(function() {
	
		var imageClass = $('.shipyard_mainTable_row_shipImage');
	
		if((screen.width < 900)) {
			imageClass.jScale({w: '40%'});
			imageClass.css({
					"margin": '0px'
				});
		}	
		else if((screen.width < 1200)){
			imageClass.jScale({w: '60%'});
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
	
		var imageClass = $('.shipyard_mainTable_row_shipImage');
	
		if((screen.width < 900)) {
			imageClass.jScale({w: '40%'});
			imageClass.css({
					"margin": '0px'
				});
		}	
		else if((screen.width < 1200)){
			imageClass.jScale({w: '60%'});
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

<!--Script to Keep Left-Hand-Detail Elements Fixed While Scrolling Vertically-->
<script>
	var currentScroll_1;
	var fixmeTop_1 = $('.yard_left_container').offset().top;
	var divToScroll_1 = $('.yard_filter_container');
	
	$(window).on( 'scroll', function(){
		currentScroll_1 = $(window).scrollTop();
		if ((screen.width > 719)){
			if (currentScroll_1 <= fixmeTop_1)
			{
				divToScroll_1
					.stop()
					.css({"marginTop": (0 + 12) + "px"});			
			}
			else
			{
				divToScroll_1
					.stop()
					.css({"marginTop": (currentScroll_1 - fixmeTop_1 + 12) + "px"});			
			}
		}
		

	});

</script>

<!--Script to Keep Right-Hand-Detail Elements Fixed While Scrolling Vertically-->
<script>
	var currentScroll_2;
	var fixmeTop_2 = $('.yard_right_container').offset().top;
	var divToScroll_2 = $('.yard_stats_container');
	
	$(window).on( 'scroll', function(){
		currentScroll_2 = $(window).scrollTop();
		
		if ((screen.width > 1023)){
			if (currentScroll_2 <= fixmeTop_2)
			{
				divToScroll_2
					.stop()
					.css({"marginTop": (0 + 12) + "px"});			
			}
			else
			{
				divToScroll_2
					.stop()
					.css({"marginTop": (currentScroll_2 - fixmeTop_2 + 12) + "px"});			
			}
		}
		

	});

</script>

<!--Script to Show/Hide All Rows when buttons are clicked-->
<script language="javascript">
    $(document).ready(function () {
		$('.yard_filters_expand_container').click(function () {
			$('.shipyard_mainTable_row').each(function(index) {
				//If This Row Is Already Expanded
				if( $(this).find('.shipyard_mainTable_row_header_arrow').hasClass('rotate90CW') )
				{
					//Do Nothing
				}
				else
				{
					//Activate Slide Toggle
					$(this).find(".shipyard_mainTable_row_content").slideToggle(500);
					$(this).find('.shipyard_mainTable_row_header_arrow').toggleClass('rotate90CW');
				}
			});
        });
		
		$('.yard_filters_collapse_container').click(function () {
			$('.shipyard_mainTable_row').each(function(index) {
				//If This Row Is Already Expanded
				if( $(this).find('.shipyard_mainTable_row_header_arrow').hasClass('rotate90CW') )
				{
					//Activate Slide Toggle
					$(this).find(".shipyard_mainTable_row_content").slideToggle(500);
					$(this).find('.shipyard_mainTable_row_header_arrow').toggleClass('rotate90CW');					
				}
				else
				{
					//Do Nothing
				}
			});
        });		

    });
</script>

<!--Script for Ship Manufacturer Filters-->
<script language="javascript">
    $(document).ready(function () {
		//Show All Rows by Default when Page Loads
		$('.shipyard_mainTable_row').show();

		$('.yard_filters_clear_container').click(function() {
			
			//Clear All Filter Selectors
			$('.yard_filters').find('.cornerToggle2').removeClass("opaque");
			
			//Show All Ship Rows
			$('.shipyard_mainTable_row').show();
		});
		
		$('.yard_filters_entry_container').click(function() {
		
			var $self = jQuery(this);
		
			//Get ManuName of Filter Clicked
			var $selectedManuName = $self.parent().attr('class').substr(19);
			
			//Check If Other Filters are Active
			//--------------------------------//
			var $otherFilterActive = 0;
			
			$('.yard_filters_entry_container').each(function(index) {
				
				//Check if this entry is not the selected filter
				if( $(this).parent().attr('class').substr(19) != $selectedManuName )
				{
					//Check if this filter is enabled
					if( $(this).find('.cornerToggle2').hasClass("opaque") )
					{
						$otherFilterActive++;
					}
				}
			});
			//--------------------------------//
		
			//Check If This Filter is Active or Inactive
			if($self.find('.cornerToggle2').hasClass("opaque")) //This Filter Is Already On
			{
				if($otherFilterActive != 0) //Another Filter is on - turn off and hide the rows for this manu filter
				{
					$self.find('.cornerToggle2').removeClass("opaque");
					$('.shipyard_mainTable_row.' + $selectedManuName).hide();
				}
				else //no Other Filters are on - turn off filter and show all rows
				{
					$self.find('.cornerToggle2').removeClass("opaque");
					$('.shipyard_mainTable_row').show();
				}
			}
			else //This Filter is Disabled
			{
				if($otherFilterActive != 0) //Another Filter is on - turn on and show the rows for this manu filter
				{
					$self.find('.cornerToggle2').addClass("opaque");
					$('.shipyard_mainTable_row.' + $selectedManuName).show();
				}
				else //No Other Filters are on - hide all rows, turn on and show the rows for this manu filter
				{
					$('.shipyard_mainTable_row').hide();
					$self.find('.cornerToggle2').addClass("opaque");
					$('.shipyard_mainTable_row.' + $selectedManuName).show();
				}
			}
			
		});	
    });
</script>

<!--Script to Show/Hide Filter Rows when Expansion Arrow is Clicked-->
<script>
    $(document).ready(function () {
		if((screen.width < 720)){
			$(".yard_filter_container").hide();
		}
		else {
			$(".yard_filter_container").show();
		}
		
		$(".filter_header_arrow").click(function () {
			$(this).parent().parent().find(".yard_filter_container").slideToggle(500);
			$(this).toggleClass('rotate90CW');
		});
		
    });

</script>

<script>
	var previousWidth = (screen.width);
	
	$(window).resize(function () {
	
		var currentWidth = (screen.width);
		if(currentWidth != previousWidth) {
			if((currentWidth < 720)){
				$(".yard_filter_container").hide();
				$(".filter_header_arrow").removeClass('rotate90CW');
			}
			else {
				$(".yard_filter_container").show();
			}
		}
		
		previousWidth = (screen.width);
	});
</script>