<!DOCTYPE html>
<?
	putenv("TZ=US/Pacific");

	include_once('../dbconn/dbconn.php');
	include_once('../functions/function_auth_user.php');
?>
<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex,nofollow">
	<? include_once('../inc/meta.php'); ?>
	</head>
	<body>
		<div class="fullscreen background parallax" data-img-width="3840" data-img-height="2160" data-diff="100">
			<div class="content-a" id="parallax__layer--base">
				<div class="content-b">
					<div id="MainWrapper">
						<div id="overlay">
						</div>
						<div id="MainWebsiteInner">
							<? include_once('../inc/header.php'); ?>
							<div id="CONTENT">
								<br />
								<?php
									echo "<center><iframe src=\"index.php\" style=\"border:none; width:96%;\" class=\"myIframe\"></iframe></center>";
								?>
								<script type="text/javascript" language="javascript"> 
									$('.myIframe').css('height', $(window).height()-250+'px');
								</script>
							</div>
						</div>
					</div>
					<? include_once('../inc/footer.php'); ?>
				</div>
			</div>
		</div>
	</body>
</html>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.jScale.js"></script>
<script type="text/javascript" src="<? $link_base; ?>/js/jquery.jscroll.min.js"></script>