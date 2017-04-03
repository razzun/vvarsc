<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']))
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	//print_r($_POST);
	
	require_once('../../dbconn/dbconn.php');
	require_once('../../functions/security/function_hash_password.php');
	require_once('../../functions/security/function_verify_password.php');

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
	if (isset($_POST['CurrentPassword']))
	{
		$CurrentPassword = $_POST['CurrentPassword'];
	}
	if (isset($_POST['NewPassword']))
	{
		$NewPassword = $_POST['NewPassword'];
	}
	if (isset($_POST['Biography']))
	{
		$MemberBio =  mysqli_real_escape_string($connection, $_POST['Biography']);
	}
	
	$NewHashedPassword = hash_password($NewPassword);
	
	if($ID == $SessionMemID)
	{
		if($CurrentPassword != "" && $CurrentPassword != null)
		{
			//If New Password is Passed-In, check to make sure it's legit
			$initialQuery = "
				SELECT 
					m.password
				FROM projectx_vvarsc2.members m
				WHERE m.mem_id = $ID
			";
			$initialQuery_result = $connection->query($initialQuery);
			$row2 = $initialQuery_result->fetch_assoc();
			
			if(!verify_password($row2['password'],$CurrentPassword))
			{
				//print_r('verify password failed');
				header("Location: ".$link_base."/login.php?err=1");
			}
			else
			{
				if($NewPassword != "" && $NewPassword != null)
				{
					$q = "UPDATE projectx_vvarsc2.members set
							mem_name = '$Name'
							,mem_callsign = '$Callsign'
							,password = '$NewHashedPassword'
							,member_bio = '$MemberBio'
						where mem_id = '$ID'
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
					";					
				}
			
				$query_result = $connection->query($q);
				if ($query_result)
				{
					header("Location: $link_base/?page=player&pid=$ID");
				}
				else
				{
					//print_r('update statement failed');
					header("Location: $link_base/error_generic");
				}
			}
		}
		else
		{
			//Add Meaningful Error if user doesn't enter their current password
			//print_r('no current password supplied');
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