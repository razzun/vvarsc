<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
	$userID = $_SESSION['sess_user_id'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<?php
	require_once('../../dbconn/dbconn.php');
	
	print_r($_POST);
	$ShipID = "";
	$MissionID = "";
	$MissionUnitID = "";
	 
	if(isset($_POST['ShipID']))
	{
		$ShipID = $_POST['ShipID'];
	}
	if(isset($_POST['OpTemplateID']))
	{
		$MissionID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitID']))
	{
		$MissionUnitID = $_POST['OpTemplateUnitID'];
	}
	 
	$q = "
		set @MaxOrderBy = (
			select
				IFNULL(MAX(MissionShipOrderBy),0) + 1
			from projectx_vvarsc2.MissionShips
			where MissionUnitID = '$MissionUnitID'
				and MissionID = '$MissionID'
		);		
	
		INSERT INTO projectx_vvarsc2.MissionShips (
			MissionUnitID
			,ShipID
			,Callsign
			,MissionShipOrderBy
			,MissionID
		)
		VALUES
		(
			$MissionUnitID
			,$ShipID
			,null
			,@MaxOrderBy
			,'$MissionID'
		)
	";
	
	print_r($q);
	$_SESSION['maintain_edit'] = 'true';
	$query_result = $connection->multi_query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/mission/$MissionID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>