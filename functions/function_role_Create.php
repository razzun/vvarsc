<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: ../login.php?err=4');
    }
?>

<?php
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$Name = "";
	$ShortName = "";
	$DisplayName = "";
	$IsPrivate = "";
	$Order = "";
	 
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
	 
	$q = "
		INSERT into projectx_vvarsc2.roles (
			role_name
			,role_shortName
			,role_displayName
			,role_orderby
			,isPrivate
		) VALUES (
			'$Name'
			,'$ShortName'
			,'$DisplayName'
			,'$Order'
			,'$IsPrivate'
		)
	";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: ?page=admin_roles");
	}
	else
	{
		header("Location: ../error_generic");
	}
	
	$connection->close();
?>