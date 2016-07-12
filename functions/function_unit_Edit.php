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
	$ShortName = "";
	$FullName = "";
	$Division = "";
	$IsActive = "";
	$Level = "";
	$Description = "";
	$BackgroundImage = "";
	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}	
	if(isset($_POST['Name']))
	{
		$Name = $_POST['Name'];
	}
	if (isset($_POST['ShortName']))
	{
		$ShortName = $_POST['ShortName'];
	}
	if (isset($_POST['FullName']))
	{
		$FullName = $_POST['FullName'];
	}
	if (isset($_POST['Division']))
	{
		$Division = $_POST['Division'];
	}
	if (isset($_POST['IsActive']))
	{
		$IsActive = $_POST['IsActive'];
	}
	if (isset($_POST['Level']))
	{
		$Level = $_POST['Level'];
	}
	if(isset($_POST['Description']))
	{
		$Description = mysqli_real_escape_string($connection, $_POST['Description']);
	}
	if(isset($_POST['BackgroundImage']))
	{
		$BackgroundImage = $_POST['BackgroundImage'];
	}
	
	$q = "UPDATE projectx_vvarsc2.Units set
			UnitName = '$Name'
			,UnitShortName = '$ShortName'
			,UnitFullName = '$FullName'
			,DivisionID = '$Division'
			,IsActive = $IsActive
			,UnitLevel = '$Level'
			,UnitDescription = '$Description'
			,UnitBackgroundImage = '$BackgroundImage'
		WHERE UnitID = '$ID'";
	
	print_r($q);
	
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/admin_unit/$ID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>