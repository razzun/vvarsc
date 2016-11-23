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

	$MissionID = "";
	 
	if(isset($_POST['MissionID']))
	{
		$MissionID = $_POST['MissionID'];
	}
	 
	$q = "
		delete from projectx_vvarsc2.Missions where MissionID = $MissionID;
		delete from projectx_vvarsc2.MissionUnits where MissionUnitID > 0 and MissionID = $MissionID;
		delete from projectx_vvarsc2.MissionUnitMembers where RowID > 0 and MissionID = $MissionID;
		delete from projectx_vvarsc2.MissionShips where MissionShipID > 0 and MissionID = $MissionID;
		delete from projectx_vvarsc2.MissionShipMembers where RowID > 0 and MissionID = $MissionID;
	";

	$_SESSION['maintain_edit'] = 'true';
	$query_result = $connection->multi_query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/missions");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>