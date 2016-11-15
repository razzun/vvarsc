<?
	/*Get OrgUnits*/
	$getOrgUnits_query = "
		select
			u.UnitID
			,case
				when u.UnitFullName is null or u.UnitFullName = '' then u.UnitName
				else u.UnitFullName
			end as UnitName
			,u.UnitLevel as UnitType
		from projectx_vvarsc2.Units u
		order by
			u.UnitLevel
			,u.UnitName
	";
	
	$getOrgUnits_query_results = $connection->query($getOrgUnits_query);
	$displayOrgUnitsSelectors = "";
	
	while(($row = $getOrgUnits_query_results->fetch_assoc()) != false)
	{
		$UnitID = $row['UnitID'];
		$UnitName = $row['UnitName'];
		$UnitType = $row['UnitType'];
	
		$displayOrgUnitsSelectors .= "
			<option value=\"$UnitID\" id=\"UnitID-$UnitID\">
				$UnitName
			</option>
		";
	}
?>

<?
	/*List LK_OpUnitTypes*/
	$getOpUnitTypes_query = "
		select
			lk1.OpUnitTypeID as ID
			,lk1.OpUnitTypeDescription as Description
		from projectx_vvarsc2.LK_OpUnitTypes lk1
		order by
			lk1.OpUnitTypeDescription
	";
	
	$getOpUnitTypes_query_results = $connection->query($getOpUnitTypes_query);
	$displayOpUnitTypesSelectors = "";
	
	while(($row = $getOpUnitTypes_query_results->fetch_assoc()) != false)
	{
		$ID = $row['ID'];
		$Description = $row['Description'];
	
		$displayOpUnitTypesSelectors .= "
			<option value=\"$ID\" id=\"OpUnitTypeID-$ID\">
				$Description
			</option>
		";
	}	
	/*END List LK_OpUnitTypes*/
?>

<?
	/*List OpUnit Member RoleTypes*/
	$getOpUnitTypeMemberRoles_query = "
		select
			r.OpUnitMemberRoleID
			,RoleCategory
			,RoleName
		from projectx_vvarsc2.OpUnitTypeMemberRoles r
		order by
			r.RoleCategory
			,r.RoleOrderBy
			,r.RoleName
	";
	
	$getOpUnitTypeMemberRoles_query_results = $connection->query($getOpUnitTypeMemberRoles_query);
	$displayOpUnitTypeMemberRolesSelectors = "";
	
	while(($row = $getOpUnitTypeMemberRoles_query_results->fetch_assoc()) != false)
	{
		$ID = $row['OpUnitMemberRoleID'];
		$RoleCategory = $row['RoleCategory'];
		$RoleName = $row['RoleName'];
	
		$displayOpUnitTypeMemberRolesSelectors .= "
			<option value=\"$ID\" id=\"OpUnitMemberRoleID-$ID\">
				$RoleCategory - $RoleName
			</option>
		";
	}	
	/*END List LK_OpUnitTypes*/
?>