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
	
	session_start();

	$RowID = "";
	$UnitID = "";
	$MemberID = "";
	$RoleID = "";
	$UnitLeader = "";
	
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
	}		
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
	if(isset($_POST['UnitLeader']))
	{
		$UnitLeader = $_POST['UnitLeader'];
	}
	
	$q = "DELETE from projectx_vvarsc2.UnitMembers
		WHERE RowID = '$RowID'
			and UnitID = '$UnitID'
			and MemberID = '$MemberID'
		";
		
	$query_result = $connection->query($q);
	
	if ($UnitLeader == 1)
	{
		$q2 = "UPDATE projectx_vvarsc2.Units set
				UnitLeaderID = null
			WHERE UnitID = '$UnitID'
			";
			
		$query2_result = $connection->query($q2);
	}
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_unit&pid=$UnitID");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();

?>