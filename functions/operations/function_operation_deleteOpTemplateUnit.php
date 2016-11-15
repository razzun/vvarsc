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
	 
	if(isset($_POST['OpTemplateID']))
	{
		$OpTemplateID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitID']))
	{
		$OpTemplateUnitID = $_POST['OpTemplateUnitID'];
	}
	
	//Delete UnitShipMembers
	$q1 = "
		DELETE from projectx_vvarsc2.OpTemplateShipMembers
		where OpTemplateUnitID = '$OpTemplateUnitID'
			and OpTemplateID = '$OpTemplateID'
			and RowID > 0
	";
	
	//Delete UnitShips
	$q2 = "
		DELETE from projectx_vvarsc2.OpTemplateShips
		where OpTemplateUnitID = '$OpTemplateUnitID'
			and OpTemplateID = '$OpTemplateID'
			and OpTemplateShipID > 0
	";	
	
	//Delete OpUnit
	$q3 = "
		DELETE from projectx_vvarsc2.OpTemplateUnits
		where OpTemplateUnitID = '$OpTemplateUnitID'
			and OpTemplateID = '$OpTemplateID'
	";		
	
	//print_r($q);
	
	$_SESSION['maintain_edit'] = 'true';

	$query1_result = $connection->query($q1);
	$query2_result = $connection->query($q2);
	$query3_result = $connection->query($q3);
			
	if ($query1_result && $query2_result && $query3_result)
	{
		header("Location: http://sc.vvarmachine.com/operation/$OpTemplateID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>