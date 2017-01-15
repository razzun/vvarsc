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
	$MissionShipID = "";
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
	if(isset($_POST['OpTemplateShipID']))
	{
		$MissionShipID = $_POST['OpTemplateShipID'];
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
			UPDATE projectx_vvarsc2.MissionShipMembers set
				OpUnitMemberRoleID = $OpUnitMemberRoleID
				,MemberID = $MemberID
				,ModifiedOn = DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
				,ModifiedBy = '$userID'
			where RowID = $RowID
				and MissionShipID = '$MissionShipID'
		";
	}
	else
	{
		$q = "
			UPDATE projectx_vvarsc2.MissionShipMembers set
				OpUnitMemberRoleID = $OpUnitMemberRoleID
				,MemberID = null
				,ModifiedOn = DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
				,ModifiedBy = '$userID'
			where RowID = $RowID
				and MissionShipID = '$MissionShipID'
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