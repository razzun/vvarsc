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

	$Name = "";
	$Callsign = "";
	$Rank = "";
	$Division = "";
	 
	if(isset($_POST['Name']))
	{
		$Name = mysqli_real_escape_string($connection, $_POST['Name']);
	}
	if (isset($_POST['Callsign']))
	{
		$Callsign = mysqli_real_escape_string($connection, $_POST['Callsign']);
	}
	if (isset($_POST['Rank']))
	{
		$Rank = $_POST['Rank'];
	}
	if (isset($_POST['Division']))
	{
		$Division = $_POST['Division'];
	}
	 
	$q = "INSERT into projectx_vvarsc2.members (
				mem_name
				,mem_callsign
				,mem_sc
				,mem_gint
				,mem_avatar_link
				,ranks_rank_id
				,divisions_div_id
				,specialties_spec_id
				,CreatedOn
				,websiteRole
				,password
			)
			VALUES(
				'$Name'
				,'$Callsign'
				,'1'
				,null
				,'default'
				,'$Rank'
				,'$Division'
				,null
				,DATE_ADD(CURDATE(),INTERVAL 930 YEAR)
				,'user'
				,'password'
			)";

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: http://sc.vvarmachine.com/admin_mem");
	}
	else
	{
		header("Location: http://sc.vvarmachine.com/error_generic");
	}
	
	$connection->close();
?>