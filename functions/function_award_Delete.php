<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$ID = "";
	$Name = "";
	$Image = "";
	$IsActive = "";
	$Level1Reqs = "";
	$OrderBy = "";
	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
	if(isset($_POST['Name']))
	{
		$Name = mysqli_real_escape_string($connection, $_POST['Name']);
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
	if (isset($_POST['OrderBy']))
	{
		$OrderBy = mysqli_real_escape_string($connection, $_POST['OrderBy']);
	}
	
	$q1 = "
		delete from projectx_vvarsc2.member_Awards
		where AwardID = $ID
	";
	 
	$q2 = "
		delete from projectx_vvarsc2.Awards
		where AwardID = $ID
	";
	
	//print_r($q);
	$query1_result = $connection->query($q1);
	$query2_result = $connection->query($q2);
			
	if ($query1_result && $query2_result)
	{
		header("Location: $link_base/admin/?page=admin_awards");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>