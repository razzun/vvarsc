<?php include_once('functions/function_auth_user.php'); ?>
<?php include_once('inc/OptionQueries/unit_queries.php'); ?>
<?php include_once('inc/OptionQueries/ship_queries.php'); ?>

<?
	$OperationID = null;
	$unit_id = 0;
	$MissionID = strip_tags(isset($_GET['pid']) ? $_GET['pid'] : '');
	$canEdit = false;
	$CurrentUserID = $_SESSION['sess_user_id'];
	$CurrentUserAssigned = 'false';
	$MaintainEdit = 'false';
	
	if (($_SESSION['sess_userrole'] == "admin") ||
			($_SESSION['sess_userrole'] == "officer"))
	{
		$canEdit = true;
	}
	
	if ($_SESSION['maintain_edit'] == 'true')
		$MaintainEdit = 'true';
	else
		$MaintainEdit = 'false';
		
	//Check if Current User is Assigned to a Unit/Ship for this Operation
	$userCheckQuery = "
		select
			m.MemberID
		from projectx_vvarsc2.MissionUnitMembers m
		where m.MissionID = $MissionID
			and m.MemberID = $CurrentUserID
			and m.MemberID is not null
		union
		select
			m.MemberID
		from projectx_vvarsc2.MissionShipMembers m
		where m.MissionID = $MissionID
			and m.MemberID = $CurrentUserID
			and m.MemberID is not null
	";
	
	$userCheckQuery_results = $connection->query($userCheckQuery);
	
	while(($checkRow = $userCheckQuery_results->fetch_assoc()) != false) {
		$memberID = $checkRow['MemberID'];
		
		if ($memberID == $CurrentUserID)
			$CurrentUserAssigned = 'true';
	}
?>


<?php include_once('inc/OptionQueries/member_queries.php'); ?>
<?php include_once('inc/ListQueries/operation_queries.php'); ?>

<?
	//OPERATION DETAILS
	$display_operationDetails = "";
	
	$operationDetails_query = "
		select
			o.MissionID
			,o.OpTemplateID
			,o.MissionName
			,CONCAT('OPT_',(1000 + o.OpTemplateID),'-',(1000 + o.MissionID)) as MissionNumber
			,o.MissionType
			,o.StartingLocation
			,o.StartDate
			,o.EndDate
			,o.Mission
			,TRIM(LEADING '\t' from o.Description) as Description
			,lk1.MissionStatus as MissionStatusID
			,lk1.Description as MissionStatusDescrption
			,o.CreatedBy as CreatedByID
			,DATE_FORMAT(DATE(o.CreatedOn),'%d %b %Y') as CreatedOn
			,CONCAT(r.rank_abbr, ' ', m.mem_callsign) as CreatedByName
			,r.rank_tinyImage as CreatedByRankImage
			,o.ModifiedBy as ModifiedByID
			,DATE_FORMAT(DATE(o.ModifiedOn),'%d %b %Y') as ModifiedOn
			,CONCAT(r2.rank_abbr, ' ', m2.mem_callsign) as ModifiedByName
			,r2.rank_tinyImage as ModifiedByRankImage
		from projectx_vvarsc2.Missions o
		join projectx_vvarsc2.members m
			on m.mem_id = o.CreatedBy
		join projectx_vvarsc2.ranks r
			on r.rank_id = m.ranks_rank_id
		join projectx_vvarsc2.members m2
			on m2.mem_id = o.ModifiedBy
		join projectx_vvarsc2.ranks r2
			on r2.rank_id = m2.ranks_rank_id
		join projectx_vvarsc2.LK_MissionStatus lk1
			on lk1.MissionStatus = o.MissionStatus
		where o.MissionID = '$MissionID'
	";
	$operationDetails_query_result = $connection->query($operationDetails_query);
	
	while(($row2 = $operationDetails_query_result->fetch_assoc()) != false) {
		$operationDetails_ID = $row2['MissionID'];
		$operationDetails_OpTemplateID = $row2['OpTemplateID'];
		$operationDetails_Name = $row2['MissionName'];
		$operationDetails_Number = $row2['MissionNumber'];
		$operationDetails_Type = $row2['MissionType'];
		$operationDetails_StartingLocation = $row2['StartingLocation'];
		$operationDetails_StartDate = $row2['StartDate'];
		$operationDetails_EndDate = $row2['EndDate'];
		$operationDetails_Mission = $row2['Mission'];
		$operationDetails_Description = $row2['Description'];
		
		$operationDetails_MissionStatusID = $row2['MissionStatusID'];
		$operationDetails_MissionStatusDescrption = $row2['MissionStatusDescrption'];
		
		$display_operationDetails_Mission = nl2br($operationDetails_Mission);
		$display_operationDetails_Description = nl2br($operationDetails_Description);
		
		$operationDetails_CreatedByID = $row2['CreatedByID'];
		$operationDetails_CreatedOn = $row2['CreatedOn'];
		$operationDetails_CreatedByName = $row2['CreatedByName'];
		$operationDetails_CreatedByRankImage = $row2['CreatedByRankImage'];
		
		$operationDetails_ModifiedByID = $row2['ModifiedByID'];
		$operationDetails_ModifiedOn = $row2['ModifiedOn'];
		$operationDetails_ModifiedByName = $row2['ModifiedByName'];
		$operationDetails_ModifiedByRankImage = $row2['ModifiedByRankImage'];
	}
	
?>

<?php include_once('inc/OptionQueries/lk_mission_queries.php'); ?>

