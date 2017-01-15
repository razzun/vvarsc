<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']))
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../../dbconn/dbconn.php');
	
	session_start();

	$SessionMemID = $_SESSION['sess_user_id'];
	$ID = "";
	$Name = "";
	$Callsign = "";
	$CurrentPassword = "";
	$NewPassword = "";
	$MemberBio = "";
	 
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
	if (isset($_POST['cp']))
	{
		$CurrentPassword = $_POST['cp'];
	}
	if (isset($_POST['np']))
	{
		$NewPassword = $_POST['np'];
	}
	if (isset($_POST['Biography']))
	{
		$MemberBio =  mysqli_real_escape_string($connection, $_POST['Biography']);
	}
	
	if($ID == $SessionMemID)
	{
		if($CurrentPassword != "" && $CurrentPassword != null)
		{
			//If New Password is Passed-In, Update it
			if($NewPassword != "" && $NewPassword != null)
			{
				$q = "UPDATE projectx_vvarsc2.members set
						mem_name = '$Name'
						,mem_callsign = '$Callsign'
						,password = '$NewPassword'
						,member_bio = '$MemberBio'
					where mem_id = '$ID'
						and password = '$CurrentPassword'
				";				
			}
			//If not, only update member profile fields
			else
			{
				$q = "UPDATE projectx_vvarsc2.members set
						mem_name = '$Name'
						,mem_callsign = '$Callsign'
						,member_bio = '$MemberBio'
					where mem_id = '$ID'
						and password = '$CurrentPassword'
				";					
			}
			
			$query_result = $connection->query($q);
			if ($query_result)
			{
				header("Location: $link_base/?page=player&pid=$ID");
			}
			else
			{
				header("Location: $link_base/error_generic");
			}
		}
		else
		{
			//Add Meaningful Error if user doesn't enter their current password
			header("Location: $link_base/error_generic");
		}
	}
	else
	{
		//Add Meaningful error if the logged-in user tries to change another player's profile.
		header("Location: $link_base/error_generic");
	}

	$connection->close();
?>