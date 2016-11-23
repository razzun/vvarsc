<? //Operations LIST
	if ($OperationID == null)
		$OperationID = "";
	
	$display_operationsList = "";
	
	$operationsList_query = "
		select
			o.OpTemplateID
			,CONCAT('OPT_',(1000 + o.OpTemplateID)) as OpTemplateNumber
			,o.OpTemplateName
			,o.OpTemplateType
			,o.Mission
			,DATE_FORMAT(DATE(o.ModifiedOn),'%d %b %Y') as ModifiedOn
			,o.ModifiedBy
			,m.mem_callsign as ModifiedByName
		from projectx_vvarsc2.OpTemplates o
		join projectx_vvarsc2.members m
			on m.mem_id = o.ModifiedBy
		order by
			o.OpTemplateName
	";
	
	$operationsList_query_result = $connection->query($operationsList_query);
	
	while(($row1 = $operationsList_query_result->fetch_assoc()) != false) {
		$operationListItem_ID = $row1['OpTemplateID'];
		$operationListItem_Number = $row1['OpTemplateNumber'];
		$operationListItem_Name = $row1['OpTemplateName'];
		$operationListItem_Type = $row1['OpTemplateType'];
		$operationListItem_Mission = $row1['Mission'];
		$operationListItem_ModifiedOn = $row1['ModifiedOn'];
		$operationListItem_ModifiedByID = $row1['ModifiedBy'];
		$operationListItem_ModifiedByName = $row1['ModifiedByName'];
		
		if ($operationListItem_ID == $OperationID)
		{
			$display_operationsList .= "
				<div class=\"operationsListItemContainer operations_selected\"
					data-operationid=\"$operationListItem_ID\"
					data-targetid=\"http://sc.vvarmachine.com/operation/$operationListItem_ID\"
				>
			";
		}
		else
		{
			$display_operationsList .= "
				<div class=\"operationsListItemContainer\"
					data-operationid=\"$operationListItem_ID\"
					data-targetid=\"http://sc.vvarmachine.com/operation/$operationListItem_ID\"
				>
			";		
		}
		
		$display_operationsList .= "
					<div class=\"operationsListItem_Title\">
						<a href=\"http://sc.vvarmachine.com/operation/$operationListItem_ID\">
							$operationListItem_Number - $operationListItem_Name
						</a>
					</div>
					<div class=\"operationsListItem_Type\">
						Type: 
						<div class=\"operationsListItem_Type_Value\">
							$operationListItem_Type
						</div>
					</div>
					<div class=\"operationsListItem_Mission\" style=\"
						width: auto;
						text-overflow: unset;
						white-space: unset;
					\">
						$operationListItem_Mission
					</div>
					<!--
					<div class=\"operationsListItem_MetaData_Right\">
						<div class=\"clickableRow_memRank_inner\" style=\"
							width: 100%
						\">
							Modified: $operationListItem_ModifiedOn						
						</div>
					</div>
					-->
				</div>
		";
	}
	//END Operations List
?>

<?
	//Missions List
	if ($MissionID == null)
		$MissionID = "";
		
	$display_missionsList = "";
	
	$missionsList_query = "
		select
			o.MissionID
			,CONCAT('OPT_',(1000 + o.OpTemplateID),'-',(1000 + o.MissionID)) as MissionNumber
			,o.MissionName
			,o.MissionType
			,o.Mission
			,o.StartDate
			,DATE_FORMAT(DATE(o.ModifiedOn),'%d %b %Y') as ModifiedOn
			,o.ModifiedBy
			,m.mem_callsign as ModifiedByName
		from projectx_vvarsc2.Missions o
		join projectx_vvarsc2.members m
			on m.mem_id = o.ModifiedBy
		where o.StartDate >= UTC_TIMESTAMP()
		order by
			o.StartDate
	";
	
	$missionsList_query_result = $connection->query($missionsList_query);
	
	while(($row1 = $missionsList_query_result->fetch_assoc()) != false) {
		$missionListItem_ID = $row1['MissionID'];
		$missionListItem_Number = $row1['MissionNumber'];
		$missionListItem_Name = $row1['MissionName'];
		$missionListItem_Type = $row1['MissionType'];
		$missionListItem_Mission = $row1['Mission'];
		$missionListItem_StartDate = $row1['StartDate'];
		$missionListItem_ModifiedOn = $row1['ModifiedOn'];
		$missionListItem_ModifiedByID = $row1['ModifiedBy'];
		$missionListItem_ModifiedByName = $row1['ModifiedByName'];
		
		if ($missionListItem_ID == $MissionID)
		{
			$display_missionsList .= "
				<div class=\"operationsListItemContainer missions_selected\"
					data-operationid=\"$missionListItem_ID\"
					data-targetid=\"http://sc.vvarmachine.com/mission/$missionListItem_ID\"
				>
			";
		}
		else
		{
			$display_missionsList .= "
				<div class=\"operationsListItemContainer\"
					data-missionid=\"$missionListItem_ID\"
					data-targetid=\"http://sc.vvarmachine.com/mission/$missionListItem_ID\"
					>
			";		
		}
		
		$display_missionsList .= "
					<div class=\"operationsListItem_Title\">
						<a href=\"http://sc.vvarmachine.com/mission/$missionListItem_ID\">
							$missionListItem_Number - $missionListItem_Name
						</a>
					</div>
					<div class=\"operationsListItem_Type\">
						Type: 
						<div class=\"operationsListItem_Type_Value\">
							$missionListItem_Type
						</div>
					</div>
					<div class=\"operationsListItem_Mission\">
						StartDate:
						<strong style=\"
							font-size: 9pt;
							font-style: oblique;
							color: rgba(85, 160, 175, 0.9);
						\">
							$missionListItem_StartDate
						</strong>
					</div>
					<div class=\"operationsListItem_Mission\" style=\"
						width: auto;
						text-overflow: unset;
						white-space: unset;
					\">
						$missionListItem_Mission
					</div>
					<!--
					<div class=\"operationsListItem_MetaData_Right\">
						<div class=\"clickableRow_memRank_inner\" style=\"
							width: 100%
						\">
							Modified: $missionListItem_ModifiedOn						
						</div>
					</div>
					-->
				</div>
		";
	}	
	//End Missions List
