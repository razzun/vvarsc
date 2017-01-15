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
	$ShortName = "";
	$FullName = "";
	$Callsign = "";
	$Division = "";
	$IsActive = "";
	$Level = "";
	$Description = "";
	$BackgroundImage = "";
	$EmblemImage = "";
	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}	
	if(isset($_POST['Name']))
	{
		$Name = mysqli_real_escape_string($connection, $_POST['Name']);
	}
	if (isset($_POST['ShortName']))
	{
		$ShortName = mysqli_real_escape_string($connection, $_POST['ShortName']);
	}
	if (isset($_POST['FullName']))
	{
		$FullName = mysqli_real_escape_string($connection, $_POST['FullName']);
	}
	if (isset($_POST['FullName']))
	{
		$Callsign = mysqli_real_escape_string($connection, $_POST['Callsign']);
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
		$Level = mysqli_real_escape_string($connection, $_POST['Level']);
	}
	if(isset($_POST['Description']))
	{
		$Description = mysqli_real_escape_string($connection, $_POST['Description']);
	}
	if(isset($_POST['BackgroundImage']))
	{
		$BackgroundImage = mysqli_real_escape_string($connection, $_POST['BackgroundImage']);
	}
	if(isset($_POST['EmblemImage']))
	{
		$EmblemImage = mysqli_real_escape_string($connection, $_POST['EmblemImage']);
	}
	
	$q = "UPDATE projectx_vvarsc2.Units set
			UnitName = '$Name'
			,UnitShortName = '$ShortName'
			,UnitFullName = '$FullName'
			,UnitCallsign = '$Callsign'
			,DivisionID = '$Division'
			,IsActive = $IsActive
			,UnitLevel = '$Level'
			,UnitDescription = '$Description'
			,UnitBackgroundImage = '$BackgroundImage'
			,UnitEmblemImage = '$EmblemImage'
		WHERE UnitID = '$ID'";
	
	print_r($q);
	
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_unit&pid=$ID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
	
?>