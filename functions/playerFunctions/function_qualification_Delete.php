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

	$RowID = "";
	$ID = "";
	$MemID = "";
	$Name = "";
	$Level = "";
	
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
	}	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}	
	if(isset($_POST['MemID']))
	{
		$MemID = $_POST['MemID'];
	}
	if(isset($_POST['Name']))
	{
		$Name = mysqli_real_escape_string($connection, $_POST['Name']);
	}
	if (isset($_POST['Level']))
	{
		$Level = $_POST['Level'];
	}
	 
	$q = "
		DELETE FROM projectx_vvarsc2.member_qualifications
		where RowID = $RowID
			and qualification_id = $ID
			and member_id = $MemID
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