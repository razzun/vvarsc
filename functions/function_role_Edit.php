<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$ID = "";
	$Name = "";
	$ShortName = "";
	$IsPrivate = "";
	$Order = "";
	
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
	if (isset($_POST['IsPrivate']))
	{
		$IsPrivate = $_POST['IsPrivate'];
	}
	if (isset($_POST['Order']))
	{
		$Order = $_POST['Order'];
	}
	 
	$q = "UPDATE projectx_vvarsc2.roles set
			role_name = '$Name'
			,role_shortName = '$ShortName'
			,role_orderBy = '$Order'
			,isPrivate = '$IsPrivate'
		WHERE role_id = '$ID$'";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/admin_roles");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>