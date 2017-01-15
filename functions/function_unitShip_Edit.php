<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']))
	{
      header('Location: ../login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$RowID = "";
	$UnitID = "";
	$ShipID = "";
	$Purpose = "";
	 
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
	}
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
	 
	$q = "UPDATE projectx_vvarsc2.UnitShips set
			UnitID = '$UnitID'
			,ShipID = '$ShipID'
			,Purpose = '$Purpose'
		WHERE RowID = '$RowID'";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: ?page=admin_unit&pid=$UnitID");
	}
	else
	{
		header("Location: ../error_generic");
	}
	
	$connection->close();
?>