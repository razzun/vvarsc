<? //Operations LIST
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
			o.CreatedOn desc
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
				<div class=\"operationsListItemContainer operations_selected\" data-operationid=\"$operationListItem_ID\">
			";
		}
		else
		{
			$display_operationsList .= "
				<div class=\"operationsListItemContainer\" data-operationid=\"$operationListItem_ID\">
			";		
		}
		
		$display_operationsList .= "
					<div class=\"operationsListItem_Title\">
						<a href=\"http://sc.vvarmachine.com/operation/$operationListItem_ID\">
							$operationListItem_Number - \"$operationListItem_Name\"
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
		order by
			o.StartDate desc
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
		
		if ($missionListItem_ID == $missionID)
		{
			$display_missionsList .= "
				<div class=\"operationsListItemContainer missions_selected\" data-operationid=\"$missionListItem_ID\">
			";
		}
		else
		{
			$display_missionsList .= "
				<div class=\"operationsListItemContainer\" data-missionid=\"$missionListItem_ID\">
			";		
		}
		
		$display_missionsList .= "
					<div class=\"operationsListItem_Title\">
						<a href=\"http://sc.vvarmachine.com/mission/$missionListItem_ID\">
							$missionListItem_Number - \"$missionListItem_Name\"
						</a>
					</div>
					<div class=\"operationsListItem_Type\">
						Type: 
						<div class=\"operationsListItem_Type_Value\">
							$missionListItem_Type
						</div>
					</div>
					<div class=\"operationsListItem_Mission\">
						StartDate: $missionListItem_StartDate
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
