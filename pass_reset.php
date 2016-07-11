<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']))
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>