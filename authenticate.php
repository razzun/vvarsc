<?php 
	require_once('dbconn/dbconn.php');
	
	session_start();

	$username = "";
	$password = "";
	 
	if(isset($_POST['username']))
	{
		$username = $_POST['username'];
	}
	if (isset($_POST['password']))
	{
		$password = $_POST['password'];
	}
	 
	$q = "SELECT 
			m.mem_id
			,m.mem_name
			,m.password
			,m.websiteRole
		FROM projectx_vvarsc2.members m
		WHERE m.mem_name = '$username'
			AND m.password = '$password'";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		$rowCount = mysqli_num_rows($query_result);

		if($rowCount == 0)
		{
			header("Location: http://sc.vvarmachine.com/login.php?err=1");
		}
		else
		{
			$row = $query_result->fetch_assoc();
			
			session_regenerate_id();
			$_SESSION['sess_user_id'] = $row['mem_id'];
			$_SESSION['sess_username'] = $row['mem_name'];
			$_SESSION['sess_userrole'] = $row['websiteRole'];

			session_write_close();
			
			//print_r($_SESSION);

			if($_SESSION['sess_userrole'] == "admin")
			{
				header("Location: http://sc.vvarmachine.com/admin/index.php");
			}
			else{
				header("Location: http://sc.vvarmachine.com/player/".$_SESSION['sess_user_id']);
			}		
		}
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/login.php?err=3");
	}
	
	$connection->close();
?>