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
	 
	$q = "
		UPDATE projectx_vvarsc2.qualifications set
			qualification_name = '$Name'
			,qualification_image = '$Image'
			,qualification_categoryID = $CategoryID
			,IsActive = $IsActive
		where qualification_id = $ID
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