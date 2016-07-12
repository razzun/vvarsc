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
	$Callsign = "";
	$Rank = "";
	$Division = "";
	 
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
	if(isset($_POST['Name']))
	{
		$Name = mysqli_real_escape_string($connection, $_POST['Name']);
	}
	if (isset($_POST['Callsign']))
	{
		$Callsign = mysqli_real_escape_string($connection, $_POST['Callsign']);
	}
	if (isset($_POST['Rank']))
	{
		$Rank = $_POST['Rank'];
	}
	if (isset($_POST['Division']))
	{
		$Division = $_POST['Division'];
	}
	 
	$q = "UPDATE projectx_vvarsc2.members set
			mem_name = '$Name'
			,mem_callsign = '$Callsign'
			,ranks_rank_id = '$Rank'
			,divisions_div_id = '$Division'
		where mem_id = '$ID'
	";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/admin_mem");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>