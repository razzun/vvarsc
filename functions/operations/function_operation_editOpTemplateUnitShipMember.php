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

	$RowID = "";
	$OpTemplateID = "";
	$OpTemplateUnitID = "";
	$OpTemplateShipID = "";
	$OpUnitMemberRoleID = "";
	 
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
	if(isset($_POST['OpUnitMemberRoleID']))
	{
		$OpUnitMemberRoleID = $_POST['OpUnitMemberRoleID'];
	}
	 
	$q = "
		UPDATE projectx_vvarsc2.OpTemplateShipMembers set
			OpUnitMemberRoleID = '$OpUnitMemberRoleID'
			,ModifiedOn = DATE_ADD(CURDATE(), INTERVAL 930 YEAR)
			,ModifiedBy = '$userID'
		where RowID = $RowID
			and OpTemplateShipID = '$OpTemplateShipID'
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