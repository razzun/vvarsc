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

	$UnitID = "";
	$RowID = "";
	$RoleID = "";
	$MinPayGrade = "";
	$MaxPayGrade = "";
	$IsActive = "";
	
	if(isset($_POST['UnitID']))
	{
		$UnitID = $_POST['UnitID'];
	}	
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
	}		
	if(isset($_POST['RoleID']))
	{
		$RoleID = $_POST['RoleID'];
	}	
	if(isset($_POST['MinPayGrade']))
	{
		$MinPayGrade = $_POST['MinPayGrade'];
	}
	if(isset($_POST['MaxPayGrade']))
	{
		$MaxPayGrade = $_POST['MaxPayGrade'];
	}	
	if(isset($_POST['IsActive']))
	{
		$IsActive = $_POST['IsActive'];
	}
	
	$q = "DELETE from projectx_vvarsc2.UnitRoles
		WHERE RowID = '$RowID'
			and UnitID = '$UnitID'
		";
	//print_r($q);
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_unit&pid=$UnitID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>