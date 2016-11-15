<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
	$userID = $_SESSION['sess_user_id'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<?php
	require_once('../../dbconn/dbconn.php');
	
	print_r($_POST);

	$MissionID = "";
	$Name = "";
	$Type = "";
	$StartingLocation = "";
	$StartDate = "";
	$Mission = "";
	$Description = "";
	 
	if(isset($_POST['MissionID']))
	{
		$MissionID = $_POST['MissionID'];
	}
	if(isset($_POST['OperationName']))
	{
		$Name = mysqli_real_escape_string($connection, $_POST['OperationName']);
	}
	if(isset($_POST['OperationType']))
	{
		$Type = mysqli_real_escape_string($connection, $_POST['OperationType']);
	}
	if(isset($_POST['StartingLocation']))
	{
		$StartingLocation = mysqli_real_escape_string($connection, $_POST['StartingLocation']);
	}
	if(isset($_POST['StartDate']))
	{
		$StartDate = mysqli_real_escape_string($connection, $_POST['StartDate']);
	}
	if(isset($_POST['MissionSummary']))
	{
		$Mission = mysqli_real_escape_string($connection, $_POST['MissionSummary']);
	}
	if(isset($_POST['ObjectiveDetails']))
	{
		$Description = mysqli_real_escape_string($connection, $_POST['ObjectiveDetails']);
	}
	 
	$q = "
		UPDATE projectx_vvarsc2.Missions set
			MissionName = '$Name'
			,MissionType = '$Type'
			,StartingLocation = '$StartingLocation'
			,StartDate = '$StartDate'
			,Mission = '$Mission'
			,Description = '$Description'
			,ModifiedOn = DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
			,ModifiedBy = '$userID'
		where MissionID = '$MissionID'
	";

	$_SESSION['maintain_edit'] = 'true';
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/mission/$MissionID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>