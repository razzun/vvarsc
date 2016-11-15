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
	
	//print_r($_POST);
	
	$ID = "";
	$Name = "";
	$Type = "";
	$StartingLocation = "";
	$StartDate = "";
	$Mission = "";
	$Description = "";
	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
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
	
	$q1 = "
		call projectx_vvarsc2.BuildMissionFromOpTemplate($ID,'$StartDate',$userID);
	";

	print_r($q1);
	
	$_SESSION['maintain_edit'] = 'true';
	
	$query1_result = $connection->multi_query($q1);
	
	if ($query1_result)
	{
		header("Location: http://sc.vvarmachine.com/missions");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
	
?>