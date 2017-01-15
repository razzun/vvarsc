<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: ../login.php?err=4');
    }
?>

<?php
	print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	
	session_start();

	$ID = "";
	 
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
	
	$q = "DELETE from projectx_vvarsc2.members where mem_id = '$ID'";
	$q2 = "DELETE from projectx_vvarsc2.ships_has_members where RowID > 1 and members_mem_id = '$ID'";
	$q3 = "DELETE from projectx_vvarsc2.UnitMembers where RowID > 1 and MemberID = '$ID'";

	$query2_result = $connection->query($q2);
	$query3_result = $connection->query($q3);
	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: ?page=admin_mem");
	}
	else
	{
		header("Location: ../error_generic");
	}
	
	$connection->close();
?>