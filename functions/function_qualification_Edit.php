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
	$CategoryID = "";
	$Image = "";
	$IsActive = "";
	$DivisionID = "";
	$Level1Reqs = "";
	$Level2Reqs = "";
	$Level3Reqs = "";
	
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
	if (isset($_POST['DivisionID']))
	{
		$DivisionID = $_POST['DivisionID'];
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
	 
	$q = "
		UPDATE projectx_vvarsc2.qualifications set
			qualification_name = '$Name'
			,qualification_image = '$Image'
			,qualification_categoryID = $CategoryID
			,IsActive = $IsActive
			,division_id = $DivisionID
			,level1_reqs = '$Level1Reqs'
			,level2_reqs = '$Level2Reqs'
			,level3_reqs = '$Level3Reqs'
		where qualification_id = $ID
	";
	
	//print_r($q);
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_qual");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>