<?php 
	
	require_once('dbconn/dbconn.php');
	
	session_start();

	$ID = "";
	$Name = "";
	$ShortName = "";
	$ImageURL = "";
	 
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
	if(isset($_POST['Name']))
	{
		$Name = $_POST['Name'];
	}
	if (isset($_POST['ShortName']))
	{
		$ShortName = $_POST['ShortName'];
	}
	if (isset($_POST['ImageURL']))
	{
		$ImageURL = $_POST['ImageURL'];
	}
	 
	$q = "UPDATE projectx_vvarsc2.manufacturers set
			manu_name = '$Name'
			,manu_shortName = '$ShortName'
			,manu_smallImage = '$ImageURL'
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