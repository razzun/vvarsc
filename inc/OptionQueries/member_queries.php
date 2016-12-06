<?
	/*Get OrgUnits*/
	$getMembers_query = "
		select
			m.mem_id
			,m.mem_callsign
			,r.rank_abbr
			,r.rank_level
			,case
				when am.MemberID is null then 'No'
				else 'Yes'
			end as MemberAssigned
		from projectx_vvarsc2.members m
		join projectx_vvarsc2.ranks r
			on r.rank_id = m.ranks_rank_id
		left join (
			select
				m.MemberID
			from projectx_vvarsc2.MissionUnitMembers m
			where m.MissionID = '$MissionID'
			union
			select
				m.MemberID
			from projectx_vvarsc2.MissionShipMembers m
			where m.MissionID = '$MissionID'
		) am
			on am.MemberID = m.mem_id
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
		$MemberAssigned = $row['MemberAssigned'];
		
		if ($MemberAssigned == "Yes")
		{
			$displayGetMembersSelectors .= "
				<option value=\"$MemberID\" id=\"MemberID-$MemberID\" style=\"display:none;\">
					[$Rank_Level] - $Rank_Abbr $MemberName
				</option>
			";
		}
		else
		{
			$displayGetMembersSelectors .= "
				<option value=\"$MemberID\" id=\"MemberID-$MemberID\">
					[$Rank_Level] - $Rank_Abbr $MemberName
				</option>
			";
		}
	}
?>