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
			,r.rank_tinyImage as ModifiedByRankImage
		from projectx_vvarsc2.OpTemplates o
		join projectx_vvarsc2.members m
			on m.mem_id = o.ModifiedBy
		join projectx_vvarsc2.ranks r
			on r.rank_id = m.ranks_rank_id
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
		$operationListItem_ModifiedByRankImage = $row1['ModifiedByRankImage'];
		
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
					<div class=\"operationsListItem_MetaData_Right\">
						<div class=\"clickableRow_memRank_inner\">
							<div class=\"operations_rank_image_text\" style=\"
								font-size: 8pt;
								display: block;
							\">
								Last Modified: $operationListItem_ModifiedOn
							</div>
							<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$operationListItem_ModifiedByRankImage.png\"/ style=\"
								display: inline;
							\">
							<div class=\"operations_rank_image_text\" style=\"
								display: inline;
								padding-left: 0px;
							\">
								<a href=\"http://sc.vvarmachine.com/player/$operationListItem_ModifiedByID\" style=\"
									padding-left: 4px;
								\">
									$operationListItem_ModifiedByName
								</a>
							</div>							
						</div>
					</div>
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
				<div class=\"operationsListItemContainer operations_selected\"
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
	
	//Happening Now
	$missionsList_query = "
		select
			o.MissionID
			,CONCAT('OPT_',(1000 + o.OpTemplateID),'-',(1000 + o.MissionID)) as MissionNumber
			,o.MissionName
			,o.MissionType
			,o.Mission
			,lk1.Description as MissionStatus
			,o.StartDate
			,o.EndDate
			,DATE_FORMAT(DATE(o.ModifiedOn),'%d %b %Y') as ModifiedOn
			,o.ModifiedBy
			,m.mem_callsign as ModifiedByName
			,'Currently Active' as DateGrouping
			,'1' as DateGroupingOrderBy
		from projectx_vvarsc2.Missions o
		join projectx_vvarsc2.members m
			on m.mem_id = o.ModifiedBy
		join projectx_vvarsc2.LK_MissionStatus lk1
			on lk1.MissionStatus = o.MissionStatus
		where (
			o.StartDate <= UTC_TIMESTAMP()
			and o.EndDate > UTC_TIMESTAMP()
			and ($unit_id = 0 or o.MissionID in (
				select
					u.MissionID
				from projectx_vvarsc2.MissionUnits u
				where u.MissionID = o.MissionID
					and u.UnitID = $unit_id
			))			
		)
		order by
			13
			,o.StartDate
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
		$missionListItem_StartDate = $row1['StartDate'];
		$missionListItem_EndDate = $row1['EndDate'];
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
					<div class=\"operationsListItem_MetaData_Right\">
						<div class=\"clickableRow_memRank_inner\" style=\"
							width: 100%
						\">
							Modified: $missionListItem_ModifiedOn						
						</div>
						Mission Status: <strong class=\"MissionStatus MissionStatus_$missionListItem_MissionStatus\">$missionListItem_MissionStatus</strong>
					</div>
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
						<strong class=\"operation_startDate_text\">
							$missionListItem_StartDate
						</strong>
						<br />
						EndDate:
						<strong class=\"operation_startDate_text\">
							$missionListItem_EndDate
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
	
	//Upcoming Missions
	$missionsList_query1 = "
		select
			o.MissionID
			,CONCAT('OPT_',(1000 + o.OpTemplateID),'-',(1000 + o.MissionID)) as MissionNumber
			,o.MissionName
			,o.MissionType
			,o.Mission
			,lk1.Description as MissionStatus
			,o.StartDate
			,o.EndDate
			,DATE_FORMAT(DATE(o.ModifiedOn),'%d %b %Y') as ModifiedOn
			,o.ModifiedBy
			,m.mem_callsign as ModifiedByName
			,case
				when DAY(UTC_TIMESTAMP()) = DAY(o.StartDate) then 'Upcoming - Today'
				when WEEK(UTC_TIMESTAMP()) = WEEK(o.StartDate) then 'Upcoming - This Week'
				when MONTH(UTC_TIMESTAMP()) = MONTH(o.StartDate) then 'Upcoming - This Month'
				else 'Upcoming - Future'
			end as DateGrouping
			,case
				when DAY(UTC_TIMESTAMP()) = DAY(o.StartDate) then '1'
				when WEEK(UTC_TIMESTAMP()) = WEEK(o.StartDate) then '2'
				when MONTH(UTC_TIMESTAMP()) = MONTH(o.StartDate) then '3'
				else '4'
			end as DateGroupingOrderBy
		from projectx_vvarsc2.Missions o
		join projectx_vvarsc2.members m
			on m.mem_id = o.ModifiedBy
		join projectx_vvarsc2.LK_MissionStatus lk1
			on lk1.MissionStatus = o.MissionStatus
		where (
			o.StartDate >= UTC_TIMESTAMP()
			and ($unit_id = 0 or o.MissionID in (
				select
					u.MissionID
				from projectx_vvarsc2.MissionUnits u
				where u.MissionID = o.MissionID
					and u.UnitID = $unit_id
			))			
		)
		order by
			13
			,o.StartDate
	";
	
	$missionsList_query1_result = $connection->query($missionsList_query1);
		
	$previousGroup = "";
	$currentGroup = "";
	
	while(($row1 = $missionsList_query1_result->fetch_assoc()) != false) {
		$missionListItem_ID = $row1['MissionID'];
		$missionListItem_Number = $row1['MissionNumber'];
		$missionListItem_Name = $row1['MissionName'];
		$missionListItem_Type = $row1['MissionType'];
		$missionListItem_Mission = $row1['Mission'];
		$missionListItem_MissionStatus = $row1['MissionStatus'];
		$missionListItem_StartDate = $row1['StartDate'];
		$missionListItem_EndDate = $row1['EndDate'];
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
					<div class=\"operationsListItem_MetaData_Right\">
						<div class=\"clickableRow_memRank_inner\" style=\"
							width: 100%
						\">
							Modified: $missionListItem_ModifiedOn						
						</div>
						Mission Status: <strong class=\"MissionStatus MissionStatus_$missionListItem_MissionStatus\">$missionListItem_MissionStatus</strong>
					</div>
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
	
	//Past Missions
	$missionsList_query2 = "
		select
			o.MissionID
			,CONCAT('OPT_',(1000 + o.OpTemplateID),'-',(1000 + o.MissionID)) as MissionNumber
			,o.MissionName
			,o.MissionType
			,o.Mission
			,lk1.Description as MissionStatus
			,o.StartDate
			,o.EndDate
			,DATE_FORMAT(DATE(o.ModifiedOn),'%d %b %Y') as ModifiedOn
			,o.ModifiedBy
			,m.mem_callsign as ModifiedByName
			,case
				when o.StartDate < UTC_TIMESTAMP() then 'Past'
			end as DateGrouping
			,case
				when o.StartDate < UTC_TIMESTAMP() then '5'
			end as DateGroupingOrderBy
		from projectx_vvarsc2.Missions o
		join projectx_vvarsc2.members m
			on m.mem_id = o.ModifiedBy
		join projectx_vvarsc2.LK_MissionStatus lk1
			on lk1.MissionStatus = o.MissionStatus
		where (
			o.EndDate < UTC_TIMESTAMP()
			and ($unit_id = 0 or o.MissionID in (
				select
					u.MissionID
				from projectx_vvarsc2.MissionUnits u
				where u.MissionID = o.MissionID
					and u.UnitID = $unit_id
			))
		)
		order by
			o.StartDate desc
	";
	
	$missionsList_query2_result = $connection->query($missionsList_query2);
		
	$previousGroup = "";
	$currentGroup = "";
	
	while(($row1 = $missionsList_query2_result->fetch_assoc()) != false) {
		$missionListItem_ID = $row1['MissionID'];
		$missionListItem_Number = $row1['MissionNumber'];
		$missionListItem_Name = $row1['MissionName'];
		$missionListItem_Type = $row1['MissionType'];
		$missionListItem_Mission = $row1['Mission'];
		$missionListItem_MissionStatus = $row1['MissionStatus'];
		$missionListItem_StartDate = $row1['StartDate'];
		$missionListItem_EndDate = $row1['EndDate'];
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
					<div class=\"operationsListItem_MetaData_Right\">
						<div class=\"clickableRow_memRank_inner\" style=\"
							width: 100%
						\">
							Modified: $missionListItem_ModifiedOn						
						</div>
						Mission Status: <strong class=\"MissionStatus MissionStatus_$missionListItem_MissionStatus\">$missionListItem_MissionStatus</strong>
					</div>
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