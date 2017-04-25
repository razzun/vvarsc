<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username']) || $role!="admin")
	{
      header('Location: $link_base/login.php?err=4');
    }
?>

<?php
	//print_r($_POST);
	
	require_once('../dbconn/dbconn.php');
	require_once('../functions/security/function_csprng.php');
	
	session_start();

	$VVarID = "";
	$Name = "";
	$Callsign = "";
	$Rank = "";
	$Division = "";
	$MembershipType = "";
	$InfoSecLevel = "";
	$NewPassword = csprng(16);
	$NewHashedPassword = password_hash($NewPassword, PASSWORD_DEFAULT);
	
	if (isset($_POST['VVarID']))
	{
		$VVarID = mysqli_real_escape_string($connection, $_POST['VVarID']);
	}
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
	if (isset($_POST['MembershipType']))
	{
		$MembershipType = $_POST['MembershipType'];
	}
	if (isset($_POST['InfoSecLevel']))
	{
		$InfoSecLevel = $_POST['InfoSecLevel'];
	}
	
	$q = "INSERT into projectx_vvarsc2.members (
				mem_name
				,mem_callsign
				,mem_sc
				,mem_gint
				,mem_avatar_link
				,ranks_rank_id
				,divisions_div_id
				,CreatedOn
				,websiteRole
				,password
				,membership_type
				,InfoSecLevelID
				,RankModifiedOn
				,vvar_id
			)
			VALUES(
				'$Name'
				,'$Callsign'
				,'1'
				,null
				,'default'
				,'$Rank'
				,'$Division'
				,DATE_ADD(UTC_TIMESTAMP(),INTERVAL 930 YEAR)
				,'user'
				,'$NewHashedPassword'
				,'$MembershipType'
				,'$InfoSecLevel'
				,DATE_ADD(UTC_TIMESTAMP(), INTERVAL 930 YEAR)
				,'$VVarID'
			)";
			
	print_r($NewPassword);

	$query_result = $connection->query($q);
			
	if ($query_result)
	{
		header("Location: $link_base/admin/?page=admin_mem_success&pw=$NewPassword");
	}
	else
	{
		header("Location: $link_base/error_generic");
	}

	$connection->close();
?>