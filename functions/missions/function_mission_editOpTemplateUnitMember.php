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

	$RowID = "";
	$MissionID = "";
	$MissionUnitID = "";
	$OpUnitMemberRoleID = "";
	$MemberID = null;
	 
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
	}
	if(isset($_POST['OpTemplateID']))
	{
		$MissionID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitID']))
	{
		$MissionUnitID = $_POST['OpTemplateUnitID'];
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
	 
	if ($MemberID != null)
	{
		$q = "
			UPDATE projectx_vvarsc2.MissionUnitMembers set
				MemberID = $MemberID
				,OpUnitMemberRoleID = $OpUnitMemberRoleID
				,ModifiedOn = DATE_ADD(UTC_TIMESTAMP(), INTERVAL 930 YEAR)
				,ModifiedBy = '$userID'
			where RowID = $RowID
				and MissionUnitID = '$MissionUnitID'
		";
	}
	else
	{
		$q = "
			UPDATE projectx_vvarsc2.MissionUnitMembers set
				MemberID = null
				,OpUnitMemberRoleID = $OpUnitMemberRoleID
				,ModifiedOn = DATE_ADD(UTC_TIMESTAMP(), INTERVAL 930 YEAR)
				,ModifiedBy = '$userID'
			where RowID = $RowID
				and MissionUnitID = '$MissionUnitID'
		";		
	}
	
	//print_r($q);
	$_SESSION['maintain_edit'] = 'true';

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/mission/$MissionID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>