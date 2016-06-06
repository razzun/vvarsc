<?
		session_start();
		
		srand(date("s"));
		$possible="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$str="";
		while(strlen($str)<12) {
			$str.=substr($possible,(rand()%(strlen($possible))),1);
		}
		$sessionID = md5($str);
		
		if(!isset($_SESSION[id])){
			if($_POST[submit]){
				$result = mysql_query("SELECT user_id, user_uname, user_fname, user_mname, user_lname, user_email, user_password, user_level, user_active, DATE_FORMAT(user_last_login,'%m/%d/%Y at %l:%i %p') user_past_login FROM USERS WHERE user_uname = '$_POST[user_uname_login]'");
				$row = mysql_fetch_array($result);
				$count = mysql_num_rows($result);
				if($count == 0 || $row[user_password] != md5($_POST[user_password_login])){
					$password = md5($_POST[user_password_login]);
					$error = "<div class=redText>Invalid Username and/or Password.</div";
				}
				elseif($count == 1 && ($row[user_active] == 0)){
					$password = md5($_POST[user_password_login]);
					$error = "<div class=redText>This account is not yet active.</div>";
				}
				elseif($count == 1 && ($row[user_active] == 2)){
					$password = md5($_POST[user_password_login]);
					$error = "<div class=redText>Your accout had been suspended.  Please contact the <a href=\"mailto:webmaster@vvarmachine.com?Subject=Suspended Account\">webmaster</a> for details.</div>";
				}				
				elseif($row[user_uname] == $_POST[user_uname_login] && $row[user_password] == md5($_POST[user_password_login])){
					$_SESSION[user_id] = $row[user_id];
					$_SESSION[user_uname] = $row[user_uname];
					$_SESSION[user_fname] = $row[user_fname];
					$_SESSION[user_mname] = $row[user_mname];
					$_SESSION[user_lname] = $row[user_lname];
					$_SESSION[user_level] = $row[user_level];
					$_SESSION[user_email] = $row[user_email];
					$_SESSION[user_past_login] = $row[user_past_login];
					$_SESSION[id] = $sessionID;
					$now = date("Y-m-d H:i:s");
			
					mysql_query("UPDATE USERS SET user_session = '$_SESSION[id]', user_last_login = '$now' WHERE user_uname = '$_SESSION[user_uname]'");
					
					echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
				}
			}
			else {
				if($_GET[scheck] == '0'){
					$error = "<div class=redText>You must be logged in to view that.</div>";
				}
				if($_GET[scheck] == '1'){
					$error = "<div class=redText>Bad session key. This is usually due to logging in again from another machine.</div>";
				}
			}
		}
?>