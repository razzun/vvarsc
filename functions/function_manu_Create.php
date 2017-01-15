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
	$ShortName = "";
	$ImageURL = "";
	 
	if(isset($_POST['Name']))
	{
		$Name = mysqli_real_escape_string($connection, $_POST['Name']);
	}
	if (isset($_POST['ShortName']))
	{
		$ShortName = mysqli_real_escape_string($connection, $_POST['ShortName']);
	}
	if (isset($_POST['ImageURL']))
	{
		$ImageURL = mysqli_real_escape_string($connection, $_POST['ImageURL']);
	}
	 
	$q = "INSERT into projectx_vvarsc2.manufacturers (manu_name, manu_shortName, manu_smallImage)
			VALUES('$Name','$ShortName','$ImageURL')";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_manu");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>