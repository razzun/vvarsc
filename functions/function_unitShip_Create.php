<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']))
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$UnitID = "";
	$ShipID = "";
	$Purpose = "";
	 
	if(isset($_POST['UnitID']))
	{
		$UnitID = $_POST['UnitID'];
	}
	if (isset($_POST['ShipID']))
	{
		$ShipID = $_POST['ShipID'];
	}
	if (isset($_POST['Purpose']))
	{
		$Purpose = mysqli_real_escape_string($connection, $_POST['Purpose']);
	}
	 
	$q = "INSERT into projectx_vvarsc2.UnitShips (
				UnitID
				,ShipID
				,Purpose
			)
			VALUES(
				'$UnitID'
				,'$ShipID'
				,'$Purpose'
			)";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/admin/?page=admin_unit&pid=$UnitID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>