<?
	/*Get Award Categories*/
	$getAwards_query = "
		select
			a.AwardID
			,a.AwardName
			,a.AwardImage
			,a.IsActive
			,a.AwardOrderBy
		from projectx_vvarsc2.Awards a
		order by
			a.AwardName
	";
	
	$getAwards_query_results = $connection->query($getAwards_query);
	$displayAwardsSelectors = "";
	
	while(($row = $getAwards_query_results->fetch_assoc()) != false)
	{
		$awardID = $row['AwardID'];
		$awardName = $row['AwardName'];
	
		$displayAwardsSelectors .= "
			<option value=\"$awardID\" id=\"$awardID\">
				$awardName
			</option>
		";
	}
?>