<?
	
	if ($MissionID != null)
	{
	
		$displayMainActionButtons = "";
		if ($canEdit)
		{
			$displayMainActionButtons = "
				<div id=\"MainActionButtons\" style=\"
					display: table-cell;
				\">
					<button id=\"ButtonEditToggle\" class=\"adminButton adminButtonEdit\" title=\"Edit Toggle\"style=\"
						margin-left: 0px;
						margin-right: 2%;
						margin-top: 0px;
						margin-bottom: 0px;
					\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
						Toggle Edit Options
					</button>				
				</div>
			";
		}
		
		$display_operation_edit = "";
		if ($canEdit)
		{
			$display_operation_edit = "
				<div id=\"OperationButtons\" style=\"
					float: right;
					text-align: right;
					margin-right: 8px;
					width: 50%;
				\">
					<button id=\"ButtonEditOperation\" class=\"adminButton adminButtonEdit\" title=\"Edit Mission Plan\"style=\"
						margin: 0px;
					\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
					</button>
					<button id=\"ButtonDeleteOperation\" class=\"adminButton adminButtonDelete\" title=\"Delete Mission Plan\"style=\"
						margin: 0px;
					\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
					</button>
				</div>
			";
		}		
		
		$display_operationDetails = "
			$display_operation_edit
			<h3 class=\"operations_h3\">
				\"$operationDetails_Name\"
			</h3>
			<h5 class=\"operations_h5\" style=\"
				background: url($link_base/images/public-profile-box-title.png) repeat-x;
			\">
				$operationDetails_Number // $operationDetails_Type
			</h5>
				
			<div class=\"operationsListItem_MetaData_Right\">
				<div class=\"clickableRow_memRank_inner\" style=\"
					display: inline-table;
				\">
					<div class=\"operations_rank_image_text\" style=\"
						padding-right: 8px;
					\">
						Created: $operationDetails_CreatedOn
					</div>
					<img class=\"clickableRow_memRank_Image\" src=\"$link_base/images/ranks/TS3/$operationDetails_CreatedByRankImage.png\"/>
					<div class=\"operations_rank_image_text\">
						<a href=\"$link_base/player/$operationDetails_CreatedByID\" style=\"
							padding-left: 4px;
							font-weight: normal;
						\">
							$operationDetails_CreatedByName
						</a>
					</div>					
				</div>
			</div>
			
			<h4 class=\"operations_h4\">
				Mission Data
			</h4>
			<div class=\"operationsListItem_MetaData_Right\">
				<div class=\"clickableRow_memRank_inner\" style=\"
					display: inline-table;
				\">
					<div class=\"operations_rank_image_text\" style=\"
						padding-right: 8px;
					\">
						Last Modified: $operationDetails_ModifiedOn
					</div>
					<img class=\"clickableRow_memRank_Image\" src=\"$link_base/images/ranks/TS3/$operationDetails_ModifiedByRankImage.png\"/>
					<div class=\"operations_rank_image_text\">
						<a href=\"$link_base/player/$operationDetails_ModifiedByID\" style=\"
							padding-left: 4px;
							font-weight: normal;
						\">
							$operationDetails_ModifiedByName
						</a>
					</div>
				</div>
			</div>
			<div class=\"WikiText OperationText\" style=\"
				background: none;
				margin-left: 0px;
			\">
				Mission Status: <strong class=\"MissionStatus MissionStatus_$operationDetails_MissionStatusDescrption\">
					$operationDetails_MissionStatusDescrption
				</strong>
				<br />
				StartDate (UTC): <strong class=\"operation_startDate_text\">$operationDetails_StartDate</strong>
				<br />
				EndDate (UTC): <strong class=\"operation_startDate_text\">$operationDetails_EndDate</strong>
			</div>
			
			<h4 class=\"operations_h4\">
				Mission Objectives
			</h4>
			<div class=\"WikiText OperationText\" style=\"
				background: none;
				margin-left: 0px;
			\">
				$display_operationDetails_Mission
			</div>
			
			<h4 class=\"operations_h4\">
				Description and Details
			</h4>
					
			<div class=\"shipDetails_info1_table_ship_desc\" style=\"
				font-style: normal;
				margin-top: 0px;
				padding-bottom: 12px;
				background-color: rgba(0, 0, 0, 0.75);
			\">
				<div class=\"corner corner-top-left\">
				</div>
				<div class=\"corner corner-top-right\">
				</div>
				<div class=\"corner corner-bottom-left\">
				</div>
				<div class=\"corner corner-bottom-right\">
				</div>			
				
				<h5 class=\"operations_h5\" style=\"
					padding-left: 0px;
					margin-top: 4px;
				\">
					Starting Location
				</h5>
				<div class=\"WikiText OperationText\" style=\"
					margin-left: 0px;
					background: none;
				\">
					$operationDetails_StartingLocation
				</div>
				
				<h5 class=\"operations_h5\" style=\"
					padding-left: 0px;
					margin-top: 4px;
				\">
					Objective Details
				</h5>
				<div class=\"WikiText OperationText\" style=\"
					margin-left: 0px;
					background: none;
				\">
					$display_operationDetails_Description
				</div>
			</div>
		";
		
		//OPERATION UNITS
		$display_opUnits_list_edit = "";
		/*
		if ($canEdit)
		{
			$display_opUnits_list_edit = "
				<div id=\"OpUnitsListButtons\" style=\"
					width: 100%;
					text-align: right;
				\">
					<button id=\"ButtonAddOpUnit\" class=\"adminButton adminButtonCreate\" title=\"Add Unit\"style=\"
						margin-left: 0px;
						margin-right: 2%;
					\">
						<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
						Add Unit
					</button>
				</div>
			";
		}
		*/
		
		
		$display_opUnits_list = "
			<div class=\"unit_description_container\" style=\"
				background-image: none;
				background-color: rgba(0,0,0,0.0);
				font-size: 10pt;
				color: #A2B5D2;
			\">	
				<div class=\"top-line\">
				</div>
				<div class=\"corner4 corner-diag-blue-topLeft\">
				</div>
				<div class=\"corner4 corner-diag-blue-topRight\">
				</div>
				<div class=\"corner4 corner-diag-blue-bottomLeft\">
				</div>
				<div class=\"corner4 corner-diag-blue-bottomRight\">
				</div>
				<h4 class=\"operations_h4\" style=\"
					padding-top: 0px;
				\">
					Operational Units
				</h4>
				<div class=\"PayGradeDetails\">
		";
		
		$operationUnits_query = "
			select
				ou.MissionUnitID
				,ou.MissionUnitType
				,lk1.OpUnitTypeDescription
				,lk1.Support_AirFlight
				,lk1.Support_GroundTeam
				,TRIM(LEADING '\t' from ou.MissionUnitObjectives) as MissionUnitObjectives
				,u.UnitID
				,u.UnitLevel as UnitType
				,case
					when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
					else u.UnitFullName
				end as UnitName
				,ou.PackageNumber
				,u.UnitCallSign as UnitCallSign
				,ou.CallSign as OpUnitCallsign
                ,( select
						CONCAT(s.ship_asset_designationCode, '-', shm.rowID) as CapitalAssetDesignation
                    from projectx_vvarsc2.ships_has_members shm
                    join projectx_vvarsc2.ships s
						on s.ship_id = shm.ships_ship_id
                    where shm_assetName = ou.Callsign
                ) as CapitalAssetDesignation
				,(select COUNT(1) from projectx_vvarsc2.MissionShips ms
					where ms.MissionUnitID = ou.MissionUnitID) as ShipCount
			from projectx_vvarsc2.MissionUnits ou
			join projectx_vvarsc2.Units u
				on u.UnitID = ou.UnitID
			join projectx_vvarsc2.LK_OpUnitTypes lk1
				on lk1.OpUnitTypeID = ou.MissionUnitType
			where ou.MissionID = $MissionID
			order by
				lk1.OpUnitType_OrderBy
				,u.UnitLevel
				,u.UnitName
				,ou.MissionUnitID
		";
		$operationUnits_query_result = $connection->query($operationUnits_query);
		
		$CurrentUnitID = "";
		$UnitIndex = 1;
		
		while(($row3 = $operationUnits_query_result->fetch_assoc()) != false) {
			$opUnitsListItem_OpUnitID = $row3['MissionUnitID'];
			$opUnitsListItem_OpUnitTypeID = $row3['MissionUnitType'];
			$opUnitsListItem_OpUnitTypeDescription = $row3['OpUnitTypeDescription'];
			$opUnitsListItem_Support_AirFlight = $row3['Support_AirFlight'];
			$opUnitsListItem_Support_GroundTeam = $row3['Support_GroundTeam'];
			$opUnitsListItem_OpUnitObjectives = nl2br($row3['MissionUnitObjectives']);
			$opUnitsListItem_UnitID = $row3['UnitID'];
			$opUnitsListItem_UnitName = $row3['UnitName'];
			$opUnitsListItem_UnitType = $row3['UnitType'];
			$opUnitsListItem_PackageNumber = $row3['PackageNumber'];
			$opUnitsListItem_UnitCallSign = $row3['UnitCallSign'];
			$opUnitsListItem_OpUnitCallsign = $row3['OpUnitCallsign'];
			$CapitalAssetDesignation = $row3['CapitalAssetDesignation'];
			
			$callSign = "";
			//Callsign Logic
			if ($opUnitsListItem_UnitType == 'Squadron' || $opUnitsListItem_UnitType == 'QRF' || $opUnitsListItem_UnitType == 'Platoon')
			{
				//If Previous OrgUnit is same as Current OrgUnit, change callsign to be unique.
				if ($CurrentUnitID == $opUnitsListItem_UnitID)
				{
					$UnitIndex++;
					if ($opUnitsListItem_OpUnitCallsign == null || $opUnitsListItem_OpUnitCallsign == '')
					{
						$callSign = ($opUnitsListItem_UnitCallSign.' '.$UnitIndex);
					}
					else
					{
						$callSign = ($opUnitsListItem_OpUnitCallsign.' '.$UnitIndex);
					}
				}
				else
				{
					$UnitIndex = 1;
					if ($opUnitsListItem_OpUnitCallsign == null || $opUnitsListItem_OpUnitCallsign == '')
					{
						$callSign = $opUnitsListItem_UnitCallSign.' 1';
					}
					else
					{
						$callSign = $opUnitsListItem_OpUnitCallsign;
					}
				}
			}
			else if ($opUnitsListItem_OpUnitTypeDescription == 'Capital Ship')
			{
				$callSign = $opUnitsListItem_OpUnitCallsign;
			}
			//Callsign Logic for all other Unit Types
			else
			{
				if ($opUnitsListItem_OpUnitCallsign == null || $opUnitsListItem_OpUnitCallsign == '')
				{
					$callSign = $opUnitsListItem_UnitCallSign;
				}
				else
				{
					$callSign = $opUnitsListItem_OpUnitCallsign;
				}
			}
			
			$display_opUnits_list .= "					
				<div class=\"table_header_block\">
				</div>
				<div class=\"yard_filters\" style=\"
					margin-bottom: 16px;
					border-spacing: 0px;
				\"
					data-opunitid=$opUnitsListItem_OpUnitID
					data-operationid=$MissionID
					data-opunittype=$opUnitsListItem_OpUnitTypeID
					data-unitid=$opUnitsListItem_UnitID
					data-unittype=$opUnitsListItem_UnitType
					data-supportairflight=$opUnitsListItem_Support_AirFlight
					data-supportgroundteam=$opUnitsListItem_Support_GroundTeam
					data-callsign=\"$opUnitsListItem_OpUnitCallsign\"
				>
			";
			
			$display_opUnit_edit = "";
			if ($canEdit &&
				($opUnitsListItem_UnitType == 'Squadron' or $opUnitsListItem_UnitType== 'Platoon'
					or $opUnitsListItem_OpUnitTypeDescription == 'Capital Ship'))
			{
				$display_opUnit_edit = "
					<div id=\"OpUnitsButtons_$opUnitsListItem_OpUnitID\" style=\"
						float: right;
						text-align: right;
						margin-right: 8px;
						width: 50%;
						margin-top: 4px;
					\">
						<button id=\"ButtonEditOpUnit_$opUnitsListItem_OpUnitID\" class=\"adminButton adminButtonEdit ButtonEditOpUnit\" title=\"Edit Unit\"style=\"
							margin: 0px;
						\">
							<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
						</button>
						<!--
						<button id=\"ButtonDeleteOpUnit_$opUnitsListItem_OpUnitID\" class=\"adminButton adminButtonDelete ButtonDeleteOpUnit\" title=\"Delete Unit\"style=\"
							margin: 0px;
						\">
							<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
						</button>
						-->
					</div>
				";
			}
		
			$display_opUnits_list .= "
					$display_opUnit_edit
					<div class=\"PayGradeDetails_Entry_Header\" style=\"
						cursor: pointer;
						vertical-align: middle;
						margin-top: 6px;
						display: table;
					\">
						<img class=\"shipyard_mainTable_row_header_arrow\" style=\"display: table-cell;\" src=\"".$link_base."/images/SC_Button01.png\" align=\"middle\">
						<h5 class=\"operations_h5\" style=\"
							padding-left: 8px;
							padding-right: 8px;
							margin: 0;
							font-size: 12pt;
							font-style: italic;
							cursor: pointer;
							display: table-cell;
							vertical-align: middle;
						\">
							$opUnitsListItem_OpUnitTypeDescription
						</h5>";
						if ($opUnitsListItem_OpUnitTypeDescription == 'Capital Ship' &&
							$callSign != null && $CapitalAssetDesignation != null)
						{
							$display_opUnits_list .= "
						<div class=\"OperationText_Hideable\"style=\"
							padding-left: 8px;
							padding-right: 8px;
							margin: 0;
							font-style: italic;
							display: table-cell;
							vertical-align: middle;
						\">
							<strong style=\"
							color: #DDD;
							text-shadow: 0px 0px 4px #00E0FF;
							\">VMNS $callSign</strong>
						</div>";
						}
						else if ($opUnitsListItem_OpUnitTypeDescription == 'Capital Ship' &&
							$callSign != null)
						{
							$display_opUnits_list .= "
						<div class=\"OperationText_Hideable\"style=\"
							padding-left: 8px;
							padding-right: 8px;
							margin: 0;
							font-style: italic;
							display: table-cell;
							vertical-align: middle;
						\">
							<strong style=\"
							color: #DDD;
							text-shadow: 0px 0px 4px #00E0FF;
							\">$callSign</strong>
						</div>";
						}
						else if ($callSign != null)
						{
								$display_opUnits_list .= "
						<div class=\"OperationText_Hideable\"style=\"
							padding-left: 8px;
							padding-right: 8px;
							margin: 0;
							font-style: italic;
							display: table-cell;
							vertical-align: middle;
						\">
							$opUnitsListItem_UnitName - <strong style=\"
							color: #DDD;
							text-shadow: 0px 0px 4px #00E0FF;
							\">$callSign</strong>
						</div>";						
						}
					$display_opUnits_list .= "
					</div>
					<div class=\"shipyard_mainTable_row_content\" style=\"
						padding-top: 0px;
						border-top: none;
						width: 100%;
					\">";
						if ($opUnitsListItem_OpUnitTypeDescription == 'Capital Ship' &&
							$callSign != null && $CapitalAssetDesignation != null)
						{
						$display_opUnits_list .= "
						<div class=\"WikiText OperationText\" style=\"
							margin-left: 8px;
							background: none;
						\">
							Asset Registration & Callsign: 
							<strong style=\"
								color: #DDD;
								font-style: italic;
								text-shadow: 0px 0px 4px #00E0FF;
							\">$CapitalAssetDesignation // VMNS $callSign</strong>
						</div>";
						}
						else if ($opUnitsListItem_OpUnitTypeDescription == 'Capital Ship' &&
							$callSign != null)
						{
						$display_opUnits_list .= "
						<div class=\"WikiText OperationText\" style=\"
							margin-left: 8px;
							background: none;
						\">
							Callsign: 
							<strong style=\"
								color: #DDD;
								font-style: italic;
								text-shadow: 0px 0px 4px #00E0FF;
							\">$callSign</strong>
						</div>";
						}
						else if ($callSign != null)
						{
						$display_opUnits_list .= "
						<div class=\"WikiText OperationText\" style=\"
							margin-left: 8px;
							background: none;
						\">
							Source Unit: <a href=\"$link_base/unit/$opUnitsListItem_UnitID\"><strong>$opUnitsListItem_UnitName</strong></a>
							<br />
							Callsign: <strong style=\"
								color: #DDD;
								font-style: italic;
								text-shadow: 0px 0px 4px #00E0FF;
							\">$callSign</strong>
						</div>";						
						}
			
			//Objectives for Unit
			if ($opUnitsListItem_OpUnitObjectives != null && $opUnitsListItem_OpUnitObjectives != "")
			{
				$display_opUnits_list .= "
					<h6 class=\"operations_h6\" style=\"
						margin-left: 8px;
						margin-top: 4px;
					\">
						Objectives
					</h6>
					<div class=\"WikiText OperationText OpUnitObjectives\" style=\"
						margin-left: 8px;
						background: none;
					\">$opUnitsListItem_OpUnitObjectives</div>
				";
			}
			
			
			if ($opUnitsListItem_Support_GroundTeam == "1")
			{
				$display_opUnitMembers_list_edit = "";
				if ($canEdit)
				{
					$display_opUnitMembers_list_edit = "
						<div id=\"OpUnitMembersListButtons_$opUnitsListItem_OpUnitID\" style=\"
							float: right;
							text-align: right;
							margin-right: 8px;
						\">
							<button id=\"ButtonAddOpUnitMember_$opUnitsListItem_OpUnitID\" class=\"adminButton adminButtonCreate ButtonAddOpUnitMember\" title=\"Add Member\"style=\"
								margin-left: 0px;
								margin-right: 2%;
							\">
								<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
								Add Member
							</button>
						</div>
					";
				}
				
				//GET MEMBERS FOR THIS OpUnit
				$display_opUnit_member_list = "
					$display_opUnitMembers_list_edit
					<div class=\"OpUnitMemberList\" style=\"padding-bottom: 8px;\">
						<h6 class=\"operations_h6\">
							Personnel
						</h6>
						<div class=\"OpUnit_Members\">				
				";
				
				$opUnitMember_query = "
					select
						om.RowID
						,om.MissionUnitID
						,om.MemberID
						,om.OpUnitMemberRoleID
						,om.RoleName
						,om.RoleOrderBy
						,CONCAT(om.rank_abbr, ' ', om.mem_name) as mem_name
						,om.rank_tinyImage
						,om.rank_orderBy
					from
					(
						select
							om.RowID
							,om.MissionUnitID
							,om.MemberID
							,om.OpUnitMemberRoleID
							,mr.RoleName
							,mr.RoleOrderBy
							,m.mem_callsign as mem_name
							,r.rank_abbr
							,r.rank_tinyImage
							,r.rank_orderBy
							,0 as member_orderBy
						from projectx_vvarsc2.MissionUnitMembers om
						join projectx_vvarsc2.members m
							on m.mem_id = om.MemberID
						join projectx_vvarsc2.ranks r
							on r.rank_id = m.ranks_rank_id
						join projectx_vvarsc2.OpUnitTypeMemberRoles mr
							on mr.OpUnitMemberRoleID = om.OpUnitMemberRoleID
						where om.MissionUnitID = $opUnitsListItem_OpUnitID

						union
						select
							om.RowID
							,om.MissionUnitID
							,om.MemberID
							,om.OpUnitMemberRoleID
							,mr.RoleName
							,mr.RoleOrderBy
							,null as mem_name
							,null as rank_abbr
							,null as rank_tinyImage
							,null as rank_orderBy
							,1 as member_orderBy
						from projectx_vvarsc2.MissionUnitMembers om
						join projectx_vvarsc2.OpUnitTypeMemberRoles mr
							on mr.OpUnitMemberRoleID = om.OpUnitMemberRoleID
						where om.MissionUnitID = $opUnitsListItem_OpUnitID
							and om.MemberID is null
					) om
					order by
						om.RoleOrderBy
						,om.RoleName
						,om.member_orderBy
						,om.rank_orderBy
						,om.mem_name
				";
				
				$opUnitMember_query_result = $connection->query($opUnitMember_query);
				while(($row5 = $opUnitMember_query_result->fetch_assoc()) != false) {
					$opUnitMemberListItem_RowID = $row5['RowID'];
					$opUnitMemberListItem_OpUnitID = $row5['MissionUnitID'];
					$opUnitMemberListItem_MemberID = $row5['MemberID'];
					$opUnitMemberListItem_MemName = $row5['mem_name'];
					$opUnitMemberListItem_OpUnitMemberRoleID = $row5['OpUnitMemberRoleID'];
					$opUnitMemberListItem_RoleName = $row5['RoleName'];
					$opUnitMemberListItem_RoleOrderBy = $row5['RoleOrderBy'];
					$opUnitMemberListItem_RankImage = $row5['rank_tinyImage'];
					
					$display_opUnitMember_edit = "";
					if ($canEdit)
					{
						$display_opUnitMember_edit = "
							<div id=\"OpUnitMemberButtons_$opUnitMemberListItem_RowID\" style=\"
								text-align: left;
								margin-left: 8px;
								display: table-cell;
							\">
								<button id=\"ButtonEditOpUnitMember_$opUnitMemberListItem_RowID\" class=\"adminButton adminButtonEdit ButtonEditOpUnitMember\" title=\"Edit OpUnitMember\"style=\"
									margin: 0px;
								\">
									<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
								</button>
								<button id=\"ButtonDeleteOpUnitMember_$opUnitMemberListItem_RowID\" class=\"adminButton adminButtonDelete ButtonDeleteOpUnitMember\" title=\"Delete OpUnit Member\"style=\"
									margin: 0px;
								\">
									<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
								</button>
							</div>
						";
					}

					//Button for Member to SignUp for OpUnit
					$display_opUnit_member_signUp = "";
					if ($CurrentUserAssigned != "true" && $operationDetails_MissionStatusDescrption == "Approved")
					{
						$display_opUnit_member_signUp .= "
							<div id=\"OpUnitMemberButtons_$opUnitMemberListItem_RowID\" style=\"
								text-align: left;
								margin-left: 8px;
								display: table-cell;
							\">
								<button id=\"ButtonOpUnitMember_SignUp_$opUnitMemberListItem_RowID\" class=\"adminButton adminButtonEdit ButtonSignUpOpUnitMember\" title=\"Assign Self to Role\"style=\"
									margin: 0px;
								\">
									<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
								</button>							
							</div>
						";
					}				
					
					$display_opUnit_member_list .= "
						<div class=\"operations_memRank\"
							data-rowid=$opUnitMemberListItem_RowID
							data-optemplateid=$MissionID
							data-optemplateunitid=$opUnitMemberListItem_OpUnitID
							data-opunitmemberroleid=$opUnitMemberListItem_OpUnitMemberRoleID
							data-memberid=$opUnitMemberListItem_MemberID
						>
					";
					
					//Display Member with Rank
					if ($opUnitMemberListItem_MemberID != null)
					{
						$display_opUnit_member_list .= "
							$display_opUnitMember_edit
							<div class=\"operations_rank_image_text\" style=\"
								vertical-align: inherit;
								font-weight: bold;
								padding-right:4px;
							\">
								$opUnitMemberListItem_RoleName
							</div>
							<div class=\"operations_memRank_inner\" style=\"
								margin-left: 4px;
								display: block;
							\">
								<div class=\"clickableRow_memRank_Image_Container shiftable\">
									<img class=\"clickableRow_memRank_Image\" src=\"$link_base/images/ranks/TS3/$opUnitMemberListItem_RankImage.png\"/>
								
								</div>
								<div class=\"operations_rank_image_text\">
									<a href=\"$link_base/player/$opUnitMemberListItem_MemberID\" style=\"
									text-decoration: none;
									font-weight: normal;
								\">
									$opUnitMemberListItem_MemName
									</a>
								</div>";
						
							//Display Button for a Member to Remove Themself
							if ($opUnitMemberListItem_MemberID == $CurrentUserID && $operationDetails_MissionStatusDescrption == "Approved")
							{
								$display_opUnit_member_list .= "
									<div id=\"OpUnitMemberButtons_$opUnitMemberListItem_RowID\" style=\"
										text-align: left;
										margin-left: 8px;
										display: table-cell;
									\">
										<button id=\"ButtonOpUnitMember_Clear_$opUnitMemberListItem_RowID\" class=\"adminButton adminButtonDelete ButtonClearOpUnitMember\" title=\"Remove Self from Role\"style=\"
											margin: 0px;
										\">
											<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
										</button>							
									</div>	
								";
							}
					}
					//Display Role Only
					else
					{
						$display_opUnit_member_list .= "
							$display_opUnitMember_edit
							<div class=\"operations_rank_image_text\" style=\"
								vertical-align: inherit;
								font-weight: bold;
							\">
								$opUnitMemberListItem_RoleName
							</div>
							<div class=\"operations_memRank_inner\" style=\"
								margin-left: 4px;
								display: block;
							\">
								<div class=\"operations_rank_image_text shiftable\" style=\"
									font-style: italic;
									color: #888;
								\">
									-- None Assigned --
								</div>							
							$display_opUnit_member_signUp
						";						
					}
								
					$display_opUnit_member_list .= "
							</div>
						</div>
					";
				}
				
				//Close list of Members
				$display_opUnit_member_list .= "
						</div>
					</div>
				";
				
				//Add MemberList to UnitList
				$display_opUnits_list .= "
					$display_opUnit_member_list
				";				
			}
			if ($opUnitsListItem_Support_AirFlight == "1")
			{
				$display_opUnitShips_list_edit = "";
				/*
				if ($canEdit)
				{
					$display_opUnitShips_list_edit = "
						<div id=\"OpUnitShipsListButtons_$opUnitsListItem_OpUnitID\" style=\"
							float: right;
							text-align: right;
							margin-right: 8px;
						\"
							data-optemplateid=$MissionID
							data-optemplateunitid=$opUnitsListItem_OpUnitID
						>
							<button id=\"ButtonAddOpUnitShip_$opUnitsListItem_OpUnitID\" class=\"adminButton adminButtonCreate ButtonAddOpUnitShip\" title=\"Add Ship\"style=\"
								margin-left: 0px;
								margin-right: 2%;
							\">
								<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
								Add Ship
							</button>
						</div>
					";
				}
				*/
			
				//GET SHIPS FOR THIS OpUnit
				$display_opUnit_ships_list = "
					$display_opUnitShips_list_edit
					<div class=\"OpUnitShipsList\">
						<h6 class=\"operations_h6\">
							Equipment
						</h6>
						<table class=\"OpUnit_Ships\">
				";
				
				$opUnitShips_query = "
					select
						os.MissionShipID
						,os.MissionUnitID
						,os.ShipID
						,s.ship_silo
						,s.ship_name
						,s.ship_model_designation
						,s.ship_model_visible
						,m.manu_shortName
						,os.Callsign
						,sed.MaxCrew as ship_max_crew
						,(select COUNT(1) from projectx_vvarsc2.MissionShipMembers osm where osm.MissionShipID = os.MissionShipID) as MemberCount
					from projectx_vvarsc2.MissionShips os
					join projectx_vvarsc2.ships s
						on s.ship_id = os.ShipID
					join projectx_vvarsc2.manufacturers m
						on m.manu_id = s.manufacturers_manu_id
					join projectx_vvarsc2.ShipStats_v2 sed
						on sed.ShipID = s.ship_id
					where os.MissionUnitID = $opUnitsListItem_OpUnitID
					order by
						os.MissionShipOrderBy
				";
				
				$full_ship_name = "";
				$shipIndex = 1;
				$opUnitShips_query_result = $connection->query($opUnitShips_query);
				
				while(($row4 = $opUnitShips_query_result->fetch_assoc()) != false) {
					$opUnitShipsListItem_OpShipID = $row4['MissionShipID'];
					$opUnitShipsListItem_ShipID = $row4['ShipID'];
					$opUnitShipsListItem_ShipSilo = $row4['ship_silo'];
					$opUnitShipsListItem_ShipName = $row4['ship_name'];
					$opUnitShipsListItem_ShipModel = $row4['ship_model_designation'];
					$opUnitShipsListItem_ShipModelVisible = $row4['ship_model_visible'];
					$opUnitShipsListItem_ManuShortName = $row4['manu_shortName'];
					$opUnitShipsListItem_ShipCallsign = $row4['Callsign'];
					$opUnitShipsListItem_ShipMaxCrew = $row4['ship_max_crew'];
					$opUnitShipsListItem_MemberCount = $row4['MemberCount'];
					
					if ($opUnitShipsListItem_ShipModel != NULL && $opUnitShipsListItem_ShipModelVisible != "0") {
						$full_ship_name = "";
						$full_ship_name .= $opUnitShipsListItem_ShipModel;
						$full_ship_name .= " \n";
						$full_ship_name .= $opUnitShipsListItem_ShipName;
					}
					else
					{
						$full_ship_name = $opUnitShipsListItem_ShipName;
					}

					if ($opUnitsListItem_OpUnitTypeDescription != 'Capital Ship' && $opUnitsListItem_OpUnitTypeDescription != 'Marine Squad' && ($opUnitShipsListItem_ShipCallsign == null || $opUnitShipsListItem_ShipCallsign == ''))
					{
						$opUnitShipsListItem_ShipCallsign = $callSign.'-'.$shipIndex;
					}
					
					$display_opUnitShip_edit = "";
					/*
					if ($canEdit)
					{
						$display_opUnitShip_edit = "
							<div id=\"OpUnitShipButtons_$opUnitShipsListItem_OpShipID\" style=\"
								text-align: left;
								margin-left: 8px;
								display: table-cell;
							\"
								data-optemplateid=$MissionID
								data-optemplateunitid=$opUnitsListItem_OpUnitID
								data-optemplateshipid=$opUnitShipsListItem_OpShipID
								data-shipid=$opUnitShipsListItem_ShipID
							>
								<button id=\"ButtonDeleteOpUnitShip_$opUnitShipsListItem_OpShipID\" class=\"adminButton adminButtonDelete ButtonDeleteOpUnitShip\" title=\"Delete OpUnit Ship\"style=\"
									margin-left: 0px;
									margin-right: 0px;
								\">
									<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
								</button>
							</div>
						";
					}
					*/
					
					$display_opUnit_ships_list .= "
						<tr class=\"player_ships_row\">
							<td class=\"player_ships_entry\">
								<div class=\"player_ships_shipTitle\">
									$display_opUnitShip_edit
									<div class=\"player_ships_shipTitleContainer\">
										<a href=\"$link_base/ship/$opUnitShipsListItem_ShipID\" >
											<div class=\"player_ships_shipTitleText\">
												$opUnitShipsListItem_ManuShortName $full_ship_name
											</div>
										</a>
									</div>
								</div>								
								<div class=\"player_ships_entry_details\">
									<div class=\"shipTable2_Container\">
										<div class=\"corner2 corner-top-left\">
										</div>
										<div class=\"corner2 corner-top-right\">
										</div>
										<div class=\"corner2 corner-bottom-left\">
										</div>
										<div class=\"corner2 corner-bottom-right\">
										</div>
								";
								if ($opUnitsListItem_OpUnitTypeDescription != 'Capital Ship' && $opUnitsListItem_OpUnitTypeDescription != 'Marine Squad' )
								{
									$display_opUnit_ships_list .= "
										<table class=\"tooltip_shipTable2\" style=\"
											width: auto;
											padding-left: 4px;
										\">
											<tr>
												<td class=\"tooltip_shipTable2_key\" style=\"
													width: auto;
												\">
													<div class=\"tooltip_shipTable2_key_inner\">
													Callsign
													</div>
												</td>
												<td class=\"tooltip_shipTable2_value\">
													<div class=\"tooltip_shipTable2_value_inner\">
													$opUnitShipsListItem_ShipCallsign
													</div>
												</td>
											</tr>
										</table>
									";								
								}

					
					$display_opUnitShipMembers_list_edit = "";
					if ($canEdit
						&& ($opUnitShipsListItem_MemberCount < $opUnitShipsListItem_ShipMaxCrew)
						)
					{
						$display_opUnitShipMembers_list_edit = "
							<div id=\"OpUnitShipMembersListButtons_$opUnitShipsListItem_OpShipID\" style=\"
								float: right;
								text-align: right;
								margin-right: 8px;
							\"
								data-optemplateid=$MissionID
								data-optemplateunitid=$opUnitsListItem_OpUnitID
								data-optemplateshipid=$opUnitShipsListItem_OpShipID
							>
								<button id=\"ButtonAddOpUnitShipMember_$opUnitShipsListItem_OpShipID\" class=\"adminButton adminButtonCreate ButtonAddOpUnitShipMember\" title=\"Add Member\"style=\"
									margin-left: 0px;
									margin-right: 2%;
								\">
									<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
									Add Member
								</button>
							</div>
						";
					}
					
					//GET Members for this Ship
					$display_opUnitShipMembers = "
						$display_opUnitShipMembers_list_edit
						<div class=\"OpUnitMemberList\" style=\"
							padding-top: 4px;
						\">
							<h6 class=\"operations_h6\">
								Personnel
							</h6>
							<div class=\"OpUnit_Members\">						
					";
					
					$opUnitShipMembers_query = "
						select
							osm.RowID
							,osm.MissionShipID
							,osm.MemberID
							,osm.OpUnitMemberRoleID
							,osm.RoleName
							,osm.RoleOrderBy
							,CONCAT(osm.rank_abbr, ' ', osm.mem_name) as mem_name
							,osm.rank_tinyImage
							,osm.rank_orderBy
						from 
						(
							select
								osm.RowID
								,osm.MissionShipID
								,osm.MemberID
								,osm.OpUnitMemberRoleID
								,mr.RoleName
								,mr.RoleOrderBy
								,m.mem_callsign as mem_name
								,r.rank_abbr
								,r.rank_tinyImage
								,r.rank_orderBy
								,0 as member_orderBy
							from projectx_vvarsc2.MissionShipMembers osm
							join projectx_vvarsc2.members m
								on m.mem_id = osm.MemberID
							join projectx_vvarsc2.ranks r
								on r.rank_id = m.ranks_rank_id
							join projectx_vvarsc2.OpUnitTypeMemberRoles mr
								on mr.OpUnitMemberRoleID = osm.OpUnitMemberRoleID
							where osm.MissionShipID = $opUnitShipsListItem_OpShipID

							union
							select
								osm.RowID
								,osm.MissionShipID
								,osm.MemberID
								,osm.OpUnitMemberRoleID
								,mr.RoleName
								,mr.RoleOrderBy
								,null as mem_name
								,null as rank_abbr
								,null as rank_tinyImage
								,null as rank_orderBy
								,1 as member_orderBy
							from projectx_vvarsc2.MissionShipMembers osm
							join projectx_vvarsc2.OpUnitTypeMemberRoles mr
								on mr.OpUnitMemberRoleID = osm.OpUnitMemberRoleID
							where osm.MissionShipID = $opUnitShipsListItem_OpShipID
								and osm.MemberID is null
						) osm
						order by
							osm.RoleOrderBy
							,osm.RoleName
							,osm.member_orderBy
							,osm.rank_orderBy
							,osm.mem_name
					";
					
					$opUnitShipMembers_query_result = $connection->query($opUnitShipMembers_query);
				
					while(($row6 = $opUnitShipMembers_query_result->fetch_assoc()) != false) {
						$opShipMembersListItem_RowID = $row6['RowID'];
						$opShipMembersListItem_OpShipID = $row6['MissionShipID'];
						$opShipMembersListItem_MemberID = $row6['MemberID'];
						$opShipMembersListItem_OpUnitMemberRoleID = $row6['OpUnitMemberRoleID'];
						$opShipMembersListItem_RoleName = $row6['RoleName'];
						$opShipMembersListItem_RoweOrderBy = $row6['RoleOrderBy'];
						$opShipMembersListItem_MemName = $row6['mem_name'];
						$opShipMembersListItem_RankImage = $row6['rank_tinyImage'];
						
						$display_opShipMember_edit = "";
						if ($canEdit)
						{
							$display_opShipMember_edit = "
								<div id=\"OpShipMemberButtons_$opShipMembersListItem_RowID\" style=\"
									text-align: left;
									margin-left: 8px;
									display: table-cell;
								\">
									<button id=\"ButtonEditOpShipMember_$opShipMembersListItem_RowID\" class=\"adminButton adminButtonEdit ButtonEditOpShipMember\" title=\"Edit OpShip Member\"style=\"
										margin: 0px;
									\">
										<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_edit.png\">
									</button>
									<button id=\"ButtonDeleteOpShipMember_$opShipMembersListItem_RowID\" class=\"adminButton adminButtonDelete ButtonDeleteOpShipMember\" title=\"Delete OpShip Role\"style=\"
										margin: 0px;
									\">
										<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
									</button>
								</div>
							";
						}

						//Button for Member to SignUp for OpUnitShip
						$display_opShip_member_signUp = "";
						if ($CurrentUserAssigned != "true" && $operationDetails_MissionStatusDescrption == "Approved")
						{
							$display_opShip_member_signUp .= "
								<div id=\"OpUnitMemberButtons_$opShipMembersListItem_RowID\" style=\"
									text-align: left;
									margin-left: 8px;
									display: table-cell;
								\">
									<button id=\"ButtonOpUnitMember_SignUp_$opShipMembersListItem_RowID\" class=\"adminButton adminButtonEdit ButtonSignUpOpShipMember\" title=\"Assign Self to Role\"style=\"
										margin: 0px;
									\">
										<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_add.png\">
									</button>							
								</div>
							";
						}									
						
						$display_opUnitShipMembers .= "
							<div class=\"operations_memRank\"
								data-rowid=$opShipMembersListItem_RowID
								data-optemplateid=$MissionID
								data-optemplateunitid=$opUnitsListItem_OpUnitID
								data-optemplateshipid=$opShipMembersListItem_OpShipID
								data-opunitmemberroleid=$opShipMembersListItem_OpUnitMemberRoleID
								data-memberid=$opShipMembersListItem_MemberID
							>	
						";
						
						if ($opShipMembersListItem_MemberID != null)
						{
							//Display Members with Rank
							$display_opUnitShipMembers .= "
								$display_opShipMember_edit
								<div class=\"operations_rank_image_text\" style=\"
									vertical-align: inherit;
									font-weight: bold;
									padding-right: 4px;
								\">
									$opShipMembersListItem_RoleName
								</div>
								<div class=\"operations_memRank_inner\" style=\"
									margin-left: 4px;
									display: block;
								\">
									<div class=\"clickableRow_memRank_Image_Container shiftable\">
										<img class=\"clickableRow_memRank_Image\" src=\"$link_base/images/ranks/TS3/$opShipMembersListItem_RankImage.png\"/>
									</div>
									<div class=\"operations_rank_image_text\">
										<a href=\"$link_base/player/$opShipMembersListItem_MemberID\" style=\"
										text-decoration: none;
										font-weight: normal;
									\">
										$opShipMembersListItem_MemName
										</a>
									</div>";
									
							//Display Button for a Member to Remove Themself
							if ($opShipMembersListItem_MemberID == $CurrentUserID && $operationDetails_MissionStatusDescrption == "Approved")
							{
								$display_opUnitShipMembers .= "
									<div id=\"OpUnitMemberButtons_$opShipMembersListItem_RowID\" style=\"
										text-align: left;
										margin-left: 8px;
										display: table-cell;
									\">
										<button id=\"ButtonOpUnitMember_Clear_$opShipMembersListItem_RowID\" class=\"adminButton adminButtonDelete ButtonClearOpShipMember\" title=\"Remove Self from Role\"style=\"
											margin: 0px;
										\">
											<img height=\"20px\" class=\"adminButtonImage\" src=\"$link_base/images/misc/button_delete.png\">
										</button>							
									</div>	
								";
							}									
									
							$display_opUnitShipMembers .= "
								</div>
							";
						}
						//Display Role Only
						else
						{
							$display_opUnitShipMembers .= "
								$display_opShipMember_edit
								<div class=\"operations_rank_image_text\" style=\"
									vertical-align: inherit;
									font-weight: bold;
								\">
									$opShipMembersListItem_RoleName
								</div>
								<div class=\"operations_memRank_inner\" style=\"
									margin-left: 4px;
									display: block;
								\">
									<div class=\"operations_rank_image_text shiftable\" style=\"
										font-style: italic;
										color: #888;
									\">
										-- None Assigned --
									</div>
								$display_opShip_member_signUp
							";
						}
									
						$display_opUnitShipMembers .= "
							</div>
						";						
					}
					
					//Close list of Members
					$display_opUnitShipMembers .= "
							</div>
						</div>
					";
				
					//Add MemberList to Ship
					$display_opUnit_ships_list .= "
						$display_opUnitShipMembers
					";
					
					$display_opUnit_ships_list .= "
									</div>								
								</div>
							</td>
							<td class=\"player_ships_entry_ship\" style=\"\">
								<div class=\"player_ships_entry_ship_inner\">
									<div class=\"player_ships_entry_ship_inner_imageContainer\">
										<a href=\"$link_base/ship/$opUnitShipsListItem_ShipID\" >
											<img class=\"player_fleet\" align=\"center\" src=\"$link_base/images/silo_topDown/$opUnitShipsListItem_ShipSilo\" />
										</a>
									</div>
								</div>
								<div class=\"playerShips_table_header_block2\">
								</div>
							</td>
						</tr>
					";
					$shipIndex++;
				}
				
				//Close list of Ships
				$display_opUnit_ships_list .= "
						</table>
					</div>
				";
				
				//Add ShipList to UnitList
				$display_opUnits_list .= "
					$display_opUnit_ships_list
				";
			}
			
			//Close Unit Entry
			$display_opUnits_list .= "
					</div>
				</div>
			";
			
			$CurrentUnitID = $opUnitsListItem_UnitID;
		}
		
		//Close List of Units
		$display_opUnits_list .= "
				</div>
			</div>
		";
		
		//Button and Hidden Containers for Navigation
		$display_operationsListContainer = "";
		$display_operationsListButton = "";
		$display_missionsListButton = "";
		
		if ($canEdit)
		{
			$display_operationsListContainer = "
				<div id=\"operations_list_menu\">
					<div class=\"div_filters_container\" id=\"filtersContainer_OpMenu_Hide\" style=\"
						background: rgba(0, 0, 0, 0.65) none repeat scroll 0% 0%;
						margin-left: 0px;
						text-align: right;
						float: right;
					\">
						<div class=\"div_filters_entry\">
							<div id=\"operations_menu_toggle_off\">
								Close Operations List
							</div>
						</div>
					</div>
					<h4 class=\"operations_h4\" style=\"
						padding-left:4px;
					\">
						Operation Templates
					</h4>		
					<div class=\"operations_menu_inner_items_container\">
						$display_operationsList
					</div>
				</div>
			";
			
			$display_missionsListContainer = "
				<div id=\"missions_list_menu\">
					<div class=\"div_filters_container\" id=\"filtersContainer_MissionMenu_Hide\" style=\"
						background: rgba(0, 0, 0, 0.65) none repeat scroll 0% 0%;
						margin-left: 0px;
						text-align: right;
						float: right;
					\">
						<div class=\"div_filters_entry\">
							<div id=\"missions_menu_toggle_off\">
								Close Missions List
							</div>
						</div>
					</div>
					<h4 class=\"operations_h4\" style=\"
						padding-left:4px;
					\">
						Upcoming Missions
					</h4>	
					<div class=\"operations_menu_inner_items_container\">
						$display_missionsList
					</div>
				</div>
			";
			
			$display_operationsListButton = "	
				<div class=\"div_filters_entry\">
					<div id=\"operations_menu_toggle_on\">
						Operation Templates
					</div>
				</div>
			";
			
			
			$display_missionsListButton = "
				<div class=\"div_filters_entry\" style=\"margin-left: 8px\">
					<div id=\"missions_menu_toggle_on\">
						Missions
					</div>
				</div>
			";
		}
		
	}
	else
	{
		$display_operationDetails = "Please Select an Operation from the list.";
	}

	$connection->close();
