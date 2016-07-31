<?
	$display_login = "";
	
	if(isset($_SESSION['sess_username']))
	{
		$userName = $_SESSION['sess_username'];
		$userID = $_SESSION['sess_user_id'];
		$display_login .= "
			<div>
				Welcome <a href=\"http://sc.vvarmachine.com/player/$userID\">$userName</a> // <a class=\"headerLoginLink\" href=/logout.php>Logout</a>
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
?>

<div id="header">
	<div class="top-line">
	</div>
	<div class="corner4 corner-diag-blue-topRight headerCornerTopRight">
	</div>
	<!--
	<div class="HeaderContainer">
		<img class="height="105" width="1200" border="0" src="http://vvarmachine.com/themes/starcitzen/images_custom/2banner_04_noBorder.png"/>
	</div>
	-->
	<div id="nav_player_info">
		<? echo $display_login ?>
	</div>
	<div id="nav_container">
		<div id="nav_header">
			<img class="nav_header_arrow" align="center" src="http://vvarmachine.com/uploads/galleries/SC_Button01.png"/>
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
						<a class="navbar_inner" href="http://vvarmachine.com/index.php?page=cedi&type=misc&id=1%2F25" target="_blank">About Us &#38; CoC</a></div>
					</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/members">Members</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/divisions/0">Divisions & Units</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/fleetinfo">Fleet Infograph</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/shipyard">Shipyard</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/links">Links</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="http://vvarmachine.com/index.php?page=join&type=misc" target="_blank">Join VVAR</a>
					</div>
				</div>
				<div class="nav_entry">
					<div class="nav_entry_inner">
						<a class="navbar_inner" href="/admin/">Admin Zone</a>
					</div>
				</div>
			</div>
		</nav>
	</div>
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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