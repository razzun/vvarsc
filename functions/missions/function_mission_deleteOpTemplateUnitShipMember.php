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
	
	print_r($_POST);
	

	$RowID = "";
	$OpTemplateID = "";
	$OpTemplateUnitID = "";
	$OpTemplateShipID = "";
	$MemberID = null;
	 
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
	}
	if(isset($_POST['OpTemplateID']))
	{
		$OpTemplateID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitID']))
	{
		$OpTemplateUnitID = $_POST['OpTemplateUnitID'];
	}
	if(isset($_POST['OpTemplateShipID']))
	{
		$OpTemplateShipID = $_POST['OpTemplateShipID'];
	}
	if(isset($_POST['MemberID']))
	{
		$MemberID = $_POST['MemberID'];
	}
	
	if ($MemberID == 0)
		$MemberID = null;
	 
	$q = "
		DELETE FROM projectx_vvarsc2.MissionShipMembers
		where RowID = $RowID
			and MissionShipID = '$OpTemplateShipID'
	";
	
	//print_r($q);
	
	$_SESSION['maintain_edit'] = 'true';

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/mission/$OpTemplateID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>