?>
<h2 id="MainPageHeaderText">Mission Plan Details</h2>
<div id="TEXT">
	<div id="operations_topMenu_container">
		<div class="div_filters_container" id="filtersContainer_OpMenu_Show" style="
			display:inline-table;
			width: auto;
		">
			<div style="
				display: inline-block;
				cursor: pointer;
				font-style: italic;
				font-variant: all-petite-caps;
				margin-right: 8px;
				margin-left: 8px;
			">
				<a href="<? $link_base; ?>/missions">&#8672; Back to List</a>
			</div>
			<? echo $display_operationsListButton; ?>
			<? echo $display_missionsListButton; ?>
		</div>
		<? echo $displayMainActionButtons; ?>
	</div>
	<div class="tbinfo_container">
		<div id="operations_menu_container">
			<div class="partialBorder-left-blue border-left border-top border-4px">
			</div>
			<? echo $display_operationsListContainer; ?>
		</div>
		<div id="missions_menu_container">
			<div class="partialBorder-left-blue border-left border-top border-4px">
			</div>
			<? echo $display_missionsListContainer; ?>
		</div>
		<div class="operation_main_container">
			<div class="table_header_block2_long">
			</div>
			<div class="operationsDetails_main" data-operationid="<? echo $MissionID;?>">
				<? echo $display_operationDetails; ?>
				<br />
				<? echo $display_opUnits_list_edit; ?>
				<? echo $display_opUnits_list; ?>
			</div>
		</div>
	</div>
	
	<!--Forms-->
	
	<!--Edit Operation Form-->
	<div id="dialog-form-edit-operation" class="adminDialogFormContainer" style="max-width:80%;min-width:50%">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update Operation Template Main Info</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/missions/function_mission_editOpTemplate.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="MissionID" class="adminDialogInputLabel" style="display: none">
					Mission ID
				</label>
				<input type="none" name="MissionID" id="MissionID" value="" class="adminDialogTextInput" readonly style="display: none">
				
				<label for="OperationName" class="adminDialogInputLabel">
					Mission Name
				</label>
				<input type="text" name="OperationName" id="OperationName" value="" class="adminDialogTextInput" required autofocus>
				
				<label for="OperationType" class="adminDialogInputLabel">
					Mission Type
				</label>
				<input type="text" name="OperationType" id="OperationType" value="" class="adminDialogTextInput" required>
				
				<label for="StartDate" class="adminDialogInputLabel">
					Mission Start Date (UTC, Format: YYYY-MM-DD HH:MM:SS)
				</label>
				<input type="text" name="StartDate" id="StartDate" value="" class="adminDialogTextInput" required>
				
				<label for="EndDate" class="adminDialogInputLabel">
					Mission End Date (UTC, Format: YYYY-MM-DD HH:MM:SS)
				</label>
				<input type="text" name="EndDate" id="EndDate" value="" class="adminDialogTextInput" required>
				
				<label for="StartingLocation" class="adminDialogInputLabel">
					Starting Location
				</label>
				<input type="text" name="StartingLocation" id="StartingLocation" value="" class="adminDialogTextInput" required>
				
				<label for="MissionSummary" class="adminDialogInputLabel">
					Mission Summary
				</label>
				<textarea name="MissionSummary" id="MissionSummary" class="adminDialogTextArea" required><? echo $operationDetails_Mission ?></textarea>
				
				<label for="ObjectiveDetails" class="adminDialogInputLabel">
					Objective Details
				</label>
				<textarea name="ObjectiveDetails" id="ObjectiveDetails" class="adminDialogTextArea" required><? echo $operationDetails_Description ?></textarea>
				
				<!--MissionStatus-->
				<label for="MissionStatusID" class="adminDialogInputLabel">
					Mission Status
				</label>
				<select name="MissionStatusID" id="MissionStatusID" class="adminDialogDropDown">
					<option selected disabled value="default" id="MissionStatusID-default">
						- Select a Mission Status -
					</option>	
					<? echo $displayMissionStatusesSelectors ?>
				</select>
				
			</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>	
		</form>
	</div>
	
	<!--Delete Operation Form-->
	<div id="dialog-form-delete-operation" class="adminDialogFormContainer" style="max-width:80%;min-width:50%">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">WARNING - You are about to delete this Mission Plan. This is a non-reversible operation.</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/missions/function_mission_deleteOpTemplate.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="MissionID" class="adminDialogInputLabel" style="display: none">
					Mission ID
				</label>
				<input type="none" name="MissionID" id="MissionID" value="" class="adminDialogTextInput" readonly style="display: none">
				
				<label for="OperationName" class="adminDialogInputLabel">
					Mission Name
				</label>
				<input type="text" name="OperationName" id="OperationName" value="" class="adminDialogTextInput" required autofocus readonly>
				
				<label for="OperationType" class="adminDialogInputLabel">
					Mission Type
				</label>
				<input type="text" name="OperationType" id="OperationType" value="" class="adminDialogTextInput" required readonly>
				
				<label for="StartingLocation" class="adminDialogInputLabel">
					Starting Location
				</label>
				<input type="text" name="StartingLocation" id="StartingLocation" value="" class="adminDialogTextInput" readonly>
			</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>	
		</form>
	</div>	
	
	<!--Edit OpUnit Form-->
	<div id="dialog-form-edit-opUnit" class="adminDialogFormContainer" style="max-width:80%;min-width:50%">	
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update Operational Unit</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/missions/function_mission_editOpTemplateUnit.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="OpTemplateID" class="adminDialogInputLabel" style="display:none">
					Operation Template ID
				</label>
				<input type="none" name="OpTemplateID" id="OpTemplateID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateUnitID" class="adminDialogInputLabel" style="display:none">
					Operation Unit ID
				</label>
				<input type="none" name="OpTemplateUnitID" id="OpTemplateUnitID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateUnitType" class="adminDialogInputLabel">
					Operational Unit Type
				</label>
				<select name="OpTemplateUnitType" id="OpTemplateUnitType" class="adminDialogDropDown" disabled>
					<option selected disabled value="default" id="OpUnitTypeID-default">
						- Select a Type -
					</option>	
					<? echo $displayOpUnitTypesSelectors ?>
				</select>
				
				<label for="UnitID" class="adminDialogInputLabel">
					Source Unit
				</label>
				<select name="UnitID" id="UnitID" class="adminDialogDropDown">
					<option selected disabled value="default" id="UnitID-default">
						- Select a Unit -
					</option>	
					<? echo $displayOrgUnitsSelectors ?>
				</select>
				
				<label for="OpUnitCallsign" id="OpUnitCallsignLabel" class="adminDialogInputLabel">
					Callsign Override
				</label>				
				<input type="text" name="OpUnitCallsign" id="OpUnitCallsign" value="" class="adminDialogTextInput">
				
				<label for="OpUnitObjectives" id="OpUnitObjectivesLabel" class="adminDialogInputLabel">
					Unit Objectives
				</label>
				<textarea name="OpUnitObjectives" id="OpUnitObjectives" class="adminDialogTextArea"></textarea>
				
			</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>	
		</form>
	</div>
	
	<!--Create OpUnitMember Form-->
	<div id="dialog-form-create-opUnitMember" class="adminDialogFormContainer" style="max-width:80%;min-width:25%">	
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Add Unit Member</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/missions/function_mission_createOpTemplateUnitMember.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="OpTemplateID" class="adminDialogInputLabel" style="display:none">
					Operation Template ID
				</label>
				<input type="none" name="OpTemplateID" id="OpTemplateID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateUnitID" class="adminDialogInputLabel" style="display:none">
					Operation Unit ID
				</label>
				<input type="none" name="OpTemplateUnitID" id="OpTemplateUnitID" value="" class="adminDialogTextInput" readonly  style="display:none">
				
				<label for="OpUnitMemberRoleID" class="adminDialogInputLabel">
					Role
				</label>
				<select name="OpUnitMemberRoleID" id="OpUnitMemberRoleID" class="adminDialogDropDown" required>
					<option selected disabled value="default" id="OpUnitMemberRoleID-default">
						- Select a Role -
					</option>	
					<? echo $displayOpUnitTypeMemberRolesSelectors; ?>
				</select>
				
				<label for="MemberID" class="adminDialogInputLabel">
					Assigned Member
				</label>
				<select name="MemberID" id="MemberID" class="adminDialogDropDown">
					<option selected disabled value="default" id="MemberID-default">
						- Select a Member -
					</option>
					<option value="0" id="MemberID-default">
						-- Unassigned --
					</option>	
					<? echo $displayGetMembersSelectors; ?>
				</select>
				
			</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>	
		</form>
	</div>	
	
	<!--Edit OpUnitMember Form-->
	<div id="dialog-form-edit-opUnitMember" class="adminDialogFormContainer" style="max-width:80%;min-width:25%">	
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update Unit Member</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/missions/function_mission_editOpTemplateUnitMember.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel" style="display:none">
					RowID
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateID" class="adminDialogInputLabel" style="display:none">
					Operation Template ID
				</label>
				<input type="none" name="OpTemplateID" id="OpTemplateID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateUnitID" class="adminDialogInputLabel" style="display:none">
					Operation Unit ID
				</label>
				<input type="none" name="OpTemplateUnitID" id="OpTemplateUnitID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpUnitMemberRoleID" class="adminDialogInputLabel">
					Role
				</label>
				<select name="OpUnitMemberRoleID" id="OpUnitMemberRoleID" class="adminDialogDropDown" required>
					<option selected disabled value="" id="OpUnitMemberRoleID-default">
						- Select a Role -
					</option>	
					<? echo $displayOpUnitTypeMemberRolesSelectors; ?>
				</select>
				
				<label for="MemberID" class="adminDialogInputLabel">
					Assigned Member
				</label>
				<select name="MemberID" id="MemberID" class="adminDialogDropDown">
					<option selected disabled value="default" id="MemberID-default">
						- Select a Member -
					</option>
					<option value="0" id="MemberID-default">
						-- Unassigned --
					</option>	
					<? echo $displayGetMembersSelectors; ?>
				</select>
				
			</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>	
		</form>
	</div>
	
	<!--Delete OpUnitMember Form-->
	<div id="dialog-form-delete-opUnitMember" class="adminDialogFormContainer" style="max-width:80%;min-width:25%">	
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Delete Unit Member</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/missions/function_mission_deleteOpTemplateUnitMember.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel" style="display:none">
					RowID
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateID" class="adminDialogInputLabel" style="display:none">
					Operation Template ID
				</label>
				<input type="none" name="OpTemplateID" id="OpTemplateID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateUnitID" class="adminDialogInputLabel" style="display:none">
					Operation Unit ID
				</label>
				<input type="none" name="OpTemplateUnitID" id="OpTemplateUnitID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpUnitMemberRoleID" class="adminDialogInputLabel">
					Role
				</label>
				<select name="OpUnitMemberRoleID" id="OpUnitMemberRoleID" class="adminDialogDropDown" disabled>
					<option selected disabled value="default" id="OpUnitMemberRoleID-default">
						- Select a Role -
					</option>	
					<? echo $displayOpUnitTypeMemberRolesSelectors; ?>
				</select>
				
				<label for="MemberID" class="adminDialogInputLabel">
					Assigned Member
				</label>
				<select name="MemberID" id="MemberID" class="adminDialogDropDown" disabled>
					<option selected disabled value="default" id="MemberID-default">
						- Select a Member -
					</option>
					<option value="0" id="MemberID-default">
						-- Unassigned --
					</option>	
					<? echo $displayGetMembersSelectors; ?>
				</select>
				
			</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>	
		</form>
	</div>

	<!--Create OpShipMember Form-->
	<div id="dialog-form-create-opUnitShipMember" class="adminDialogFormContainer" style="max-width:80%;min-width:25%">	
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Add Ship Member</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/missions/function_mission_createOpTemplateUnitShipMember.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="OpTemplateID" class="adminDialogInputLabel" style="display:none">
					Operation Template ID
				</label>
				<input type="none" name="OpTemplateID" id="OpTemplateID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateUnitID" class="adminDialogInputLabel" style="display:none">
					Operation Unit ID
				</label>
				<input type="none" name="OpTemplateUnitID" id="OpTemplateUnitID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateShipID" class="adminDialogInputLabel" style="display:none">
					Operation Ship ID
				</label>
				<input type="none" name="OpTemplateShipID" id="OpTemplateShipID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpUnitMemberRoleID" class="adminDialogInputLabel">
					Role
				</label>
				<select name="OpUnitMemberRoleID" id="OpUnitMemberRoleID" class="adminDialogDropDown" required>
					<option selected disabled value="default" id="OpUnitMemberRoleID-default">
						- Select a Role -
					</option>	
					<? echo $displayOpUnitTypeMemberRolesSelectors; ?>
				</select>
				
				<label for="MemberID" class="adminDialogInputLabel">
					Assigned Member
				</label>
				<select name="MemberID" id="MemberID" class="adminDialogDropDown">
					<option selected disabled value="" id="MemberID-default">
						- Select a Member -
					</option>
					<option value="0" id="MemberID-0">
						-- Unassigned --
					</option>		
					<? echo $displayGetMembersSelectors; ?>
				</select>
				
			</fieldset>
				<div class="adminDialogButtonPane">
					<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
						Submit
					</button>
				</div>	
		</form>
	</div>
	
	<!--Edit OpUnitShipMember Form-->
	<div id="dialog-form-edit-opUnitShipMember" class="adminDialogFormContainer" style="max-width:80%;min-width:25%">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Update Ship Member</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/missions/function_mission_editOpTemplateUnitShipMember.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel" style="display:none">
					RowID
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateID" class="adminDialogInputLabel" style="display:none">
					Operation Template ID
				</label>
				<input type="none" name="OpTemplateID" id="OpTemplateID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateUnitID" class="adminDialogInputLabel" style="display:none">
					Operation Unit ID
				</label>
				<input type="none" name="OpTemplateUnitID" id="OpTemplateUnitID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateShipID" class="adminDialogInputLabel" style="display:none">
					Operation Ship ID
				</label>
				<input type="none" name="OpTemplateShipID" id="OpTemplateShipID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpUnitMemberRoleID" class="adminDialogInputLabel">
					Role
				</label>
				<select name="OpUnitMemberRoleID" id="OpUnitMemberRoleID" class="adminDialogDropDown" required>
					<option selected disabled value="default" id="OpUnitMemberRoleID-default">
						- Select a Role -
					</option>	
					<? echo $displayOpUnitTypeMemberRolesSelectors; ?>
				</select>
				
				<label for="MemberID" class="adminDialogInputLabel">
					Assigned Member
				</label>
				<select name="MemberID" id="MemberID" class="adminDialogDropDown">
					<option selected disabled value="" id="MemberID-default">
						- Select a Member -
					</option>
					<option value="0" id="MemberID-0">
						-- Unassigned --
					</option>		
					<? echo $displayGetMembersSelectors; ?>
				</select>
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>
		</form>
		
	</div>
		
	<!--Delete OpUnitShipMember Form-->
	<div id="dialog-form-delete-opUnitShipMember" class="adminDialogFormContainer" style="width:80%">
		<button id="adminDialogCancel" class="adminDialogButton dialogButtonCancel" type="cancel">
			Cancel
		</button>
		<p class="validateTips">Delete Ship Member</p>
		<form class="adminDialogForm" action="<? $link_base; ?>/functions/missions/function_mission_deleteOpTemplateUnitShipMember.php" method="POST" role="form">
			<fieldset class="adminDiaglogFormFieldset">
				<label for="RowID" class="adminDialogInputLabel" style="display:none">
					RowID
				</label>
				<input type="none" name="RowID" id="RowID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateID" class="adminDialogInputLabel" style="display:none">
					Operation Template ID
				</label>
				<input type="none" name="OpTemplateID" id="OpTemplateID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateUnitID" class="adminDialogInputLabel" style="display:none">
					Operation Unit ID
				</label>
				<input type="none" name="OpTemplateUnitID" id="OpTemplateUnitID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpTemplateShipID" class="adminDialogInputLabel" style="display:none">
					Operation Ship ID
				</label>
				<input type="none" name="OpTemplateShipID" id="OpTemplateShipID" value="" class="adminDialogTextInput" readonly style="display:none">
				
				<label for="OpUnitMemberRoleID" class="adminDialogInputLabel">
					Role
				</label>
				<select name="OpUnitMemberRoleID" id="OpUnitMemberRoleID" class="adminDialogDropDown" disabled>
					<option selected disabled value="default" id="OpUnitMemberRoleID-default">
						- Select a Role -
					</option>	
					<? echo $displayOpUnitTypeMemberRolesSelectors; ?>
				</select>
				
				<label for="MemberID" class="adminDialogInputLabel">
					Assigned Member
				</label>
				<select name="MemberID" id="MemberID" class="adminDialogDropDown" disabled>
					<option selected disabled value="" id="MemberID-default">
						- Select a Member -
					</option>
					<option value="0" id="MemberID-0">
						-- Unassigned --
					</option>		
					<? echo $displayGetMembersSelectors; ?>
				</select>
				
			</fieldset>
			<div class="adminDialogButtonPane">
				<button id="adminDialogSubmit" class="adminDialogButton dialogButtonSubmit" type="submit">
					Submit
				</button>
			</div>	
		</form>
	</div>	
	
