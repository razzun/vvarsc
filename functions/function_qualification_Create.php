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
	$Level1Reqs = "";
	$Level2Reqs = "";
	$Level3Reqs = "";
	 
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
	if (isset($_POST['Level1Reqs']))
	{
		$Level1Reqs = mysqli_real_escape_string($connection, $_POST['Level1Reqs']);
	}
	if (isset($_POST['Level2Reqs']))
	{
		$Level2Reqs = mysqli_real_escape_string($connection, $_POST['Level2Reqs']);
	}
	if (isset($_POST['Level3Reqs']))
	{
		$Level3Reqs = mysqli_real_escape_string($connection, $_POST['Level3Reqs']);
	}
	 
	$q = "INSERT into projectx_vvarsc2.qualifications (
			qualification_name
			,qualification_categoryID
			,qualification_image
			,IsActive
			,level1_reqs
			,level2_reqs
			,level3_reqs
		)
		VALUES (
			'$Name'
			,$CategoryID
			,'$Image'
			,$IsActive
			,'$Level1Reqs'
			,'$Level2Reqs'
			,'$Level3Reqs'
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