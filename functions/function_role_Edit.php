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
	$DisplayName = "";
	$IsPrivate = "";
	$Order = "";
	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}	
	if(isset($_POST['Name']))
	{
		$Name = mysqli_real_escape_string($connection, $_POST['Name']);
	}
	if (isset($_POST['ShortName']))
	{
		$ShortName = mysqli_real_escape_string($connection, $_POST['ShortName']);
	}
	if (isset($_POST['DisplayName']))
	{
		$DisplayName = mysqli_real_escape_string($connection, $_POST['DisplayName']);
	}
	if (isset($_POST['IsPrivate']))
	{
		$IsPrivate = $_POST['IsPrivate'];
	}
	if (isset($_POST['Order']))
	{
		$Order = mysqli_real_escape_string($connection, $_POST['Order']);
	}
	 
	$q = "UPDATE projectx_vvarsc2.roles set
			role_name = '$Name'
			,role_shortName = '$ShortName'
			,role_displayName = '$DisplayName'
			,role_orderBy = '$Order'
			,isPrivate = '$IsPrivate'
		WHERE role_id = '$ID$'";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/admin/?page=admin_roles");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>