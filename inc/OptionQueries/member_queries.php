<?
	/*Get OrgUnits*/
	$getMembers_query = "
		select
			m.mem_id
			,m.mem_callsign
			,r.rank_abbr
			,r.rank_level
		from projectx_vvarsc2.members m
		join projectx_vvarsc2.ranks r
			on r.rank_id = m.ranks_rank_id
		where m.mem_sc = 1
		order by
			r.rank_orderBy
			,m.mem_callsign
	";
	
	$getMembers_query_results = $connection->query($getMembers_query);
	$displayGetMembersSelectors = "";
	
	while(($row = $getMembers_query_results->fetch_assoc()) != false)
	{
		$MemberID = $row['mem_id'];
		$MemberName = $row['mem_callsign'];
		$Rank_Abbr = $row['rank_abbr'];
		$Rank_Level = $row['rank_level'];
	
		$displayGetMembersSelectors .= "
			<option value=\"$MemberID\" id=\"MemberID-$MemberID\">
				[$Rank_Level] - $Rank_Abbr $MemberName
			</option>
		";
	}
?>