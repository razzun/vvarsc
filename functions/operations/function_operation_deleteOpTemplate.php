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

	$MissionID = "";
	 
	if(isset($_POST['MissionID']))
	{
		$MissionID = $_POST['MissionID'];
	}
	 
	$q = "
		delete from projectx_vvarsc2.OpTemplates where OpTemplateID = $MissionID;
		delete from projectx_vvarsc2.OpTemplateUnits where OpTemplateUnitID > 0 and OpTemplateID = $MissionID;
		delete from projectx_vvarsc2.OpTemplateUnitMembers where RowID > 0 and OpTemplateID = $MissionID;
		delete from projectx_vvarsc2.OpTemplateShips where OpTemplateShipID > 0 and OpTemplateID = $MissionID;
		delete from projectx_vvarsc2.OpTemplateShipMembers where RowID > 0 and OpTemplateID = $MissionID;
	";

	$_SESSION['maintain_edit'] = 'true';
	$query_result = $connection->multi_query($q);
			
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