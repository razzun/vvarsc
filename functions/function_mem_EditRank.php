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

	$ID = "";
	$Rank = "";
	 
	if(isset($_POST['ID']))
	{
		$ID = $_POST['ID'];
	}
	if (isset($_POST['Rank']))
	{
		$Rank = $_POST['Rank'];
	}
	 
	$q = "UPDATE projectx_vvarsc2.members set
			ranks_rank_id = '$Rank'
			,RankModifiedOn = DATE_ADD(UTC_TIMESTAMP(), INTERVAL 930 YEAR)
		where mem_id = '$ID'
	";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_mem");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}
	
	$connection->close();
?>