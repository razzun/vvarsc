<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=4');
    }
?>

<?php
	
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$UnitID = "";
	$MemberID = "";
	$RoleID = "";
	
	if(isset($_POST['UnitID']))
	{
		$UnitID = $_POST['UnitID'];
	}	
	if(isset($_POST['MemberID']))
	{
		$MemberID = $_POST['MemberID'];
	}	
	if(isset($_POST['RoleID']))
	{
		$RoleID = $_POST['RoleID'];
	}
	
	$q = "INSERT into projectx_vvarsc2.UnitMembers (
			UnitID
			,MemberID
			,MemberRoleID
			,CreatedOn
		)
		VALUES (
			'$UnitID'
			,'$MemberID'
			,'$RoleID'
			,DATE_ADD(CURDATE(),INTERVAL 930 YEAR)
		)
		";
		
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/admin_unit/$UnitID");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();

?>