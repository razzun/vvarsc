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
		where a.IsActive = 1
			and a.AwardID not in (
				select
					ma.AwardID
				from projectx_vvarsc2.member_Awards ma
				where ma.MemberID = $player_id
			)
		order by
			a.AwardOrderBy
			,a.AwardName
	";
	
	$getAwards_query_results = $connection->query($getAwards_query);
	$displayAwardsSelectors = "";
	
	while(($row = $getAwards_query_results->fetch_assoc()) != false)
	{
		$awardID = $row['AwardID'];
		$awardName = $row['AwardName'];
		$awardOrderBy = $row['AwardOrderBy'];
	
		$displayAwardsSelectors .= "
			<option value=\"$awardID\" id=\"$awardID\">
				[$awardOrderBy] $awardName
			</option>
		";
	}
?>