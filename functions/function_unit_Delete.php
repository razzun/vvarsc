<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$ID = "";
	$ParentUnit = "";
	$Name = "";
	$ShortName = "";
	$FullName = "";
	$Division = "";
	$IsActive = "";
	$Level = "";
	$Depth = "";
	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
	
	$q = "DELETE from projectx_vvarsc2.Units
		WHERE UnitID = '$ID'";
		
	$query_result = $connection->query($q);
	
	$q2 = "DELETE from projectx_vvarsc2.UnitMembers
		WHERE UnitID = '$ID'";
		
	$query2_result = $connection->query($q2);
			
	if ($query_result && $query2_result)
	{
		header("Location: $link_base/admin/?page=admin_units");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>