<?php 
	session_start();
	session_destroy();
	header('Location: http://sc.vvarmachine.com/login.php');
?>