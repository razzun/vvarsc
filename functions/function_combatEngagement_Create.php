<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
	$userID = $_SESSION['sess_user_id'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	//print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	session_start();

	$MemberID = "";
	$TargetPlayer = "";
	$TimeOfEngagement = "";
	$IsSoloKill = "";
	$KillType = "";
	$ShipID = "";
	$TargetPlayerShip = "";
	$ConfirmedBy = "";
	$KillNotes = "";
	
	if (isset($_POST['MemberID']))
	{
		$MemberID = $_POST['MemberID'];
	}
	if(isset($_POST['TargetPlayer']))
	{
		$TargetPlayer = mysqli_real_escape_string($connection, $_POST['TargetPlayer']);
	}
	if(isset($_POST['TimeOfEngagement']))
	{
		$TimeOfEngagement = mysqli_real_escape_string($connection, $_POST['TimeOfEngagement']);
	}
	if (isset($_POST['IsSoloKill']))
	{
		$IsSoloKill = $_POST['IsSoloKill'];
	}
	if(isset($_POST['KillType']))
	{
		$KillType = mysqli_real_escape_string($connection, $_POST['KillType']);
	}
	if (isset($_POST['ShipID']))
	{
		$ShipID = $_POST['ShipID'];
	}
	if (isset($_POST['TargetPlayerShip']))
	{
		$TargetPlayerShip = $_POST['TargetPlayerShip'];
	}
	if (isset($_POST['ConfirmedBy']))
	{
		$ConfirmedBy = $_POST['ConfirmedBy'];
	}
	if(isset($_POST['KillNotes']))
	{
		$KillNotes = mysqli_real_escape_string($connection, $_POST['KillNotes']);
	}
	 
	$q = "INSERT into projectx_vvarsc2.MemberKills (
			MemberID
			,KillType
			,ShipID
			,TargetPlayer
			,TargetPlayerShip
			,TimeOfEngagement
			,ConfirmedBy
			,IsSoloKill
			,CreatedOn
			,CreatedBy
			,Notes
		) VALUES (
			'$MemberID'
			,'$KillType'
			,'$ShipID'
			,'$TargetPlayer'
			,'$TargetPlayerShip'
			,'$TimeOfEngagement'
			,'$ConfirmedBy'
			,$IsSoloKill
			,DATE_ADD(UTC_TIMESTAMP(), INTERVAL 930 YEAR)
			,'$userID'
			,'$KillNotes'
		)
	";
	
	//print_r($q);
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/combatRecord/$MemberID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>