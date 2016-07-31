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
	
	require_once('../../dbconn/dbconn.php');
	
	session_start();

	$RowID = "";
	$MemberID = "";
	$ShipID = "";
	$Package = "";
	$LTI = "";
	 
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
	if (isset($_POST['Package']))
	{
		$Package = $_POST['Package'];
	}
	if (isset($_POST['LTI']))
	{
		$LTI = $_POST['LTI'];
	}
	 
	$q = "UPDATE projectx_vvarsc2.ships_has_members set
				ships_ship_id = '$ShipID'
				,members_mem_id = '$MemberID'
				,shm_package = '$Package'
				,shm_lti = '$LTI'
				,ModifiedOn = DATE_ADD(CURDATE(),INTERVAL 930 YEAR)
			where RowID = '$RowID'
		";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/player&pid=$MemberID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>