</div>
  
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
</script>
<script type="text/javascript" src="/js/jquery.jScale.js">
</script>
<script type="text/javascript" src="<? $link_base; ?>/js/jquery.jscroll.min.js">
</script>

<!--FORM CONTROLS-->
<script>
	function resizeInput() {
		$(this).attr('size', $(this).val().length);
	}
	
	$(document).ready(function() {
		var dialog = $('#dialog-form');
		dialog.hide();
		
		//Set TextArea Input Height to Correct Values
		$("textarea").height( $("textarea")[0].scrollHeight );
		
		$('input[type="text"]')
		// event handler
		.keyup(resizeInput)
		// resize on page load
		.each(resizeInput);
	});
	
	$(function() {

		var overlay = $('#overlay');
		
		//Edit Operation
		$('#ButtonEditOperation').click(function() {
			var dialog = $('#dialog-form-edit-operation');
			
			var $self = jQuery(this);
			
			var operationID = $self.parent().parent().data("operationid");
			var operationName = "<? echo $operationDetails_Name ?>";
			var operationType = "<? echo $operationDetails_Type ?>";
			var startingLocation = "<? echo $operationDetails_StartingLocation ?>";
			var missionStartDate = "<? echo $operationDetails_StartDate ?>";
			var missionEndDate = "<? echo $operationDetails_EndDate ?>";
			var missionStatus = "<?echo $operationDetails_MissionStatusID?>";
			
			dialog.find('#MissionID').val(operationID).text();
			dialog.find('#OperationName').val(operationName).text();
			dialog.find('#OperationType').val(operationType).text();
			dialog.find('#StartingLocation').val(startingLocation).text();
			dialog.find('#StartDate').val(missionStartDate).text();
			dialog.find('#EndDate').val(missionEndDate).text();
			
			/*
			dialog.find('#Name').val(memName).text();
			dialog.find('#Callsign').val(memCallsign).text();
			dialog.find('#CurrentPassword').val("").text();
			dialog.find('#NewPassword').val("").text();
			*/
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#MissionStatusID').find('#MissionStatusID-' + missionStatus).prop('selected',true);
			
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'blur(2px)'
			});
		});
		
		//Delete Operation (mission)
		$('#ButtonDeleteOperation').click(function() {
			var dialog = $('#dialog-form-delete-operation');
			
			var $self = jQuery(this);
			
			var operationID = $self.parent().parent().data("operationid");
			var operationName = "<? echo $operationDetails_Name ?>";
			var operationType = "<? echo $operationDetails_Type ?>";
			var startingLocation = "<? echo $operationDetails_StartingLocation ?>";
			
			dialog.find('#MissionID').val(operationID).text();
			dialog.find('#OperationName').val(operationName).text();
			dialog.find('#OperationType').val(operationType).text();
			dialog.find('#StartingLocation').val(startingLocation).text();
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'blur(2px)'
			});
		});		
				
		
		//Edit Unit
		$('.ButtonEditOpUnit').click(function() {
			var dialog = $('#dialog-form-edit-opUnit');
			
			var $self = jQuery(this);
			
			var operationID = $self.parent().parent().data("operationid");
			var opUnitID = $self.parent().parent().data("opunitid");
			var opUnitType = $self.parent().parent().data("opunittype");
			var unitID = $self.parent().parent().data("unitid");
			var unitType = $self.parent().parent().data("unittype");
			var opUnitObjectives = $self.parent().parent().find('.OpUnitObjectives').text();
			var opUnitCallsign = $self.parent().parent().data("callsign");
			
			
			dialog.find('#OpUnitObjectives').show();
			dialog.find('#OpUnitObjectivesLabel').show();
			
			dialog.find('#OpTemplateID').val(operationID).text();
			dialog.find('#OpTemplateUnitID').val(opUnitID).text();
			dialog.find('#OpTemplateUnitType').val(opUnitType).text();
			dialog.find('#OpUnitObjectives').val(opUnitObjectives).text();
			dialog.find('#OpUnitCallsign').val(opUnitCallsign).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#UnitID').find('#UnitID-' + unitID).prop('selected',true);
			dialog.find('#OpTemplateUnitType').find('#OpUnitTypeID-' + opUnitType).prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'blur(2px)'
			});
		});

		//Add UnitMember
		$('.ButtonAddOpUnitMember').click(function() {
			var dialog = $('#dialog-form-create-opUnitMember');
			
			var $self = jQuery(this);
			
			var opTemplateID = $self.parent().parent().parent().data("operationid");
			var opTemplateUnitID = $self.parent().parent().parent().data("opunitid");
						
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-default').prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'blur(2px)'
			});
		});	
		
		//Edit UnitMember
		$('.ButtonEditOpUnitMember').click(function() {
			var dialog = $('#dialog-form-edit-opUnitMember');
			
			var $self = jQuery(this);
			
			var rowID = $self.parent().parent().data("rowid");
			var opTemplateID = $self.parent().parent().data("optemplateid");
			var opTemplateUnitID = $self.parent().parent().data("optemplateunitid");
			var opUnitMemberRoleID = $self.parent().parent().data("opunitmemberroleid");
			var memberID = $self.parent().parent().data("memberid");
						
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-' + opUnitMemberRoleID).prop('selected',true);
			if (memberID != null && memberID != "")
			{
				dialog.find('#MemberID').find('#MemberID-' + memberID).prop('selected',true);
				//dialog.find('#MemberID').find('#MemberID-' + memberID).prop('disabled',true);
			}
			else
			{
				dialog.find('#MemberID').find('#MemberID-default').prop('selected',true);
			}
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'blur(2px)'
			});
		});	

		//Remove UnitMember
		$('.ButtonDeleteOpUnitMember').click(function() {
			var dialog = $('#dialog-form-delete-opUnitMember');
			
			var $self = jQuery(this);
			
			var rowID = $self.parent().parent().data("rowid");
			var opTemplateID = $self.parent().parent().data("optemplateid");
			var opTemplateUnitID = $self.parent().parent().data("optemplateunitid");
			var opUnitMemberRoleID = $self.parent().parent().data("opunitmemberroleid");
			var memberID = $self.parent().parent().data("memberid");
						
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-' + opUnitMemberRoleID).prop('selected',true);
			
			if (memberID != null && memberID != "")
			{
				dialog.find('#MemberID').find('#MemberID-' + memberID).prop('selected',true);
				//dialog.find('#MemberID').find('#MemberID-' + memberID).prop('disabled',true);
			}
			else
			{
				dialog.find('#MemberID').find('#MemberID-default').prop('selected',true);
			}
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'blur(2px)'
			});
		});	

		//Add ShipMember
		$('.ButtonAddOpUnitShipMember').click(function() {
			var dialog = $('#dialog-form-create-opUnitShipMember');
			
			var $self = jQuery(this);
			
			var opTemplateID = $self.parent().data("optemplateid");
			var opTemplateUnitID = $self.parent().data("optemplateunitid");
			var opTemplateShipID = $self.parent().data("optemplateshipid");
						
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			dialog.find('#OpTemplateShipID').val(opTemplateShipID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-default').prop('selected',true);
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'blur(2px)'
			});
		});	
		
		//Edit ShipMember
		$('.ButtonEditOpShipMember').click(function() {
			var dialog = $('#dialog-form-edit-opUnitShipMember');
			
			var $self = jQuery(this);
			
			var rowID = $self.parent().parent().data("rowid");
			var opTemplateID = $self.parent().parent().data("optemplateid");
			var opTemplateUnitID = $self.parent().parent().data("optemplateunitid");
			var opTemplateShipID = $self.parent().parent().data("optemplateshipid");
			var opUnitMemberRoleID = $self.parent().parent().data("opunitmemberroleid");
			var memberID = $self.parent().parent().data("memberid");
						
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			dialog.find('#OpTemplateShipID').val(opTemplateShipID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-' + opUnitMemberRoleID).prop('selected',true);
			if (memberID != null && memberID != "")
			{
				dialog.find('#MemberID').find('#MemberID-' + memberID).prop('selected',true);
			}
			else
			{
				dialog.find('#MemberID').find('#MemberID-default').prop('selected',true);
			}
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'blur(2px)'
			});
		});

		//Remove ShipMember
		$('.ButtonDeleteOpShipMember').click(function() {
			var dialog = $('#dialog-form-delete-opUnitShipMember');
			
			var $self = jQuery(this);
			
			var rowID = $self.parent().parent().data("rowid");
			var opTemplateID = $self.parent().parent().data("optemplateid");
			var opTemplateUnitID = $self.parent().parent().data("optemplateunitid");
			var opTemplateShipID = $self.parent().parent().data("optemplateshipid");
			var opUnitMemberRoleID = $self.parent().parent().data("opunitmemberroleid");
			var memberID = $self.parent().parent().data("memberid");
						
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			dialog.find('#OpTemplateShipID').val(opTemplateShipID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-' + opUnitMemberRoleID).prop('selected',true);
			
			if (memberID != null && memberID != "")
			{
				dialog.find('#MemberID').find('#MemberID-' + memberID).prop('selected',true);
			}
			else
			{
				dialog.find('#MemberID').find('#MemberID-0').prop('selected',true);
			}
			
			dialog.show();
			overlay.show();
			$('.operation_main_container').css({
				filter: 'blur(2px)'
			});
			$('#MainPageHeaderText').css({
				filter: 'blur(2px)'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'blur(2px)'
			});
		});			
		
		//UnitMember SignUp
		$('.ButtonSignUpOpUnitMember').click(function() {
			var dialog = $('#dialog-form-edit-opUnitMember');
			
			var $self = jQuery(this);
			
			var rowID = $self.parent().parent().parent().data("rowid");
			var opTemplateID = $self.parent().parent().parent().data("optemplateid");
			var opTemplateUnitID = $self.parent().parent().parent().data("optemplateunitid");
			var opUnitMemberRoleID = $self.parent().parent().parent().data("opunitmemberroleid");
			var memberID = "<? echo $CurrentUserID;?>";
						
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-' + opUnitMemberRoleID).prop('selected',true);
			if (memberID != null && memberID != "")
			{
				dialog.find('#MemberID').find('#MemberID-' + memberID).prop('selected',true);
			}
			else
			{
				dialog.find('#MemberID').find('#MemberID-default').prop('selected',true);
			}
			
			dialog.find('.adminDialogForm').submit();
		});

		//UnitMember Clear
		$('.ButtonClearOpUnitMember').click(function() {
			var dialog = $('#dialog-form-edit-opUnitMember');
			
			var $self = jQuery(this);
			
			var rowID = $self.parent().parent().parent().data("rowid");
			var opTemplateID = $self.parent().parent().parent().data("optemplateid");
			var opTemplateUnitID = $self.parent().parent().parent().data("optemplateunitid");
			var opUnitMemberRoleID = $self.parent().parent().parent().data("opunitmemberroleid");
			var memberID = "0";
						
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-' + opUnitMemberRoleID).prop('selected',true);
			if (memberID != null && memberID != "")
			{
				dialog.find('#MemberID').find('#MemberID-' + memberID).prop('selected',true);
			}
			else
			{
				dialog.find('#MemberID').find('#MemberID-default').prop('selected',true);
			}
			
			dialog.find('.adminDialogForm').submit();
		});
		
		//ShipMember SignUp
		$('.ButtonSignUpOpShipMember').click(function() {
			var dialog = $('#dialog-form-edit-opUnitShipMember');
			
			var $self = jQuery(this);
			
			var rowID = $self.parent().parent().parent().data("rowid");
			var opTemplateID = $self.parent().parent().parent().data("optemplateid");
			var opTemplateUnitID = $self.parent().parent().parent().data("optemplateunitid");
			var opTemplateShipID = $self.parent().parent().parent().data("optemplateshipid");
			var opUnitMemberRoleID = $self.parent().parent().parent().data("opunitmemberroleid");
			var memberID = "<? echo $CurrentUserID;?>";
						
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			dialog.find('#OpTemplateShipID').val(opTemplateShipID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-' + opUnitMemberRoleID).prop('selected',true);
			if (memberID != null && memberID != "")
			{
				dialog.find('#MemberID').find('#MemberID-' + memberID).prop('selected',true);
			}
			else
			{
				dialog.find('#MemberID').find('#MemberID-default').prop('selected',true);
			}
			
			dialog.find('.adminDialogForm').submit();
		});		
		
		//ShipMember Clear
		$('.ButtonClearOpShipMember').click(function() {
			var dialog = $('#dialog-form-edit-opUnitShipMember');
			
			var $self = jQuery(this);
			
			var rowID = $self.parent().parent().parent().data("rowid");
			var opTemplateID = $self.parent().parent().parent().data("optemplateid");
			var opTemplateUnitID = $self.parent().parent().parent().data("optemplateunitid");
			var opTemplateShipID = $self.parent().parent().parent().data("optemplateshipid");
			var opUnitMemberRoleID = $self.parent().parent().parent().data("opunitmemberroleid");
			var memberID = "0";
						
			dialog.find('#RowID').val(rowID).text();
			dialog.find('#OpTemplateID').val(opTemplateID).text();
			dialog.find('#OpTemplateUnitID').val(opTemplateUnitID).text();
			dialog.find('#OpTemplateShipID').val(opTemplateShipID).text();
			
			dialog.find('select').find('option').prop('selected',false);
			dialog.find('#OpUnitMemberRoleID').find('#OpUnitMemberRoleID-' + opUnitMemberRoleID).prop('selected',true);
			if (memberID != null && memberID != "")
			{
				dialog.find('#MemberID').find('#MemberID-' + memberID).prop('selected',true);
			}
			else
			{
				dialog.find('#MemberID').find('#MemberID-default').prop('selected',true);
			}
			
			dialog.find('.adminDialogForm').submit();
		});			
		
		//Cancel
		$('.adminDialogButton.dialogButtonCancel').click(function() {
			
			//Clear DropDown Selections
			$('.adminDiaglogFormFieldset').find('select').find('option').prop('selected',false);
			
			//Hide All Dialog Containers
			$('#dialog-form-edit-operation').hide();
			$('#dialog-form-delete-operation').hide();
			
			$('#dialog-form-create-opUnit').hide();
			$('#dialog-form-edit-opUnit').hide();
			$('#dialog-form-delete-opUnit').hide();
			
			$('#dialog-form-create-opUnitMember').hide();
			$('#dialog-form-edit-opUnitMember').hide();
			$('#dialog-form-delete-opUnitMember').hide();
			
			$('#dialog-form-create-opUnitShip').hide();
			$('#dialog-form-delete-opUnitShip').hide();
			
			$('#dialog-form-create-opUnitShipMember').hide();
			$('#dialog-form-edit-opUnitShipMember').hide();
			$('#dialog-form-delete-opUnitShipMember').hide();
			
			overlay.hide();
			$('.operation_main_container').css({
				filter: 'none'
			});
			$('#MainPageHeaderText').css({
				filter: 'none'
			});
			$('#filtersContainer_OpMenu_Show').css({
				filter: 'none'
			});
		});	
	});
