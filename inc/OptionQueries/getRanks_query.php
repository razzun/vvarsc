<?
	$rank_query = "
		select
			r.rank_id
			,r.rank_level
			,r.rank_name
		from projectx_vvarsc2.ranks r
		order by
			r.rank_orderby
	";
	
	$rank_query_results = $connection->query($rank_query);
	$displayRanks = "";
	
	while(($row = $rank_query_results->fetch_assoc()) != false)
	{
		$RankID = $row['rank_id'];
		$RankLevel = $row['rank_level'];
		$RankName = $row['rank_name'];
	
		$displayRanks .= "
			<option value=\"$RankID\" id=\"$RankID\">
				$RankLevel - $RankName
			</option>
		";
	}
?>