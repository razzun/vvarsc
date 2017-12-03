<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$UnitRoleID = "";
	$UnitID = "";
	$RoleID = "";
	$QualificationID = "";
	$Level = "";
	
	if(isset($_POST['UnitRoleID']))
	{
		$UnitRoleID = $_POST['UnitRoleID'];
	}
	if(isset($_POST['UnitID']))
	{
		$UnitID = $_POST['UnitID'];
	}
	if(isset($_POST['RoleID']))
	{
		$RoleID = $_POST['RoleID'];
	}
	if(isset($_POST['QualificationID']))
	{
		$QualificationID = $_POST['QualificationID'];
	}
	if (isset($_POST['Level']))
	{
		$Level = $_POST['Level'];
	}
	 
	$q = "
		INSERT INTO projectx_vvarsc2.UnitQualifications (
			UnitID
			,RoleID
			,QualificationID
			,QualificationLevel
		)
		VALUES (
			$UnitID
			,$RoleID
			,SUBSTRING('$QualificationID',(LOCATE('_','$QualificationID') + 1),LENGTH('$QualificationID'))
			,$Level
		)
	";
	
	//print_r($q);
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_unitRole&pid=$UnitRoleID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>