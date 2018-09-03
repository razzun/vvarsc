<!DOCTYPE html>
<?
	putenv("TZ=US/Pacific");

	include_once('../dbconn/dbconn.php');
	include_once('../functions/function_auth_user.php');
?>

<?
	$wikiMenu_query = "select * from sc_view_wikiMenu";
	
	$wikiMenu_query_results = $connection->query($wikiMenu_query);
	$displayWikiMenu = "";
	$displayWikiMenuDesc = "";
	$displayWikiMenuSwitch = "";
	$wmIntExtDisp = "";
	$wmActiveDisp = "";
	
	while(($row = $wikiMenu_query_results->fetch_assoc()) != false)
	{
		$wmID = $row['wmID'];
		$wmName = $row['wmName'];
		$wmDesc = $row['wmDesc'];
		$wmOrderBy = $row['wmOrderBy'];
		$wmLink = $row['wmLink'];
		$wmSwitchName = $row['wmSwitchName'];
		$wmSwitchLink = $row['wmSwitchLink'];
		$wmIntExt = $row['wmIntExt'];
		$wmActive = $row['wmActive'];
		
		if ($wmIntExt == 1)
			$wmIntExtDisp = "External";
		else
			$wmIntExtDisp = "Internal";
		
		if ($wmActive == 1)
			$wmActiveDisp = "Yes";
		else
			$wmActiveDisp = "No";
		
		if ($wmIntExt == 1) {
			$displayWikiMenu .= "
				<li><a href =\"$wmLink\" target=\"_blank\">$wmName</a></li>
			";
		} else {
			$displayWikiMenu .= "
				<li><a href =\"$wmLink\">$wmName</a></li>
			";
		}
		
		$displayWikiMenuDesc .= "
			<strong>$wmName - </strong> $wmDesc <br />
		";
		
		$displayWikiMenuSwitch .= '
			case "' . $wmSwitchName . '":
			$content = "' . $wmSwitchLink . '";
			break;
		';
	};
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
							<?
								$page = isset($_GET['page']) ? $_GET['page'] : '';
								
								switch($page) {
									/*****************/
									/** Wiki Links **/
									/*****************/

									//$displayWikiMenuSwitch;
									case "awards":
										$content = "awards.php";
										break;
										
									case "formations":
										$content = "formations.php";
										break;
										
									case "facademy":
										$content = "facademy.php";
										break;
									
									case "marineTeams":
										$content = "marineTeams.php";
										break;
										
									case "operations":
										$content = "operations.php";
										break;
										
									case "ranks":
										$content = "rankStructure.php";
										break;
										
									case "qual":
										$content = "qualifications.php";
										break;
										
									case "units":
										$content = "unitStructure.php";
										break;
									
									/*******************/
									/** Academy Pages **/
									/*******************/		
									case "fac-bfm":
										$content = "fac-bfm.php";
										break;
									
									case "fac-sos":
										$content = "fac-sos.php";
										break;
										
									case "fac-scm":
										$content = "fac-scm.php";
										break;
										
									case "fac-l1vf":
										$content = "fac-l1vf.php";
										break;
									
									/******************/
									/** Error Pages **/
									/******************/		
									case "error_generic":
										$content = "../error_generic.php";
										break;
									
									/******************/
									/** Default Page **/
									/******************/		
									default:
										$content = "wiki.php";
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
					<? include_once('../inc/footer.php'); ?>
				</div>
			</div>
		</div>
	</body>
</html>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.jScale.js"></script>
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

/* fix vertical when not overflow
call fullscreenFix() if .fullscreen content changes */
function fullscreenFix(){
    var h = $('body').height();
	var h2 = $(".content-b").innerHeight();
	
    // set .fullscreen height
	if(h2 > h)
	{
		$(".fullscreen").addClass('overflow');
	}
	
	/*
    $('.content-b').each(function(i){
        if($(this).innerHeight() > h)
		{
			$(this).closest('.fullscreen').addClass("overflow");
        }
    });
	*/
}
$(window).resize(fullscreenFix);
fullscreenFix();

/* resize background images */
function backgroundResize(){
    var windowH = $(window).height();
    $(".background").each(function(i){
        var path = $(this);
        // variables
        var contW = path.width();
        var contH = path.height();
        var imgW = path.attr("data-img-width");
        var imgH = path.attr("data-img-height");
        var ratio = imgW / imgH;
        // overflowing difference
        var diff = parseFloat(path.attr("data-diff"));
        diff = diff ? diff : 0;
        // remaining height to have fullscreen image only on parallax
        var remainingH = 0;
        if(path.hasClass("parallax") && !$("html").hasClass("touch")){
            var maxH = contH > windowH ? contH : windowH;
            remainingH = windowH - contH;
        }
        // set img values depending on cont
        imgH = contH + remainingH + diff;
        imgW = imgH * ratio;
        // fix when too large
        if(contW > imgW){
            imgW = contW;
            imgH = imgW / ratio;
        }
        //
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
        // only when in range
        if(bottomWindow > top && topWindow < bottom){
            var imgW = path.data("resized-imgW");
            var imgH = path.data("resized-imgH");
            // min when image touch top of window
            var min = 0;
            // max when image touch bottom of window
            var max = - imgH + heightWindow;
            // overflow changes parallax
            var overflowH = height < heightWindow ? imgH - height : imgH - heightWindow; // fix height on overflow
            top = top - overflowH;
            bottom = bottom + overflowH;
            // value with linear interpolation
            var value = min + (max - min) * (currentWindow - top) / (bottom - top);
            // set background-position
            var orizontalPosition = path.attr("data-oriz-pos");
            orizontalPosition = orizontalPosition ? orizontalPosition : "50%";
            $(this).css("background-position", orizontalPosition + " " + value + "px");
        }
    });
}
if(!$("html").hasClass("touch")){
    $(window).resize(parallaxPosition);
    //$(window).focus(parallaxPosition);
    $(window).scroll(parallaxPosition);
    parallaxPosition();
}
</script>