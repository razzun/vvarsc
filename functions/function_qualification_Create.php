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

	$Name = "";
	$CategoryID = "";
	$Image = "";
	$IsActive = "";
	 
	if(isset($_POST['Name']))
	{
		$Name = mysqli_real_escape_string($connection, $_POST['Name']);
	}
	if (isset($_POST['Category']))
	{
		$CategoryID = $_POST['Category'];
	}
	if (isset($_POST['Image']))
	{
		$Image = mysqli_real_escape_string($connection, $_POST['Image']);
	}
	if (isset($_POST['IsActive']))
	{
		$IsActive = $_POST['IsActive'];
	}
	 
	$q = "INSERT into projectx_vvarsc2.qualifications (
			qualification_name
			,qualification_categoryID
			,qualification_image
			,IsActive
		)
		VALUES (
			'$Name'
			,$CategoryID
			,'$Image'
			,$IsActive
		)
	";
	
	//print_r($q);
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/admin/?page=admin_qual");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>