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
	require_once('../../dbconn/dbconn.php');
	
	//print_r($_POST);

	$Name = "";
	$Type = "";
	$StartingLocation = "";
	$Mission = "";
	$Description = "";
	 
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
	if(isset($_POST['MissionSummary']))
	{
		$Mission = mysqli_real_escape_string($connection, $_POST['MissionSummary']);
	}
	if(isset($_POST['ObjectiveDetails']))
	{
		$Description = mysqli_real_escape_string($connection, $_POST['ObjectiveDetails']);
	}
	 
	$q = "
		INSERT INTO projectx_vvarsc2.OpTemplates (
			OpTemplateName
			,OpTemplateType
			,StartingLocation
			,Mission
			,Description
			,CreatedOn
			,CreatedBy
			,ModifiedOn
			,ModifiedBy
		)
		VALUES (
			'$Name'
			,'$Type'
			,'$StartingLocation'
			,'$Mission'
			,'$Description'
			,DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
			,'$userID'
			,DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
			,'$userID'
		)
	";

	//print_r($q);
	
	$_SESSION['maintain_edit'] = 'true';
	$query_result = $connection->query($q);
		
	if ($query_result)
	{
		header("Location: $link_base/operations");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>