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

	$OpTemplateID = "";
	$OpTemplateUnitID = "";
	$OpTemplateShipID = "";
	$OpUnitMemberRoleID = "";
	 
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
	 
	$q = "
		INSERT INTO projectx_vvarsc2.OpTemplateShipMembers (
			OpTemplateUnitID
			,OpTemplateShipID
			,MemberID
			,OpUnitMemberRoleID
			,OpTemplateID
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
			,'userID'
			,DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
			,'userID'
		)
	";
	
	//print_r($q);
	
	$_SESSION['maintain_edit'] = 'true';

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/operation/$OpTemplateID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>