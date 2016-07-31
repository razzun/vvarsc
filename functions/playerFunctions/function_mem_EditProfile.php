<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']))
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<?php
	//print_r($_POST);
	
	require_once('../../dbconn/dbconn.php');
	
	session_start();

	$SessionMemID = $_SESSION['sess_user_id'];
	$ID = "";
	$Name = "";
	$Callsign = "";
	 
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
	 
	$q = "UPDATE projectx_vvarsc2.members set
			mem_name = '$Name'
			,mem_callsign = '$Callsign'
		where mem_id = '$ID'
	";

	if($ID == $SessionMemID)
	{
		$query_result = $connection->query($q);
		if ($query_result)
		{
			header("Location: http://sc.vvarmachine.com/?page=player&pid=$ID");
		}
		else
		{
			header("Location: http://sc.vvarmachine.com/error_generic");
		}		
	}
	else
	{
		//Add Meaningful error if the logged-in user tries to change another player's profile.
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
			

	
	$connection->close();
?>