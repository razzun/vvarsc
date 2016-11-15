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

	$RowID = "";
	$MissionID = "";
	$MissionUnitID = "";
	 
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
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
		DELETE FROM projectx_vvarsc2.MissionShips
		where MissionShipID = $RowID
			and MissionUnitID = '$MissionUnitID'
	";
	
	$q2 = "
		DELETE FROM projectx_vvarsc2.MissionShipMembers
		where RowID > 0
			and MissionShipID = '$RowID'
	";
	
	//print_r($q);
	$_SESSION['maintain_edit'] = 'true';

	$query_result = $connection->query($q);
	$query2_result = $connection->query($q2);
			
	if ($query_result && $query2_result)
	{
		header("Location: http://sc.vvarmachine.com/mission/$MissionID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>