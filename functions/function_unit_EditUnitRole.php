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

	$UnitID = "";
	$RowID = "";
	$RoleID = "";
	$MinRankID = "";
	$MaxRankID = "";
	$IsActive = "";
	
	if(isset($_POST['UnitID']))
	{
		$UnitID = $_POST['UnitID'];
	}	
	if(isset($_POST['RowID']))
	{
		$RowID = $_POST['RowID'];
	}		
	if(isset($_POST['RoleID']))
	{
		$RoleID = $_POST['RoleID'];
	}	
	if(isset($_POST['MinRankID']))
	{
		$MinRankID = $_POST['MinRankID'];
	}
	if(isset($_POST['MaxRankID']))
	{
		$MaxRankID = $_POST['MaxRankID'];
	}	
	if(isset($_POST['IsActive']))
	{
		$IsActive = $_POST['IsActive'];
	}
	
	//Validation
	$validation_q_min = "
		select
			r.rank_orderBy as MinRankOrderBy
			,r.rank_type as MinRankType
		from projectx_vvarsc2.ranks r
		where r.rank_id = '$MinRankID'
	";
	$validation_q_max = "
		select
			r.rank_orderBy as MaxRankOrderBy
			,r.rank_type as MaxRankType
		from projectx_vvarsc2.ranks r
		where r.rank_id = '$MaxRankID'
	";
	$val_q_min_result = $connection->query($validation_q_min);
	while(($row = $val_q_min_result->fetch_assoc()) != false)
	{
		$minRankType = $row['MinRankType'];
		$minRankOrderBy = $row['MinRankOrderBy'];
	}	
	
	$val_q_max_result = $connection->query($validation_q_max);
	while(($row = $val_q_max_result->fetch_assoc()) != false)
	{
		$maxRankType = $row['MaxRankType'];
		$maxRankOrderBy = $row['MaxRankOrderBy'];
	}
	
	//Function Reversed because Highest Rank has lowest orderBy number.
	if ($maxRankOrderBy > $minRankOrderBy)
	{
		//print_r("FUCK");
		header("Location: $link_base/error_generic");
	}
	
	$q = "
		update projectx_vvarsc2.UnitRoles set
			MinPayGrade = '$MinRankID'
			,MaxPayGrade = '$MaxRankID'
			,IsActive = $IsActive
		where RowID = '$RowID'
			and UnitID = '$UnitID'
	";
	
	print_r($q);
	$query_result = $connection->query($q);	
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