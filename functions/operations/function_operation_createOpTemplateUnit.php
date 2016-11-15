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

	$OpTemplateID = "";
	$OpTemplateUnitType = "";
	$UnitID = "";
	 
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
	 
	$q = "
		INSERT INTO projectx_vvarsc2.OpTemplateUnits (
			OpTemplateUnitType
			,OpTemplateID
			,UnitID
			,OpUnitObjectives
			,PackageNumber
			,Callsign
		)
		VALUES (
			'$OpTemplateUnitType'
			,'$OpTemplateID'
			,'$UnitID'
			,null
			,1
			,null
		)
	";
	
	print_r($q);
	
	$_SESSION['maintain_edit'] = 'true';

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/operation/$OpTemplateID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>