</script>

<!--Script to Show/Hide Operations List Menu when Button is Clicked-->
<script>
	$('#operations_menu_toggle_on').click(function() {
		var overlay = $('#overlay');
		
		//Show Overlay that prevents clicking on main elements
		overlay.show();
		
		//Blur the Main Page
		$('.operation_main_container').css({
			filter: 'blur(2px)'
		});
		$('#MainPageHeaderText').css({
			filter: 'blur(2px)'
		});
		$('#filtersContainer_OpMenu_Show').css({
			filter: 'blur(2px)'
		});
		
		//Show the Operations Menu
		$('#operations_menu_container').css({
			left: '16px'
		});
	});
	
	$('#missions_menu_toggle_on').click(function() {
		var overlay = $('#overlay');
		
		//Show Overlay that prevents clicking on main elements
		overlay.show();
		
		//Blur the Main Page
		$('.operation_main_container').css({
			filter: 'blur(2px)'
		});
		$('#MainPageHeaderText').css({
			filter: 'blur(2px)'
		});
		$('#filtersContainer_OpMenu_Show').css({
			filter: 'blur(2px)'
		});
		
		//Show the Operations Menu
		$('#missions_menu_container').css({
			left: '16px'
		});
	});	
	
	$('#operations_menu_toggle_off').click(function() {
		var overlay = $('#overlay');
		
		//Hide the Operations Menu
		$('#operations_menu_container').css({
			left: '-10000%'
		});
		
		//un-Blur the Main Page
		$('.operation_main_container').css({
			filter: 'none'
		});
		$('#MainPageHeaderText').css({
			filter: 'none'
		});
		$('#filtersContainer_OpMenu_Show').css({
			filter: 'none'
		});
		
		//Hide Overlay that prevents clicking on main elements
		overlay.hide();
	});	
	
	$('#missions_menu_toggle_off').click(function() {
		var overlay = $('#overlay');
		
		//Hide the Operations Menu
		$('#missions_menu_container').css({
			left: '-10000%'
		});
		
		//un-Blur the Main Page
		$('.operation_main_container').css({
			filter: 'none'
		});
		$('#MainPageHeaderText').css({
			filter: 'none'
		});
		$('#filtersContainer_OpMenu_Show').css({
			filter: 'none'
		});
		
		//Hide Overlay that prevents clicking on main elements
		overlay.hide();
	});	
