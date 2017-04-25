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

	$OpTemplateID = "";
	$OpTemplateUnitID = "";
	$OpTemplateShipID = "";
	$OpUnitMemberRoleID = "";
	$MemberID = null;
	 
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
	if(isset($_POST['OpUnitMemberRoleID']))
	{
		$OpUnitMemberRoleID = $_POST['OpUnitMemberRoleID'];
	}
	if(isset($_POST['MemberID']))
	{
		$MemberID = $_POST['MemberID'];
	}
	
	if ($MemberID == 0)
		$MemberID = null;
	
	if ($MemberID != null) { 
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
				'$OpTemplateUnitID'
				,'$OpTemplateShipID'
				,'$MemberID'
				,'$OpUnitMemberRoleID'
				,'$OpTemplateID'
				,DATE_ADD(UTC_TIMESTAMP(), INTERVAL 930 YEAR)
				,'userID'
				,DATE_ADD(UTC_TIMESTAMP(), INTERVAL 930 YEAR)
				,'userID'
			)
		";
	}
	else {
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
				'$OpTemplateUnitID'
				,'$OpTemplateShipID'
				,null
				,'$OpUnitMemberRoleID'
				,'$OpTemplateID'
				,DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
				,'$userID'
				,DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
				,'$userID'
			)
		";		
	}
	
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