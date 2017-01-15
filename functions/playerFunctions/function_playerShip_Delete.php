<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']))
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../../dbconn/dbconn.php');
	
	session_start();

	$RowID = "";
	$MemberID = "";
	$ShipID = "";
	 
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
	}
	if(isset($_POST['MemberID']))
	{
		$MemberID = $_POST['MemberID'];
	}
	if (isset($_POST['ShipID']))
	{
		$ShipID = $_POST['ShipID'];
	}
	 
	$q = "DELETE from projectx_vvarsc2.ships_has_members where RowID = '$RowID'
		";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/player&pid=$MemberID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>