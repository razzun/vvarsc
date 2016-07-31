<?php 
    session_start();
	$role = "";
	if(isset($_SESSION['sess_userrole']))
	{
		$role = $_SESSION['sess_userrole'];
	}
	
    if(!isset($_SESSION['sess_username']))
	{
      header("Location: http://sc.vvarmachine.com/login.php?err=2");
    }
?>