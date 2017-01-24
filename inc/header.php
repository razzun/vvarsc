<?
	$display_login = "";
	$display_securedLinks = "";
	$display_officerLinks = "";
	$display_adminZone = "";
	$link_base = "http://" . $_SERVER['SERVER_NAME'];
	
	if(isset($_SESSION['sess_username']))
	{
		$userName = $_SESSION['sess_username'];
		$userID = $_SESSION['sess_user_id'];
		$role = $_SESSION['sess_userrole'];
		$infoSecLevelID = $_SESSION['sess_infoseclevel'];
		$display_login .= "
			<div>
				Welcome <a href=\"$link_base/player/$userID\">$userName</a> // <a class=\"headerLoginLink\" href=/logout.php>Logout</a>
			</div>
		";
	}
	else
	{
		$display_login .= "
			<div>
				<a class=\"headerLoginLink\" href=/login.php>Login</a>
			</div>
		";
	}
	
	if ($infoSecLevelID > 1)
	{
		$display_securedLinks .= "
			<div class=\"nav_entry\">
				<div class=\"nav_entry_inner\">
					<a class=\"navbar_inner\" href=\"/fleetinfo/\">Fleet Infograph</a>
				</div>
			</div>
		";
	}
	
	if ($role == 'officer' || $role == 'admin')
	{
		$display_officerLinks .= "
			<div class=\"nav_entry\">
				<div class=\"nav_entry_inner\">
					<a class=\"navbar_inner\" href=\"/operations/\">OpPlan Templates</a>
				</div>
			</div>
		";
	}
	
	if ($role == 'admin')
	{
		$display_adminZone .= "
			<div class=\"nav_entry\">
				<div class=\"nav_entry_inner\">
					<a class=\"navbar_inner\" href=\"/admin/\">Admin Zone</a>
				</div>
			</div>
		";
	}
?>

<div id="header">
	<div class="top-line">
	</div>
	<div class="corner4 corner-diag-blue-topRight">
	</div>
	<div class="partialBorderTopRight50">
	</div>
	<!--
	<div class="HeaderContainer">
		<img class="height="105" width="1200" border="0" src="http://vvarmachine.com/themes/starcitzen/images_custom/2banner_04_noBorder.png"/>
	</div>
	-->
	<div id="nav_player_info" style="float:right">
		<? echo $display_login ?>
	</div>

	<iframe src="https://free.timeanddate.com/clock/i5h0wbhu/tluk/fn16/fs12/fcddd/tct/pct/ftbi/tt0/tw1/td2/th1/ta1/tb2" frameborder="0" width="226" height="17" allowTransparency="true" style="
		float:left;
		margin-left: 16px;
	">
	</iframe>


	<div id="nav_container">
		<div id="nav_header">
			<img class="nav_header_arrow" align="center" src="/images/misc/SC_Button01.png"/>
			<div id="nav_header_text">
				Navigation
			</div>
		</div>
		<nav>
			<div class="corner2 corner-top-left">
			</div>
			<div class="corner2 corner-top-right">
			</div>
			<div class="corner2 corner-bottom-left">
			</div>
			<div class="corner2 corner-bottom-right">
			</div>
			<div class="nav_content">
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/">Home</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/divisions/0">Members</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/units">Divisions & Units</a>
					</div>
				</div>
				<? echo $display_securedLinks; ?>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/shipyard">Shipyard</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/missions/">Missions</a>
					</div>
				</div>
				<? echo $display_officerLinks; ?>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/wiki/">Wiki</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/links">Links</a>
					</div>
				</div>
				<? echo $display_adminZone; ?>
			</div>
		</nav>
	</div>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!--Script to Show/Hide Filter Rows when Expansion Arrow is Clicked-->
<script>
    $(document).ready(function () {
		if((screen.width < 500)){
			$(".nav_content").hide();
		}
		else {
			$(".nav_content").show();
		}
		
		$("#nav_header").click(function () {
			$(this).parent().find(".nav_content").slideToggle(500);
			$(this).find('.nav_header_arrow').toggleClass('rotate90CW');
		});		
    });
</script>

<script>
	$(window).resize(function () {
		if((screen.width < 500)){
			$(".nav_content").hide();
			$(".nav_header_arrow").removeClass('rotate90CW');
		}
		else {
			$(".nav_content").show();
		}
	});
</script>
