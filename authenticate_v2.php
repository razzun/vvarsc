<?php 
	//print_r($_POST);
	require_once('dbconn/dbconn.php');
	require_once('functions/security/function_verify_password.php');
	
	session_start();

	$username = "";
	$password = "";
		 
	if (isset($_POST['username']))
	{
		$username = $_POST['username'];
	}
	if (isset($_POST['password']))
	{
		$password = $_POST['password'];
	}
	if (isset($_POST['url']))
	{
		$reqURL = $_POST['url'];
	}
	 
	$q = "SELECT 
			m.mem_id
			,m.mem_name
			,m.password
			,m.mem_sc
			,m.websiteRole
			,m.InfoSecLevelID
		FROM projectx_vvarsc2.members m
		WHERE m.mem_name = '$username'
			AND m.mem_sc = '1'
	";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		$rowCount = mysqli_num_rows($query_result);

		if($rowCount == 0)
		{
			header("Location: ".$link_base."/login.php?err=1");
		}
		else
		{
			$row = $query_result->fetch_assoc();
			
			print_r($row['password']);
			
			if(!verify_password($row['password'],$password))
			{
				//bad password supplied - fail login process
				header("Location: ".$link_base."/login.php?err=1");
			}
			else
			{
				session_regenerate_id();
				$_SESSION['sess_user_id'] = $row['mem_id'];
				$_SESSION['sess_username'] = $row['mem_name'];
				$_SESSION['sess_userrole'] = $row['websiteRole'];
				$_SESSION['sess_infoseclevel'] = $row['InfoSecLevelID'];

				session_write_close();
				
				//print_r($_SESSION);
				if(isset($_POST['url']))
				{
					header("Location: ".$link_base.$reqURL);
				}
				elseif($_SESSION['sess_userrole'] == "admin")
				{
					header("Location: ".$link_base."/admin/index.php");
				}
				else{
					header("Location: ".$link_base."/player/".$_SESSION['sess_user_id']);
				}
			}		
		}
	}
	else
	{
		header("Location: ".$link_base."/login.php?err=3");
	}
	
	$connection->close();
?>