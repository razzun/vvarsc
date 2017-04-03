<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	require_once('../functions/security/function_csprng.php');
	require_once('../functions/security/function_hash_password.php');
	
	session_start();

	$ID = "";
	$NewPassword = csprng(16);
	$NewHashedPassword = hash_password($NewPassword);
	
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
	if(isset($_POST['memName']))
	{
		$memName = $_POST['memName'];
	}
	if(isset($_POST['memCallsign']))
	{
		$memCallsign = $_POST['memCallsign'];
	}
	if(isset($_POST['memEmail']))
	{
		$memEmail = $_POST['memEmail'];
	}
	
/* 	$possible="0123456789abcdefghijklmnopqrstuvwxyz~!@#$%^&*()_+=-\][{}|;':,./?><ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$str="";
	while(strlen($str) < 10) {
		$str.=substr($possible,(rand()%(strlen($possible))),1);
	}
	$password = $str;	        
	$password2 = md5($password); */
	 
	$q = "UPDATE projectx_vvarsc2.members set
			password = '$NewHashedPassword'
		where mem_id = '$ID'
	";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
/* 		$to = $memEmail;
		$subject = 'VVarMachine Star Citizen Password Reset';
		$headers = 'From: webmaster@vvarmachine.com' . "\r\n" .
		    'Reply-To: webmaster@vvarmachine.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		$message = 'Per your request, an administrator has reset your password. Please go to $link_base, login and change your password.  Your new password is: ' . $memPassword;
		 */
		header("Location: $link_base/admin/?page=admin_mem_success&pw=$NewPassword");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>