<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || ($role!="officer" && $role!="admin"))
	{
      header("Location: $link_base/login.php?err=4");
    }
?>