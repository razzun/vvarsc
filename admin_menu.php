<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<admin_menu>
    <ul>
        <li><a href="/admin_manu">Manufacturer Management</a></li>
        <li><a href="/admin_mem">Member Management</a></li>
        <li><a href="#">Ship Management</a></li>
        <li><a href="#">Ranks Management</a></li>
    </ul>
</admin_menu>