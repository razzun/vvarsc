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
	$ShipID = "";
	$OpTemplateID = "";
	$OpTemplateUnitID = "";
	 
	if(isset($_POST['ShipID']))
	{
		$ShipID = $_POST['ShipID'];
	}
	if(isset($_POST['OpTemplateID']))
	{
		$OpTemplateID = $_POST['OpTemplateID'];
	}
	if(isset($_POST['OpTemplateUnitID']))
	{
		$OpTemplateUnitID = $_POST['OpTemplateUnitID'];
	}
	 
	$q = "
		set @MaxOrderBy = (
			select
				IFNULL(MAX(OpTemplateShipOrderBy),0) + 1
			from projectx_vvarsc2.OpTemplateShips
			where OpTemplateUnitID = '$OpTemplateUnitID'
				and OpTemplateID = '$OpTemplateID'
		);		
	
		INSERT INTO projectx_vvarsc2.OpTemplateShips (
			OpTemplateUnitID
			,ShipID
			,Callsign
			,OpTemplateShipOrderBy
			,OpTemplateID
		)
		VALUES
		(
			$OpTemplateUnitID
			,$ShipID
			,null
			,@MaxOrderBy
			,'$OpTemplateID'
		)
	";
	
	print_r($q);
	
	$_SESSION['maintain_edit'] = 'true';
	$query_result = $connection->multi_query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/operation/$OpTemplateID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>