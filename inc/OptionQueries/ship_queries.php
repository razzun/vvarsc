<?
	/*Get All Ships*/
	$getAllShips_query = "
		select
			s.ship_id
			,m.manu_shortName
			,s.ship_name
            ,s.ship_model_designation
            ,s.ship_model_visible
		from projectx_vvarsc2.ships s
		join projectx_vvarsc2.manufacturers m
			on m.manu_id = s.manufacturers_manu_id
		order by
			m.manu_name
			,s.ship_name
	";
	
	$getAllShips_query_results = $connection->query($getAllShips_query);
	$displayGetAllShipsSelectors = "";
	
	while(($row = $getAllShips_query_results->fetch_assoc()) != false)
	{
		$ShipID = $row['ship_id'];
		$ManuName = $row['manu_shortName'];
		$ShipName = $row['ship_name'];
		$ModelDesignation = $row['ship_model_designation'];
		$ModelVisible = $row['ship_model_visible'];
	
		if ($ModelDesignation != null && $ModelVisible == 1)
		{
		$displayGetAllShipsSelectors .= "
			<option value=\"$ShipID\" id=\"ShipID-$ShipID\">
				$ManuName - $ModelDesignation $ShipName
			</option>
		";
		}
		else
		{
		$displayGetAllShipsSelectors .= "
			<option value=\"$ShipID\" id=\"ShipID-$ShipID\">
				$ManuName - $ShipName
			</option>
		";
		}
	}
?>
