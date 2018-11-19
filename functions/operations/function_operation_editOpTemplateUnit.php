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


	$OpTemplateID = "";
	$OpTemplateUnitID = "";
	$OpTemplateUnitType = "";
	$UnitID = "";
	$OpUnitObjectives = "";
	$OpUnitCallsign = "";
	 
	if(isset($_POST['OpTemplateID']))
	{
		$OpTemplateID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitID']))
	{
		$OpTemplateUnitID = $_POST['OpTemplateUnitID'];
	}
	if(isset($_POST['OpTemplateUnitType']))
	{
		$OpTemplateUnitType = $_POST['OpTemplateUnitType'];
	}
	if(isset($_POST['UnitID']))
	{
		$UnitID = $_POST['UnitID'];
	}
	if(isset($_POST['OpUnitObjectives']))
	{
		$OpUnitObjectives = mysqli_real_escape_string($connection, $_POST['OpUnitObjectives']);
	}
	if(isset($_POST['OpUnitCallsign']))
	{
		$OpUnitCallsign = mysqli_real_escape_string($connection, $_POST['OpUnitCallsign']);
	}
	 
	$q = "
		UPDATE projectx_vvarsc2.OpTemplateUnits set
			UnitID = SUBSTRING('$UnitID',(LOCATE('_','$UnitID') + 1),LENGTH('$UnitID'))
			,OpTemplateUnitType = '$OpTemplateUnitType'
			,OpUnitObjectives = '$OpUnitObjectives'
			,Callsign = '$OpUnitCallsign'
		where OpTemplateUnitID = '$OpTemplateUnitID'
			and OpTemplateID = '$OpTemplateID'
	";
	
	print_r($q);
	
	$_SESSION['maintain_edit'] = 'true';

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/operation/$OpTemplateID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>