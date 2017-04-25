<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	//print_r($_POST);
	require_once('../../dbconn/dbconn.php');
	
	session_start();

	$MemID = "";
	$AwardID = "";
	
	if(isset($_POST['MemID']))
	{
		$MemID = $_POST['MemID'];
	}
	if(isset($_POST['AwardID']))
	{
		$AwardID = $_POST['AwardID'];
	}
	 
	$q = "
		INSERT INTO projectx_vvarsc2.member_Awards (
			MemberID
			,AwardID
			,ModifiedOn
		)
		VALUES (
			$MemID
			,$AwardID
			,DATE_ADD(UTC_TIMESTAMP(), INTERVAL 930 YEAR)
		)
	";
	
	//print_r($q);
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/player/$MemID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>