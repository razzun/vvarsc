<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
	$userID = $_SESSION['sess_user_id'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	require_once('../../dbconn/dbconn.php');
	
	print_r($_POST);

	$MissionID = "";
	$MissionUnitID = "";
	$MissionUnitType = "";
	$UnitID = "";
	$OpUnitObjectives = "";
	 
	if(isset($_POST['OpTemplateID']))
	{
		$MissionID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitID']))
	{
		$MissionUnitID = $_POST['OpTemplateUnitID'];
	}
	if(isset($_POST['OpTemplateUnitType']))
	{
		$MissionUnitType = $_POST['OpTemplateUnitType'];
	}
	if(isset($_POST['UnitID']))
	{
		$UnitID = $_POST['UnitID'];
	}
	if(isset($_POST['OpUnitObjectives']))
	{
		$OpUnitObjectives = mysqli_real_escape_string($connection, $_POST['OpUnitObjectives']);
	}
	 
	$_SESSION['maintain_edit'] = 'true';
	$q = "
		UPDATE projectx_vvarsc2.MissionUnits set
			UnitID = SUBSTRING('$UnitID',(LOCATE('_','$UnitID') + 1),LENGTH('$UnitID'))
			,MissionUnitObjectives = '$OpUnitObjectives'
		where MissionUnitID = '$MissionUnitID'
			and MissionID = '$MissionID'
	";
	
	//print_r($q);

	$query_result = $connection->query($q);
	
	if ($query_result)
	{
		header("Location: $link_base/mission/$MissionID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>