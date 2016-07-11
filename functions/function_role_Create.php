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

	$Name = "";
	$ShortName = "";
	$IsPrivate = "";
	$Order = "";
	 
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
	 
	$q = "INSERT into projectx_vvarsc2.roles (role_name, role_shortName, role_orderby, isPrivate)
			VALUES('$Name','$ShortName','$Order','$IsPrivate')";

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