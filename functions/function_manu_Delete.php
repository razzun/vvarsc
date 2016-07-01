<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<?php
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$ID = "";
	 
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
	 
	$q = "DELETE from projectx_vvarsc2.manufacturers
			where manu_id = '$ID'";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/admin_manu");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>