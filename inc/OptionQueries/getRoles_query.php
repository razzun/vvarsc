<?
	//Query for Roles Drop-Down Menu (all roles)
	$roles_query = "
		select
			r.role_id
			,r.role_name
            ,case
				when r.role_name like '<%' then 1
                else 0
			end as RoleDisabled
		from projectx_vvarsc2.roles r
		order by
			r.role_orderby
			,r.role_name
	";
	
	$roles_query_results = $connection->query($roles_query);
	$displayRoles = "";
	
	while(($row = $roles_query_results->fetch_assoc()) != false)
	{
		$RoleID = $row['role_id'];
		$RoleName = $row['role_name'];
		$RoleDisabled = $row['RoleDisabled'];
		
		if ($RoleDisabled == 1)
		{
			$displayRoles .= "
				<option disabled value=\"$RoleID\" id=\"RoleID-$RoleID\">
					$RoleName
				</option>
			";
		}
		else
		{
			$displayRoles .= "
				<option value=\"$RoleID\" id=\"RoleID-$RoleID\">
					$RoleName
				</option>
			";
		}
	}
?>