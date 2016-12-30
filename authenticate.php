<?php 
	//print_r($_POST);
	require_once('dbconn/dbconn.php');
	
	session_start();

	$username = "";
	$password = "";
	 
	if (isset($_POST['username']))
	{
		$username = $_POST['username'];
	}
	if (isset($_POST['p']))
	{
		$password = $_POST['p'];
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
			AND m.password = '$password'
			AND m.mem_sc = '1'
			AND m.InfoSecLevelID > 1
	";

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
			$_SESSION['sess_infoseclevel'] = $row['InfoSecLevelID'];

			session_write_close();
			
			//print_r($_SESSION);
			if(isset($reqURL))
			{
				header("Location: http://sc.vvarmachine.com".$reqURL);
			}
			elseif($_SESSION['sess_userrole'] == "admin")
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