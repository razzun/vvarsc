<?php 
	print_r($_POST);
	
	require_once('dbconn/dbconn.php');
	
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