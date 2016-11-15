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
	$MissionUnitID = "";
	 
	if(isset($_POST['OpTemplateID']))
	{
		$MissionID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitID']))
	{
		$MissionUnitID = $_POST['OpTemplateUnitID'];
	}
	
	//Delete UnitShipMembers
	$q1 = "
		DELETE from projectx_vvarsc2.MissionShipMembers
		where MissionUnitID = '$MissionUnitID'
			and MissionID = '$MissionID'
			and RowID > 0
	";
	
	//Delete UnitShips
	$q2 = "
		DELETE from projectx_vvarsc2.MissionUnitShips
		where MissionUnitID = '$MissionUnitID'
			and MissionID = '$MissionID'
			and OpTemplateShipID > 0
	";	
	
	//Delete OpUnit
	$q3 = "
		DELETE from projectx_vvarsc2.MissionUnits
		where MissionUnitID = '$MissionUnitID'
			and MissionID = '$MissionID'
	";		
	
	print_r($q1);
	print_r($q2);
	print_r($q3);
	$_SESSION['maintain_edit'] = 'true';
	
	$query1_result = $connection->query($q1);
	$query2_result = $connection->query($q2);
	$query3_result = $connection->query($q3);
			
	if ($query3_result)
	{
		header("Location: http://sc.vvarmachine.com/mission/$MissionID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>