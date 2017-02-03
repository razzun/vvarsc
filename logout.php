<?php 
	session_start();
	session_destroy();
	header('Location: $link_base/login.php');
?>