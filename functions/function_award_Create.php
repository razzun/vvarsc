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

	$Name = "";
	$Image = "";
	$IsActive = "";
	$Level1Reqs = "";
	$OrderBy = "";
	
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
	 
	$q = "INSERT into projectx_vvarsc2.Awards (
			AwardName
			,AwardImage
			,IsActive
			,AwardRequirements
			,AwardOrderBy
		)
		VALUES (
			'$Name'
			,'$Image'
			,$IsActive
			,'$Level1Reqs'
			,$OrderBy
		)
	";
	
	//print_r($q);
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_awards");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>