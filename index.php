<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if(!isset($_SESSION['sess_username'])/* || $role!="admin"*/)
	{
      header('Location: http://sc.vvarmachine.com/login.php?err=2');
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?
	putenv("TZ=US/Pacific");

	include_once('dbconn/dbconn.php');
?>
<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<? include_once('inc/meta.php'); ?>
	</head>
	<body>
	<div id="MainWebsiteInner">
		<? include_once('inc/header.php'); ?>
		<div id="CONTENT">
		<?
			$page = isset($_GET['page']) ? $_GET['page'] : '';
			
			switch($page) {
				/****************/
				/** Main Links **/
				/****************/
				case "main":
					$content = "main.php";
					break;
				
				case "members":
					$content = "members.php";
					break;
				
				case "fleet":
					$content = "fleet.php";
					break;
				
				case "fleetinfo":
					$content = "fleetinfo.php";
					break;
					
				case "shipyard":
					$content = "shipyard.php";
					break;
					
				case "units":
					$content = "units.php";
					break;
					
				case "divisions":
					$content = "divisions.php";
					break;
				
				case "links":
					$content = "links.php";
					break;
				
				/*****************/
				/** Other Links **/
				/*****************/
				case "player":
					$content = "player.php";
					break;
					
				case "ship":
					$content = "ship.php";
					break;
					
				case "unit":
					$content = "unit.php";
					break;
				
				/*****************/
				/** Admin Links **/
				/*****************/
				case "admin":
					$content = "admin.php";
					break;

				case "admin_manu":
					$content = "admin_manu.php";
					break;
			
				case "admin_ship":
					$content = "admin_ship.php";
					break;
			
				case "admin_mem":
					$content = "admin_mem.php";
					break;
				
				/******************/
				/** Default Page **/
				/******************/		
				default:
					$content = "main.php";
					break;
			}
			
			include_once($content);
			?>
			</div>
			<? include_once('inc/footer.php'); ?>
		</div>
	</body>
</html>

