<?php 
    session_start();
	$role = "";
	if(isset($_SESSION['sess_userrole']))
	{
		$role = $_SESSION['sess_userrole'];
	}
	
	$infoSecLevel = $_SESSION['sess_infoseclevel'];
	
    if(!isset($_SESSION['sess_username']))
	{
		$reqURL = $_SERVER['REQUEST_URI'];
		header("Location: http://sc.vvarmachine.com/login.php?err=2&rURL=$reqURL");
    }
?>