<?php
 	if (!isset($_SESSION)) {
	  session_start();
	}
	
	if($_GET[method] == "logout"){
		session_destroy();
		echo("<meta http-equiv=\"refresh\" content=\"0;url=index.php\">");
	}

	/* mysql_query("UPDATE USERS SET user_session = 'NULL' WHERE user_uname = '$_SESSION[user_uname]'") or die(mysql_error()); */
	
?>