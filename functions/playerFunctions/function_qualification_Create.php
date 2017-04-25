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
	
	require_once('../../dbconn/dbconn.php');
	
	session_start();

	$MemID = "";
	$QualificationID = "";
	$Level = "";
	
	if(isset($_POST['MemID']))
	{
		$MemID = $_POST['MemID'];
	}
	if(isset($_POST['QualificationID']))
	{
		$QualificationID = $_POST['QualificationID'];
	}
	if (isset($_POST['Level']))
	{
		$Level = $_POST['Level'];
	}
	 
	$q = "
		INSERT INTO projectx_vvarsc2.member_qualifications (
			member_id
			,qualification_id
			,qualification_level_id
			,last_updated
		)
		VALUES (
			$MemID
			,SUBSTRING('$QualificationID',(LOCATE('_','$QualificationID') + 1),LENGTH('$QualificationID'))
			,$Level
			,DATE_ADD(UTC_TIMESTAMP(), INTERVAL 930 YEAR)
		)
	";
	
	//print_r($q);
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/player/$MemID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>