<!DOCTYPE html>
<?
	putenv("TZ=US/Pacific");

	include_once('dbconn/dbconn.php');
	
	if($_SERVER['REQUEST_URI'] !== "/")
	{
		include_once('functions/function_auth_user.php');
	}
	else
	{
		session_start();
		$role = "";
		if(isset($_SESSION['sess_userrole']))
		{
			$role = $_SESSION['sess_userrole'];
		}
	
		$infoSecLevel = $_SESSION['sess_infoseclevel'];
	}
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<? include_once('inc/meta.php'); ?>
	</head>
	<body>
		<div class="fullscreen background parallax" data-img-width="3840" data-img-height="2160" data-diff="100">
			<div class="content-a" id="parallax__layer--base">
				<div class="content-b">
					<div id="MainWrapper">
						<div id="overlay">
						</div>
						<div id="MainWebsiteInner">
							<? include_once('inc/header.php'); ?>
							<br />
							<div class="two-line-separator">
							</div>
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
									
									case "membersList":
										$content = "membersList.php";
										break;
										
									case "combatRecord":
										$content = "combatRecord.php";
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
										
									case "mission":
										$content = "mission.php";
										break;
										
									case "calendar":
										$content = "calendar.php";
										break;
										
									case "missions":
										$content = "missions.php";
										break;
										
									case "operation":
										$content = "operation.php";
										break;
										
									case "operations":
										$content = "operations.php";
										break;
										
									case "admin":
										$content = "admin/index.php";
										break;
										
									case "wiki":
										$content = "wiki/index.php";
										break;
										
									/*****************/
									/** Static Links **/
									/*****************/
									case "ranks":
										$content = "rankStructure.php";
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
									
									/******************/
									/** Error Pages **/
									/******************/		
									case "error_generic":
										$content = "error_generic.php";
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
								<!--
								<div id="dynamicSpacer">
									&nbsp;
								</div>
								-->

							</div>
						</div>
					</div>
					<? include_once('inc/footer.php'); ?>
				</div>
			</div>
		</div>
	</body>
</html>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="<? $link_base; ?>/js/jquery.jScale.js"></script>
<script type="text/javascript" src="<? $link_base; ?>/js/jquery.jscroll.min.js"></script>

<!--Script to make parallax backgrounds full-height-->
<script>
/* detect touch */
if("ontouchstart" in window){
    document.documentElement.className = document.documentElement.className + " touch";
}
if(!$("html").hasClass("touch")){
    /* background fix */
    $(".parallax").css("background-attachment", "fixed");
}

/* fix vertical when not overflow call fullscreenFix() if .fullscreen content changes */
function fullscreenFix(){
    var h = $('body').height();
	var h2 = $(".content-b").innerHeight();
	
    /* set .fullscreen height */
	if(h2 > h)
	{
		$(".fullscreen").addClass('overflow');
	}
	
    $('.content-b').each(function(i){
        if($(this).innerHeight() > h)
		{
			$(this).closest('.fullscreen').addClass("overflow");
        }
    });
}
$(window).resize(fullscreenFix);
fullscreenFix();

/* resize background images */
function backgroundResize(){
    var windowH = $(window).height();
    $(".background").each(function(i){
        var path = $(this);
        /* variables */
        var contW = path.width();
        var contH = path.height();
        var imgW = path.attr("data-img-width");
        var imgH = path.attr("data-img-height");
        var ratio = imgW / imgH;
        /* overflowing difference */
        var diff = parseFloat(path.attr("data-diff"));
        diff = diff ? diff : 0;
        /* remaining height to have fullscreen image only on parallax */
        var remainingH = 0;
        if(path.hasClass("parallax") && !$("html").hasClass("touch")){
            var maxH = contH > windowH ? contH : windowH;
            remainingH = windowH - contH;
        }
        /* set img values depending on cont */
        imgH = contH + remainingH + diff;
        imgW = imgH * ratio;
        /* fix when too large */
        if(contW > imgW){
            imgW = contW;
            imgH = imgW / ratio;
        }
		
        path.data("resized-imgW", imgW);
        path.data("resized-imgH", imgH);
        path.css("background-size", imgW + "px " + imgH + "px");
    });
}
$(window).resize(backgroundResize);
$(window).focus(backgroundResize);
backgroundResize();

/* set parallax background-position */
function parallaxPosition(e){
    var heightWindow = $(window).height();
    var topWindow = $(window).scrollTop();
    var bottomWindow = topWindow + heightWindow;
    var currentWindow = (topWindow + bottomWindow) / 2;
    $(".parallax").each(function(i){
        var path = $(this);
        var height = path.height();
        var top = path.offset().top;
        var bottom = top + height;
        /* only when in range */
        if(bottomWindow > top && topWindow < bottom){
            var imgW = path.data("resized-imgW");
            var imgH = path.data("resized-imgH");
            /* min when image touch top of window */
            var min = 0;
            /* max when image touch bottom of window */
            var max = - imgH + heightWindow;
            /* overflow changes parallax */
            var overflowH = height < heightWindow ? imgH - height : imgH - heightWindow;
			/* fix height on overflow */
            top = top - overflowH;
            bottom = bottom + overflowH;
            /* value with linear interpolation */
            var value = min + (max - min) * (currentWindow - top) / (bottom - top);
            /* set background-position */
            var orizontalPosition = path.attr("data-oriz-pos");
            orizontalPosition = orizontalPosition ? orizontalPosition : "50%";
            $(this).css("background-position", orizontalPosition + " " + value + "px");
        }
    });
}
if(!$("html").hasClass("touch")){
    $(window).resize(parallaxPosition);
    $(window).scroll(parallaxPosition);
    parallaxPosition();
}
</script>