</script>

<!--Script to Show/Hide Admin Buttons-->
<script>
	//Hide all Admin Buttons on Page Load
	$(document).ready(function() {
		
		//Hide the Operations Menu
		$('#operations_menu_container').css({
			left: '-10000%'
		});
		
		//Hide the Operations Menu
		$('#missions_menu_container').css({
			left: '-10000%'
		});
		
		var enableEdit = "<? echo $MaintainEdit; ?>";
		
		if (enableEdit == 'true')
		{
			$('#ButtonEditToggle').addClass("operations_toggleSelected");
			$('.operationsDetails_main').find('.adminButton').show();
			$('.shiftable').css({
				"padding-left": '20px'
			});
		}
		else
		{	
			$('#ButtonEditToggle').removeClass("operations_toggleSelected");
			$('.operationsDetails_main').find('.adminButton').hide();
			$('.operationsDetails_main').find('.adminButton.adminButtonEdit.ButtonSignUpOpUnitMember').show();
			$('.operationsDetails_main').find('.adminButton.adminButtonDelete.ButtonClearOpUnitMember').show();
			$('.operationsDetails_main').find('.adminButton.adminButtonEdit.ButtonSignUpOpShipMember').show();
			$('.operationsDetails_main').find('.adminButton.adminButtonDelete.ButtonClearOpShipMember').show();
			$('.shiftable').css({
				"padding-left": '4px'
			});
		}
    });
	
	$('#ButtonEditToggle').click(function() {
		var $self = jQuery(this);
		
		if ($self.hasClass("operations_toggleSelected"))
		{
			//Toggle is already Enabled - close it!
			$self.removeClass("operations_toggleSelected");
			$('.operationsDetails_main').find('.adminButton').hide();
			$('.operationsDetails_main').find('.adminButton.adminButtonEdit.ButtonSignUpOpUnitMember').show();
			$('.operationsDetails_main').find('.adminButton.adminButtonDelete.ButtonClearOpUnitMember').show();
			$('.operationsDetails_main').find('.adminButton.adminButtonEdit.ButtonSignUpOpShipMember').show();
			$('.operationsDetails_main').find('.adminButton.adminButtonDelete.ButtonClearOpShipMember').show();
			$('.shiftable').css({
				"padding-left": '4px'
			});
		}
		else
		{
			//Toggle is Disabled - open it!
			$self.addClass("operations_toggleSelected");
			$('.operationsDetails_main').find('.adminButton').show();
			$('.shiftable').css({
				"padding-left": '20px'
			});
		}
		
		
	});
