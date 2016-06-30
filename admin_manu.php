<?php 
	$manu_query = "
		select
			m.manu_id
			,m.manu_name
			,m.manu_shortName
			,m.manu_smallImage
		from projectx_vvarsc2.manufacturers m
	";
	
	$manu_query_results = $connection->query($manu_query);
	
	while(($row = $manu_query_results->fetch_assoc()) != false)
	{
		$manuID = $row['manu_id'];
		$manuName = $row['manu_name'];
		$manuShortName = $row['manu_shortName'];
		$manuSmallImage = $row['manu_smallImage'];
	
		$displayManu .= "
			<tr class=\"adminTableRow\">
				<td class=\"adminTableRowTD\">
					$manuID
				</td>
				<td class=\"adminTableRowTD\">
					$manuName
				</td>
				<td class=\"adminTableRowTD\">
					$manuShortName
				</td>
				<td class=\"adminTableRowTD\">
					<img class=\"shipyard_mainTable_row_header_manuImage\" align=\"center\" src=\"$manuSmallImage\" />
				</td>
			</tr>
		";
	}
?>

<h2>Manufacturer Management</h2>
<div id="TEXT">
	<div id="adminManuTableContainer" class="adminTableContainer">
		<table id="adminManuTable" class="adminTable">
			<tr class="adminTableHeaderRow">
				<td class="adminTableHeaderRowTD">
					ID
				</td>
				<td class="adminTableHeaderRowTD">
					Name
				</td>
				<td class="adminTableHeaderRowTD">
					ShortName
				</td>
				<td class="adminTableHeaderRowTD">
					LogoImage
				</td>
			</tr>
			<? echo $displayManu ?>
		</table>
	</div>
</div>