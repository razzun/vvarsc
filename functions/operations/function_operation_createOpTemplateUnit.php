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
	$OpTemplateUnitType = "";
	$UnitID = "";
	$PlayerCount = "";
	$ShipID = "";
	 
	if(isset($_POST['OpTemplateID']))
	{
		$OpTemplateID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitType']))
	{
		$OpTemplateUnitType = $_POST['OpTemplateUnitType'];
	}
	if(isset($_POST['UnitID']))
	{
		$UnitID = $_POST['UnitID'];
	}
	if(isset($_POST['PlayerCount']))
	{
		$PlayerCount = $_POST['PlayerCount'];
	}
	if(isset($_POST['ShipID']))
	{
		$ShipID = $_POST['ShipID'];
	}
	 
	$q = "
		call projectx_vvarsc2.BuildOpTemplateUnit(
			'$OpTemplateID'
			,'$OpTemplateUnitType'
			,SUBSTRING('$UnitID',(LOCATE('_','$UnitID') + 1),LENGTH('$UnitID'))
			,'$PlayerCount'
			,'$ShipID'
			,'$userID'
		);
	";
	
	print_r($q);
	
	$_SESSION['maintain_edit'] = 'true';

	$query_result = $connection->multi_query($q);
			
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