?>

<?
	//Date-Sorted Missions for List Page
		
	$display_dateSorted_missionsList = "";
	
	$missionsList_query = "
		select
			o.MissionID
			,CONCAT('OPT_',(1000 + o.OpTemplateID),'-',(1000 + o.MissionID)) as MissionNumber
			,o.MissionName
			,o.MissionType
			,o.Mission
			,lk1.Description as MissionStatus
			,lk2.Description as MissionOutcome
			,o.StartDate
			,DATE_FORMAT(DATE(o.ModifiedOn),'%d %b %Y') as ModifiedOn
			,o.ModifiedBy
			,m.mem_callsign as ModifiedByName
			,case
				when o.StartDate < UTC_TIMESTAMP() then 'Past'
				when DAY(UTC_TIMESTAMP()) = DAY(o.StartDate) then 'Today'
				when WEEK(UTC_TIMESTAMP()) = WEEK(o.StartDate) then 'This Week'
				when MONTH(UTC_TIMESTAMP()) = MONTH(o.StartDate) then 'This Month'
				else 'Future'
			end as DateGrouping
			,case
				when o.StartDate < UTC_TIMESTAMP() then '4'
				when DAY(UTC_TIMESTAMP()) = DAY(o.StartDate) then '1'
				when WEEK(UTC_TIMESTAMP()) = WEEK(o.StartDate) then '2'
				when MONTH(UTC_TIMESTAMP()) = MONTH(o.StartDate) then '3'
				else 'Future'
			end as DateGroupingOrderBy
		from projectx_vvarsc2.Missions o
		join projectx_vvarsc2.members m
			on m.mem_id = o.ModifiedBy
		join projectx_vvarsc2.LK_MissionStatus lk1
			on lk1.MissionStatus = o.MissionStatus
		join projectx_vvarsc2.LK_MissionOutcome lk2
			on lk2.MissionOutcome = o.MissionOutcome
		where (
			o.StartDate >= UTC_TIMESTAMP() OR
			MONTH(UTC_TIMESTAMP()) = MONTH(o.StartDate)
			)
		order by
			13
	";
	
	$missionsList_query_result = $connection->query($missionsList_query);
		
	$previousGroup = "";
	$currentGroup = "";
	
	while(($row1 = $missionsList_query_result->fetch_assoc()) != false) {
		$missionListItem_ID = $row1['MissionID'];
		$missionListItem_Number = $row1['MissionNumber'];
		$missionListItem_Name = $row1['MissionName'];
		$missionListItem_Type = $row1['MissionType'];
		$missionListItem_Mission = $row1['Mission'];
		$missionListItem_MissionStatus = $row1['MissionStatus'];
		$missionListItem_MissionOutcome = $row1['MissionOutcome'];
		$missionListItem_StartDate = $row1['StartDate'];
		$missionListItem_DateGrouping = $row1['DateGrouping'];
		$missionListItem_ModifiedOn = $row1['ModifiedOn'];
		$missionListItem_ModifiedByID = $row1['ModifiedBy'];
		$missionListItem_ModifiedByName = $row1['ModifiedByName'];
		
		$currentGroup = $missionListItem_DateGrouping;
		//If This is a New Group, Open a New Row and Title
		if ($currentGroup != $previousGroup)
		{
			$display_dateSorted_missionsList .= "
				<h4 class=\"operations_h4\">
					$missionListItem_DateGrouping
				</h4>
			";
		
		}	
		
		$display_dateSorted_missionsList .= "
			<div class=\"operations_menu_inner_items_container\">
				<div class=\"operationsListItemContainer MissionListItem_$missionListItem_DateGrouping\"
					data-missionid=\"$missionListItem_ID\"
					data-url=\"http://sc.vvarmachine.com/mission/$missionListItem_ID\"
				>
					<div class=\"operationsListItem_Title\">
						<a href=\"http://sc.vvarmachine.com/mission/$missionListItem_ID\">
							$missionListItem_Number - $missionListItem_Name
						</a>
					</div>
					<div class=\"operationsListItem_MetaData_Right\">
						<div class=\"clickableRow_memRank_inner\" style=\"
							width: 100%
						\">
							Modified: $missionListItem_ModifiedOn						
						</div>
						Mission Status: <strong class=\"MissionStatus MissionStatus_$missionListItem_MissionStatus\">$missionListItem_MissionStatus</strong>
						<br />
						Outcome: <strong class=\"MissionOutcome MissionOutcome_$missionListItem_MissionOutcome\">$missionListItem_MissionOutcome</strong>
					</div>
					<div class=\"operationsListItem_Type\">
						Type: 
						<div class=\"operationsListItem_Type_Value\">
							$missionListItem_Type
						</div>
					</div>
					<div class=\"operationsListItem_Mission\">
						StartDate:
						<strong class=\"operation_startDate_text\">
							$missionListItem_StartDate
						</strong>
					</div>
					<div class=\"operationsListItem_Mission\" style=\"
						width: auto;
						text-overflow: unset;
						white-space: unset;
					\">
						$missionListItem_Mission
					</div>
				</div>
			</div>
		";
		$previousGroup = $currentGroup;
	}
	
	//End Missions List
?>