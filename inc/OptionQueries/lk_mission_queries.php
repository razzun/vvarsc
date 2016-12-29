<?
	/*Get MissionStatuses*/
	$getMissionStatuses_query = "
		select
		*
		from
		(
			select
				lk.MissionStatus as MissionStatusID
				,lk.Description as MissionStatusDescription
			from projectx_vvarsc2.LK_MissionStatus lk
			where lk.Description = '$operationDetails_MissionStatusDescrption'
			
			union
			
			select
				lk.MissionStatus as MissionStatusID
				,lk.Description as MissionStatusDescription
			from projectx_vvarsc2.LK_MissionStatus lk
	";
	
	//If Admin, display all Mission Status Options
	if ($_SESSION['sess_userrole'] == "admin")
	{
		$getMissionStatuses_query .= "
			where 1 = 1
		";
	}
	//Otherwise, list the appropriate ones for each state
	else if ($operationDetails_MissionStatusDescrption == "Planned")
	{
		$getMissionStatuses_query .= "
			where lk.Description in ('Submitted')
		";
	}
	else if ($operationDetails_MissionStatusDescrption == "Rejected")
	{
		$getMissionStatuses_query .= "
			where lk.Description in ('Submitted')
		";
	}
	else if ($operationDetails_MissionStatusDescrption == "Submitted")
	{
		$getMissionStatuses_query .= "
			where lk.Description in ('Approved','Rejected')
		";
	}
	else if ($operationDetails_MissionStatusDescrption == "Approved")
	{
		$getMissionStatuses_query .= "
			where lk.Description in ('Final-Approved','Cancelled')
		";
	}
	else if ($operationDetails_MissionStatusDescrption == "Final-Approved")
	{
		$getMissionStatuses_query .= "
			where lk.Description in ('Completed')
		";
	}
	else
	{
		$getMissionStatuses_query .= "
			where 1 = 0
		";
	}
	
	$getMissionStatuses_query .= "
		) a
		order by
			a.MissionStatusDescription
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