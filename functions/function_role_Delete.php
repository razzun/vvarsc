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
	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
	 
	$q = "DELETE from projectx_vvarsc2.roles
		WHERE role_id = '$ID$'";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_roles");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>