<?
	//Query to get Available Roles for Unit
	$getAvailableRolesForUnit_query = "
		select
			r.role_id
			,r.role_name
            ,case
				when r.role_name like '<%' or ur.RoleID is not null then 1
                else 0
			end as RoleDisabled
		from projectx_vvarsc2.roles r
        left join projectx_vvarsc2.UnitRoles ur
			on ur.RoleID = r.role_id
            and ur.UnitID = $unit_id
		order by
			r.role_orderby
			,r.role_name
	";
	
	$getAvailableRolesForUnit_query_results = $connection->query($getAvailableRolesForUnit_query);
	$displayAvailableRolesForUnit = "";
	
	while(($row = $getAvailableRolesForUnit_query_results->fetch_assoc()) != false)
	{
		$RoleID = $row['role_id'];
		$RoleName = $row['role_name'];
		$RoleDisabled = $row['RoleDisabled'];
		
		if ($RoleDisabled == 1)
		{
			$displayAvailableRolesForUnit .= "
				<option disabled value=\"$RoleID\" id=\"RoleID-$RoleID\">
					$RoleName
				</option>
			";
		}
		else
		{
			$displayAvailableRolesForUnit .= "
				<option value=\"$RoleID\" id=\"RoleID-$RoleID\">
					$RoleName
				</option>
			";
		}
	}
?>