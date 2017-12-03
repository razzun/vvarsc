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

	$RowID = "";
	$UnitRoleID = "";
	$UnitID = "";
	$RoleID = "";
	$QualificationID = "";
	$Level = "";
	
	
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
	}
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
		DELETE FROM projectx_vvarsc2.UnitQualifications
		where RowID = $RowID
	";
	
	print_r($q);
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