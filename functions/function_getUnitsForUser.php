<?php 

	function getUnitsForUser($connection, $userID)
	{
		$units = array();
		
		if (is_numeric($userID))
		{
			$unitsQuery = "
				select distinct
					u.UnitID
				from projectx_vvarsc2.UnitMembers um
				join projectx_vvarsc2.Units u
					on u.UnitID = um.UnitID
				where um.MemberID = '$userID'
			";
			
			$unitsQueryResult = $connection->query($unitsQuery);
			
			while(($row = $unitsQueryResult->fetch_assoc()) != false) {
			
				$units[$row['UnitID']] = array(
					'UnitID' => $row['UnitID']
				);
			}			
		}
		
		return $units;
	}
?>