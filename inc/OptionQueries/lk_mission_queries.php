<?
	/*Get MissionStatuses*/
	$getMissionStatuses_query = "
		select
			lk.MissionStatus as MissionStatusID
			,lk.Description as MissionStatusDescription
		from projectx_vvarsc2.LK_MissionStatus lk
		order by
			lk.Description
	";
	
	$getMissionStatuses_query_results = $connection->query($getMissionStatuses_query);
	$displayMissionStatusesSelectors = "";
	
	while(($row = $getMissionStatuses_query_results->fetch_assoc()) != false)
	{
		$MissionStatusID = $row['MissionStatusID'];
		$MissionStatusDescription = $row['MissionStatusDescription'];
	
		$displayMissionStatusesSelectors .= "
			<option value=\"$MissionStatusID\" id=\"MissionStatusID-$MissionStatusID\">
				$MissionStatusDescription
			</option>
		";
	}
?>

<?
	/*Get MissionOutcomees*/
	$getMissionOutcomes_query = "
		select
			lk.MissionOutcome as MissionOutcomeID
			,lk.Description as MissionOutcomeDescription
		from projectx_vvarsc2.LK_MissionOutcome lk
		order by
			lk.Description
	";
	
	$getMissionOutcomes_query_results = $connection->query($getMissionOutcomes_query);
	$displayMissionOutcomesSelectors = "";
	
	while(($row = $getMissionOutcomes_query_results->fetch_assoc()) != false)
	{
		$MissionOutcomeID = $row['MissionOutcomeID'];
		$MissionOutcomeDescription = $row['MissionOutcomeDescription'];
	
		$displayMissionOutcomesSelectors .= "
			<option value=\"$MissionOutcomeID\" id=\"MissionOutcomeID-$MissionOutcomeID\">
				$MissionOutcomeDescription
			</option>
		";
	}
?>