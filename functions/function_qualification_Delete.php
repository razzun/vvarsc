<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: ../login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$ID = "";
	$Name = "";
	$CategoryID = "";
	$Image = "";
	$IsActive = "";
	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
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
	
	$q1 = "
		delete from projectx_vvarsc2.member_qualifications
		where qualification_id = $ID
	";
	 
	$q2 = "
		delete from projectx_vvarsc2.qualifications
		where qualification_id = $ID
	";
	
	//print_r($q);
	$query1_result = $connection->query($q1);
	$query2_result = $connection->query($q2);
			
	if ($query1_result && $query2_result)
	{
		header("Location: ?page=admin_qual");
	}
	else
	{
		header("Location: ../error_generic");
	}
	
	$connection->close();
?>