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

	$MemberID = "";
	$ShipID = "";
	$Package = "";
	$LTI = "";
	 
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
	 
	$q = "INSERT into projectx_vvarsc2.ships_has_members (
				ships_ship_id
				,members_mem_id
				,shm_package
				,shm_lti
				,CreatedOn
				,ModifiedOn
			)
			VALUES(
				'$ShipID'
				,'$MemberID'
				,'$Package'
				,'$LTI'
				,DATE_ADD(CURDATE(),INTERVAL 930 YEAR)
				,DATE_ADD(CURDATE(),INTERVAL 930 YEAR)
			)";

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