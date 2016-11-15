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
	$MissionUnitID = "";
	$MissionShipID = "";
	$OpUnitMemberRoleID = "";
	$MemberID = null;
	if(isset($_POST['MemberID']))
	{
		$MemberID = $_POST['MemberID'];
	}
	 
	if(isset($_POST['OpTemplateID']))
	{
		$MissionID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitID']))
	{
		$MissionUnitID = $_POST['OpTemplateUnitID'];
	}
	if(isset($_POST['OpTemplateShipID']))
	{
		$MissionShipID = $_POST['OpTemplateShipID'];
	}
	if(isset($_POST['OpUnitMemberRoleID']))
	{
		$OpUnitMemberRoleID = $_POST['OpUnitMemberRoleID'];
	}
	 
	if ($MemberID != null)
	{
		$q = "
			INSERT INTO projectx_vvarsc2.MissionShipMembers (
				MissionUnitID
				,MissionShipID
				,MemberID
				,OpUnitMemberRoleID
				,MissionID
				,CreatedOn
				,CreatedBy
				,ModifiedOn
				,ModifiedBy
			)
			VALUES
			(
				'$MissionUnitID'
				,'$MissionShipID'
				,$MemberID
				,'$OpUnitMemberRoleID'
				,'$MissionID'
				,DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
				,'userID'
				,DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
				,'userID'
			)
		";
	}
	else
	{
		$q = "
			INSERT INTO projectx_vvarsc2.MissionShipMembers (
				MissionUnitID
				,MissionShipID
				,OpUnitMemberRoleID
				,MissionID
				,CreatedOn
				,CreatedBy
				,ModifiedOn
				,ModifiedBy
			)
			VALUES
			(
				'$MissionUnitID'
				,'$MissionShipID'
				,'$OpUnitMemberRoleID'
				,'$MissionID'
				,DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
				,'userID'
				,DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
				,'userID'
			)
		";
	}
	
	//print_r($q);
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