</script>

<!--Script to Keep TopMenu Fixed While Scrolling Vertically-->
<script>
	var currentScroll;
	var fixmeTop = $('.operationsDetails_main').offset().top;
	var divToScroll = $('#operations_topMenu_container');
	
	$(window).on( 'scroll', function(){
		currentScroll = $(window).scrollTop();
		
		if (currentScroll <= fixmeTop)
		{
			divToScroll
				.stop()
				.css({
				"position": 'relative',
				"top": 'unset',
				"left": 'unset',
				"z-index": '99',
				"background": 'none',
				"border-bottom": 'none'
			});			
		}
		else
		{
			divToScroll
				.stop()
				.css({
				"position": 'fixed',
				"top": '0px',
				"left": '0px',
				"z-index": '99',
				"background": 'rgba(0, 0, 0, 0.85)',
				"border-bottom": '1px solid rgba(0, 153, 170, 0.9)'
			});			
		}
		

	});
</script>

<!--Script to Show/Hide Rows when Arrows are clicked on each row-->
<script language="javascript">
    $(document).ready(function () {
        $(".shipyard_mainTable_row_content").show();
		$(".shipyard_mainTable_row_header_arrow").addClass('rotate90CW');
		$(".OperationText_Hideable").addClass('hidden');
		
        $(".PayGradeDetails_Entry_Header").click(function () {
            $(this).parent().find(".shipyard_mainTable_row_content").slideToggle(500);
			$(this).find('.shipyard_mainTable_row_header_arrow').toggleClass('rotate90CW');
			$(this).find('.OperationText_Hideable').toggleClass('hidden');
        });		
    });
</script>

<!--Link CONTROLS-->
<script>
	$(document).ready(function($) {
		$(".operationsListItemContainer").click(function() {
			window.document.location = $(this).data("url");
		});
	});	
</script>

<!--Script to Resize Fleet Images-->
<script>

	$(document).ready(function() {
	
		var imageClass = $('.player_fleet');
		
		if(($( window ).width() < 800)) {
			imageClass.jScale({w: '20%'});
			imageClass.css({
					"margin": '0px'
				});
		}	
		else if(($( window ).width() < 1200)){
			imageClass.jScale({w: '40%'});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '60%'});
			imageClass.css({
					"margin": '2px'
				});		
		}
	});
	
	$(window).resize(function () {
	
		var imageClass = $('.player_fleet');
		
		if(($( window ).width() < 800)) {
			imageClass.jScale({w: '20%'});
			imageClass.css({
					"margin": '0px'
				});
			
		}	
		else if(($( window ).width() < 1200)){
			imageClass.jScale({w: '40%'});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '60%'});
			imageClass.css({
					"margin": '2px'
				});		
		}
	});	

</script>