<?php include_once('functions/function_auth_user.php'); ?>

<?
	$OperationID = strip_tags(isset($_GET['pid']) ? $_GET['pid'] : '');

	$display_operationsList = "";
	
	$operationsList_query = "
		select
			o.OperationID
			,o.OperationNumber
			,o.OperationName
			,o.OperationType
			,o.Mission
			,DATE_FORMAT(DATE(o.ModifiedOn),'%d %b %Y') as ModifiedOn
			,o.ModifiedBy
			,m.mem_callsign as ModifiedByName
		from projectx_vvarsc2.Operations o
		join projectx_vvarsc2.members m
			on m.mem_id = o.ModifiedBy
	";
	
	$operationsList_query_result = $connection->query($operationsList_query);
	
	while(($row1 = $operationsList_query_result->fetch_assoc()) != false) {
		$operationListItem_ID = $row1['OperationID'];
		$operationListItem_Number = $row1['OperationNumber'];
		$operationListItem_Name = $row1['OperationName'];
		$operationListItem_Type = $row1['OperationType'];
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
						<a href=\"http://sc.vvarmachine.com/operations&pid=$operationListItem_ID\">
							$operationListItem_Number - \"$operationListItem_Name\"
						</a>
					</div>
					<div class=\"operationsListItem_Type\">
						Type: 
						<div class=\"operationsListItem_Type_Value\">
							$operationListItem_Type
						</div>
					</div>
					<div class=\"operationsListItem_Mission\">
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
	
	$display_operationDetails = "";
	
	$operationDetails_query = "
		select
			o.OperationID
			,o.OperationName
			,o.OperationNumber
			,o.OperationType
			,o.StartingLocation
			,o.Mission
			,o.Description
			,o.CreatedBy as CreatedByID
			,DATE_FORMAT(DATE(o.CreatedOn),'%d %b %Y') as CreatedOn
			,CONCAT(r.rank_abbr, ' ', m.mem_callsign) as CreatedByName
			,r.rank_tinyImage as CreatedByRankImage
			,o.ModifiedBy as ModifiedByID
			,DATE_FORMAT(DATE(o.ModifiedOn),'%d %b %Y') as ModifiedOn
			,CONCAT(r2.rank_abbr, ' ', m2.mem_callsign) as ModifiedByName
			,r2.rank_tinyImage as ModifiedByRankImage
		from projectx_vvarsc2.Operations o
		join projectx_vvarsc2.members m
			on m.mem_id = o.CreatedBy
		join projectx_vvarsc2.ranks r
			on r.rank_id = m.ranks_rank_id
		join projectx_vvarsc2.members m2
			on m2.mem_id = o.ModifiedBy
		join projectx_vvarsc2.ranks r2
			on r2.rank_id = m2.ranks_rank_id
		where o.OperationID = '$OperationID'
	";
	$operationDetails_query_result = $connection->query($operationDetails_query);
	
	while(($row2 = $operationDetails_query_result->fetch_assoc()) != false) {
		$operationDetails_ID = $row2['OperationID'];
		$operationDetails_Name = $row2['OperationName'];
		$operationDetails_Number = $row2['OperationNumber'];
		$operationDetails_Type = $row2['OperationType'];
		$operationDetails_StartingLocation = $row2['StartingLocation'];
		$operationDetails_Mission = $row2['Mission'];
		$operationDetails_Description = $row2['Description'];
		
		$operationDetails_CreatedByID = $row2['CreatedByID'];
		$operationDetails_CreatedOn = $row2['CreatedOn'];
		$operationDetails_CreatedByName = $row2['CreatedByName'];
		$operationDetails_CreatedByRankImage = $row2['CreatedByRankImage'];
		
		$operationDetails_ModifiedByID = $row2['ModifiedByID'];
		$operationDetails_ModifiedOn = $row2['ModifiedOn'];
		$operationDetails_ModifiedByName = $row2['ModifiedByName'];
		$operationDetails_ModifiedByRankImage = $row2['ModifiedByRankImage'];
	}
	
	if ($OperationID != null)
	{
		$display_operationDetails = "
			<h3 class=\"operations_h3\">
				\"$operationDetails_Name\"
			</h3>
			<h5 class=\"operations_h5\" style=\"
				background: url(https://robertsspaceindustries.com/rsi/static/images/profile/public-profile-box-title.png) repeat-x;
			\">
				$operationDetails_Number // $operationDetails_Type
			</h5>
				
			<div class=\"operationsListItem_MetaData_Right\">
				<div class=\"clickableRow_memRank_inner\" style=\"
					width: 100%;
					display: block;
				\">
					Created: $operationDetails_CreatedOn
					<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$operationDetails_CreatedByRankImage.png\"/>
					<a href=\"http://sc.vvarmachine.com/player/$operationDetails_CreatedByID\" style=\"
						padding-left: 4px
					\">
						$operationDetails_CreatedByName
					</a>						
				</div>
			</div>
			<div class=\"operationsListItem_MetaData_Right\">
				<div class=\"clickableRow_memRank_inner\" style=\"
					width: 100%;
					display: block;
				\">
					Last Modified: $operationDetails_ModifiedOn
					<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$operationDetails_ModifiedByRankImage.png\"/>
					<a href=\"http://sc.vvarmachine.com/player/$operationDetails_ModifiedByID\" style=\"
						padding-left: 4px
					\">
						$operationDetails_ModifiedByName
					</a>						
				</div>
			</div>
			
			<h4 class=\"operations_h4\">
				Mission Objectives
			</h4>
			<div class=\"WikiText OperationText\" style=\"
				background: none;
				margin-left: 0px;
			\">
				$operationDetails_Mission
			</div>
			
			<h4 class=\"operations_h4\">
				Description and Details
			</h4>
					
			<div class=\"shipDetails_info1_table_ship_desc\" style=\"
				font-style: normal;
				margin-top: 0px;
				padding-bottom: 12px;
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
					$operationDetails_Description
				</div>
			</div>
		";
		
		$display_opUnits_list = "
			<div class=\"unit_description_container\" style=\"
				background-image: none;
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
				ou.OpUnitID
				,ou.OpUnitType
				,u.UnitID
				,u.UnitLevel as UnitType
				,case
					when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
					else u.UnitFullName
				end as UnitName
				,ou.PackageNumber
				,u.UnitCallSign as UnitCallSign
				,ou.CallSign as OpUnitCallsign
			from projectx_vvarsc2.OpUnits ou
			join projectx_vvarsc2.Units u
				on u.UnitID = ou.UnitID
			where ou.OperationID = $OperationID
			order by
				u.UnitID
				,ou.OpUnitID
		";
		$operationUnits_query_result = $connection->query($operationUnits_query);
		
		$CurrentUnitID = "";
		$UnitIndex = 1;
		
		while(($row3 = $operationUnits_query_result->fetch_assoc()) != false) {
			$opUnitsListItem_OpUnitID = $row3['OpUnitID'];
			$opUnitsListItem_OpUnitType = $row3['OpUnitType'];
			$opUnitsListItem_UnitID = $row3['UnitID'];
			$opUnitsListItem_UnitName = $row3['UnitName'];
			$opUnitsListItem_UnitType = $row3['UnitType'];
			$opUnitsListItem_PackageNumber = $row3['PackageNumber'];
			$opUnitsListItem_UnitCallSign = $row3['UnitCallSign'];
			$opUnitsListItem_OpUnitCallsign = $row3['OpUnitCallsign'];
			
			$callSign = "";
			//Callsign Logic for Squadrons
			if ($opUnitsListItem_UnitType == 'Squadron')
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
					if ($opUnitsListItem_OpUnitCallsign == null || $opUnitsListItem_OpUnitCallsign == '')
					{
						$callSign = $opUnitsListItem_UnitCallSign.' 1';
					}
					else
					{
						$callSign = $opUnitsListItem_OpUnitCallsign.' 1';
					}
				}
			}
			//Callsign Logic for Platoons
			else if ($opUnitsListItem_UnitType == 'Platoon')
			{
				//If Previous OrgUnit is same as Current OrgUnit, change callsign to be unique.
				if ($CurrentUnitID == $opUnitsListItem_UnitID)
				{
					$UnitIndex++;
					
					if ($opUnitsListItem_OpUnitCallsign == null || $opUnitsListItem_OpUnitCallsign == '')
					{
						$callSign = ($opUnitsListItem_UnitCallSign.'-'.$UnitIndex);
					}
					else
					{
						$callSign = ($opUnitsListItem_OpUnitCallsign.'-'.$UnitIndex);
					}
				}
				else
				{
					if ($opUnitsListItem_OpUnitCallsign == null || $opUnitsListItem_OpUnitCallsign == '')
					{
						$callSign = $opUnitsListItem_UnitCallSign.'-1';
					}
					else
					{
						$callSign = $opUnitsListItem_OpUnitCallsign.'-1';
					}
				}
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
					margin-bottom: 16px
				\">
					<div class=\"PayGradeDetails_Entry_Header\">
						<h5 h5 class=\"operations_h5\" style=\"
							padding-left: 8px;
							padding-right: 8px;
							margin-left: 0;
							font-size: 12pt;
							font-style: italic;
						\">
							$opUnitsListItem_OpUnitType
						</h5>
					</div>
					<div class=\"WikiText OperationText\" style=\"
						margin-left: 8px;
						background: none;
					\">
						Source Unit: <a href=\"http://sc.vvarmachine.com/unit/$opUnitsListItem_UnitID\"><strong>$opUnitsListItem_UnitName</strong></a>
						<br />
						Callsign: <i>$callSign</i>
					</div>
			";
			
			
			if ($opUnitsListItem_UnitType != "Squadron")
			{
				//GET MEMBERS FOR THIS OpUnit
				$display_opUnit_member_list = "
					<div class=\"OpUnitMemberList\">
						<h6 class=\"operations_h6\">
							Personnel
						</h6>
						<ul class=\"OpUnit_Members\">				
				";
				
				$opUnitMember_query = "
					select
						om.RowID
						,om.OpUnitID
						,om.MemberID
						,om.RoleName
						,om.RoleOrderBy
						,om.mem_name
						,om.rank_tinyImage
						,om.rank_orderBy
					from
					(
						select
							om.RowID
							,om.OpUnitID
							,om.MemberID
							,om.RoleName
							,om.RoleOrderBy
							,CONCAT(r.rank_abbr, ' ', m.mem_callsign) as mem_name
							,r.rank_tinyImage
							,r.rank_orderBy
						from projectx_vvarsc2.OpUnitMembers om
						join projectx_vvarsc2.members m
							on m.mem_id = om.MemberID
						join projectx_vvarsc2.ranks r
							on r.rank_id = m.ranks_rank_id
						where om.OpUnitID = $opUnitsListItem_OpUnitID

						union
						select
							om.RowID
							,om.OpUnitID
							,om.MemberID
							,om.RoleName
							,om.RoleOrderBy
							,null as mem_name
							,null as rank_tinyImage
							,null as rank_orderBy
						from projectx_vvarsc2.OpUnitMembers om
						where om.OpUnitID = $opUnitsListItem_OpUnitID
							and om.MemberID is null
					) om
					order by
						om.RoleOrderBy
						,om.MemberID desc
						,om.RoleName
						,om.rank_orderBy
						,om.mem_name
				";
				
				$opUnitMember_query_result = $connection->query($opUnitMember_query);
				while(($row5 = $opUnitMember_query_result->fetch_assoc()) != false) {
					$opUnitMemberListItem_RowID = $row5['RowID'];
					$opUnitMemberListItem_OpUnitID = $row5['OpUnitID'];
					$opUnitMemberListItem_MemberID = $row5['MemberID'];
					$opUnitMemberListItem_MemName = $row5['mem_name'];
					$opUnitMemberListItem_RoleName = $row5['RoleName'];
					$opUnitMemberListItem_RoleOrderBy = $row5['RoleOrderBy'];
					$opUnitMemberListItem_RankImage = $row5['rank_tinyImage'];
					
					$display_opUnit_member_list .= "
						<li class=\"operations_memRank\">
							<div class=\"clickableRow_memRank_inner\">
					";
					
					if ($opUnitMemberListItem_MemberID != null)
					{
						$display_opUnit_member_list .= "
								<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$opUnitMemberListItem_RankImage.png\"/>
								<div class=\"operations_rank_image_text\">
									<a href=\"http://sc.vvarmachine.com/player/$opUnitMemberListItem_MemberID\" style=\"
									text-decoration: none;
								\">
									$opUnitMemberListItem_MemName
									</a>
								</div>
								<div class=\"operations_rank_image_text\" style=\"
									vertical-align: inherit;
								\">
									- $opUnitMemberListItem_RoleName
								</div>
						";
					}
					else
					{
						$display_opUnit_member_list .= "
								<div class=\"operations_rank_image_text\" style=\"
									vertical-align: inherit;
								\">
									unassigned - $opUnitMemberListItem_RoleName
								</div>
						";						
					}
								
					$display_opUnit_member_list .= "			
							</div>
						</li>
					";
				}
				
				//Close list of Members
				$display_opUnit_member_list .= "
						</ul>
					</div>
				";
				
				//Add MemberList to UnitList
				$display_opUnits_list .= "
					$display_opUnit_member_list
				";				
			}
			else
			{
				//GET SHIPS FOR THIS OpUnit
				$display_opUnit_ships_list = "
					<div class=\"OpUnitShipsList\">
						<h6 class=\"operations_h6\">
							Equipment
						</h6>
						<table class=\"OpUnit_Ships\">
				";
				
				$opUnitShips_query = "
					select
						os.OpShipID
						,os.OpUnitID
						,os.ShipID
						,s.ship_silo
						,s.ship_name
						,s.ship_model_designation
						,s.ship_model_visible
						,m.manu_shortName
						,os.Callsign
					from projectx_vvarsc2.OpShips os
					join projectx_vvarsc2.ships s
						on s.ship_id = os.ShipID
					join projectx_vvarsc2.manufacturers m
						on m.manu_id = s.manufacturers_manu_id
					where os.OpUnitID = $opUnitsListItem_OpUnitID
					order by
						os.OpShipOrderBy
				";
				
				$full_ship_name = "";
				$shipIndex = 1;
				$opUnitShips_query_result = $connection->query($opUnitShips_query);
				
				while(($row4 = $opUnitShips_query_result->fetch_assoc()) != false) {
					$opUnitShipsListItem_OpShipID = $row4['OpShipID'];
					$opUnitShipsListItem_ShipID = $row4['ShipID'];
					$opUnitShipsListItem_ShipSilo = $row4['ship_silo'];
					$opUnitShipsListItem_ShipName = $row4['ship_name'];
					$opUnitShipsListItem_ShipModel = $row4['ship_model_designation'];
					$opUnitShipsListItem_ShipModelVisible = $row4['ship_model_visible'];
					$opUnitShipsListItem_ManuShortName = $row4['manu_shortName'];
					$opUnitShipsListItem_ShipCallsign = $row4['Callsign'];
					
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

					if ($opUnitShipsListItem_ShipCallsign == null || $opUnitShipsListItem_ShipCallsign == '')
					{
						$opUnitShipsListItem_ShipCallsign = $callSign;
					}
					
					$display_opUnit_ships_list .= "
						<tr class=\"player_ships_row\">
							<td class=\"player_ships_entry\">
								<div class=\"player_ships_shipTitle\">
									<div class=\"player_ships_shipTitleContainer\">
										<a href=\"http://sc.vvarmachine.com/ship/$opUnitShipsListItem_ShipID\" >
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
													$opUnitShipsListItem_ShipCallsign-$shipIndex
													</div>
												</td>
											</tr>
										</table>
					";
					
					//GET Members for this Ship
					$display_opUnitShipMembers = "
						<div class=\"OpUnitMemberList\" style=\"
							padding-top: 4px;
						\">
							<h6 class=\"operations_h6\">
								Personnel
							</h6>
							<ul class=\"OpUnit_Members\">						
					";
					
					$opUnitShipMembers_query = "
						select
							osm.RowID
							,osm.OpShipID
							,osm.MemberID
							,osm.RoleName
							,osm.RoleOrderBy
							,osm.mem_name
							,osm.rank_tinyImage
							,osm.rank_orderBy
						from 
						(
							select
								osm.RowID
								,osm.OpShipID
								,osm.MemberID
								,osm.RoleName
								,osm.RoleOrderBy
								,CONCAT(r.rank_abbr, ' ', m.mem_callsign) as mem_name
								,r.rank_tinyImage
								,r.rank_orderBy
							from projectx_vvarsc2.OpShipMembers osm
							join projectx_vvarsc2.members m
								on m.mem_id = osm.MemberID
							join projectx_vvarsc2.ranks r
								on r.rank_id = m.ranks_rank_id
							where osm.OpShipID = $opUnitShipsListItem_OpShipID

							union
							select
								osm.RowID
								,osm.OpShipID
								,osm.MemberID
								,osm.RoleName
								,osm.RoleOrderBy
								,null as mem_name
								,null as rank_tinyImage
								,null as rank_orderBy
							from projectx_vvarsc2.OpShipMembers osm
							where osm.OpShipID = $opUnitShipsListItem_OpShipID
								and osm.MemberID is null
						) osm
						order by
							osm.RoleOrderBy
							,osm.MemberID desc
							,osm.RoleName
							,osm.rank_orderBy
							,osm.mem_name
					";
					
					$opUnitShipMembers_query_result = $connection->query($opUnitShipMembers_query);
				
					while(($row6 = $opUnitShipMembers_query_result->fetch_assoc()) != false) {
						$opShipMembersListItem_RowID = $row6['RowID'];
						$opShipMembersListItem_OpShipID = $row6['OpShipID'];
						$opShipMembersListItem_MemberID = $row6['MemberID'];
						$opShipMembersListItem_RoleName = $row6['RoleName'];
						$opShipMembersListItem_RoweOrderBy = $row6['RoleOrderBy'];
						$opShipMembersListItem_MemName = $row6['mem_name'];
						$opShipMembersListItem_RankImage = $row6['rank_tinyImage'];
						
						$display_opUnitShipMembers .= "
							<li class=\"operations_memRank\">
								<div class=\"clickableRow_memRank_inner\">
						";
						
						if ($opShipMembersListItem_MemberID != null)
						{
							$display_opUnitShipMembers .= "
									<img class=\"clickableRow_memRank_Image\" src=\"http://sc.vvarmachine.com/images/ranks/TS3/$opShipMembersListItem_RankImage.png\"/>
									<div class=\"operations_rank_image_text\">
										<a href=\"http://sc.vvarmachine.com/player/$opShipMembersListItem_MemberID\" style=\"
										text-decoration: none;
									\">
										$opShipMembersListItem_MemName
										</a>
									</div>
									<div class=\"operations_rank_image_text\" style=\"
										vertical-align: inherit;
									\">
										- $opShipMembersListItem_RoleName
									</div>
							";
						}
						else
						{
							$display_opUnitShipMembers .= "
									<div class=\"operations_rank_image_text\" style=\"
										vertical-align: inherit;
									\">
										unassigned - $opShipMembersListItem_RoleName
									</div>
							";						
						}
									
						$display_opUnitShipMembers .= "			
								</div>
							</li>
						";						
					}
					
					//Close list of Members
					$display_opUnitShipMembers .= "
							</ul>
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
							<td class=\"player_ships_entry_ship\" style=\"
								width: auto;
							\">
								<div class=\"player_ships_entry_ship_inner\">
									<div class=\"player_ships_entry_ship_inner_imageContainer\">
										<a href=\"http://sc.vvarmachine.com/ship/$opUnitShipsListItem_ShipID\" >
											<img class=\"player_fleet\" align=\"center\" src=\"http://sc.vvarmachine.com/images/silo_topDown/$opUnitShipsListItem_ShipSilo\" />
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
			";
			
			$CurrentUnitID = $opUnitsListItem_UnitID;
			$UnitIndex = 1;
		}
		
		//Close List of Units
		$display_opUnits_list .= "
				</div>
			</div>
		";
		
	}
	else
	{
		$display_operationDetails = "Please Select an Operation from the list.";
	}

	$connection->close();
?>
<h2>Operations Manager</h2>
<div id="TEXT">
	<div class="tbinfo_container">
		<div class="tbinfo_right">
			<div id="operations_menu_inner"> <!--THIS IS THE DIV THAT REMAINS FIXED WHEN SCROLLING-->

				<div class="operations_menu_inner_items_container">
					<? echo $display_operationsList; ?>
				</div>
			</div>
		</div>
		<div class="operation_main_container">
			<div class="table_header_block2_long">
			</div>
			<div class="operationsDetails_main">
				<? echo $display_operationDetails; ?>
				<br />
				<? echo $display_opUnits_list; ?>
			</div>
		</div>
	</div>
</div>
  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.jScale.js"></script>
<script type="text/javascript" src="http://www.wduffy.co.uk/blog/wp-content/themes/agregado/js/jquery.jscroll.min.js"></script>

<!--Script to Keep Right-Hand-Detail Elements Fixed While Scrolling Vertically-->
<script>
	var currentScroll;
	var fixmeTop = $('.tbinfo_right').offset().top;
	var divToScroll = $('#operations_menu_inner');
	
	$(window).on( 'scroll', function(){
		currentScroll = $(window).scrollTop();
		
		if (currentScroll <= fixmeTop)
		{
			divToScroll
				.stop()
				.css({"marginTop": (0 + 12) + "px"});			
		}
		else
		{
			divToScroll
				.stop()
				.css({"marginTop": (currentScroll - fixmeTop + 12) + "px"});			
		}
		

	});

</script>
