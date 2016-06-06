<?
	$display_fleet;
	
   	$fleet_query = "SELECT CONCAT(`manufacturers`.`manu_name`,' ',`ships`.`ship_name`) AS ship_info,
		GROUP_CONCAT(`members`.`mem_name` ORDER BY `members`.`mem_name` SEPARATOR '<br />') as mem_info,
		`ships`.`ship_link`
	FROM `projectx_vvarsc2`.`ships`
		LEFT JOIN `projectx_vvarsc2`.`ships_has_members`
			ON `ships_has_members`.`ships_ship_id` = `ships`.`ship_id`
		LEFT JOIN `projectx_vvarsc2`.`manufacturers`
			ON `ships`.`manufacturers_manu_id` = `manufacturers`.`manu_id`
		JOIN `projectx_vvarsc2`.`members`
			ON `ships_has_members`.`members_mem_id` = `members`.`mem_id`
	GROUP BY `ship_info`
    ORDER BY `ship_info`";
    
    $fleet_query_results = $connection->query($fleet_query);
	
	$i=0;
	while (($row = $fleet_query_results->fetch_assoc()) != false) {
		if($i % 3 == 0) {
			$display_fleet .= "<tr>";
		}

		$ship_info = $row['ship_info'];
		$mem_info = $row['mem_info'];
		$ship_link = $row['ship_link'];

		$display_fleet .= "
				<td valign=\"top\"><div id=\"fleet\"><div id=\"fheader\">$ship_info</div><br/><img align=\"center\" height=\"210\" width=\"350\" alt=\"$ship_info\" src=\"$ship_link\" /><br /><div id=\"fmem\">$mem_info</div></div></td>
			";

		if (++$i % 3 == 0) {
			$display_fleet .= "</tr>\n";
		}
	}

	// clean add the missing cells to last row.
	if ($i % 3 != 0) {
		while ($i++ % 3) {
			$display_fleet .= "<td></td>";
		}
		
		$display_fleet .= "</tr>\n";
	}
	
	$connection->close();
?>

  <h2>Star Citizen Division Fleet</h2>
  <div id="TEXT">
    <table class="tbFleet">
		<? echo $display_fleet; ?>
	</table>
  </div>