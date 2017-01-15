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
	$ParentUnit = "";
	$Name = "";
	$ShortName = "";
	$FullName = "";
	$Division = "";
	$IsActive = "";
	$Level = "";
	$Depth = "";
	
	if(isset($_POST['ParentUnit']))
	{
		$ParentUnit = $_POST['ParentUnit'];
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
	
	$pre_query = "
		select
			u.UnitDepth
		from projectx_vvarsc2.Units u
		where u.UnitID = '$ParentUnit'
	";
	
	$pre_query_result = $connection->query($pre_query);
	
	while(($row = $pre_query_result->fetch_assoc()) != false)
	{
		$Depth = $row['UnitDepth'];
	}
	$Depth += 1;
	
	$q = "INSERT into projectx_vvarsc2.Units (
				ParentUnitID
				,UnitName
				,UnitShortName
				,UnitCallsign
				,UnitFullName
				,DivisionID
				,IsActive
				,MaxUnitSize
				,UnitDepth
				,UnitLevel
				,UnitLeaderID
				,CreatedOn
				,UnitDescription
				,UnitSlogan
				,UnitBackgroundImage
			)
			VALUES(
				'$ParentUnit'
				,'$Name'
				,'$ShortName'
				,null
				,'$FullName'
				,'$Division'
				,$IsActive
				,null
				,'$Depth'
				,'$Level'
				,null
				,DATE_ADD(CURDATE(),INTERVAL 930 YEAR)
				,null
				,null
				,null
			)";
		
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: ?page=admin_units");
	}
	else
	{
		header("Location: ../error_generic");
	}
	
	$connection->close();

?>