<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
						<div id="MainWebsiteInner">
							<? include_once('inc/header.php'); ?>
							<div class="two-line-separator">
							</div>
							<div id="CONTENT">
								<h2>VVARMachine Star Citizen Division Home</h2>
								<div id="TEXT">
									<!--Login Section-->
									<div class="col-md-6 col-md-offset-3">
										<h4>
											Log in with your credentials
										</h4>
										<br/>
										<div class="block-margin-top">
											  <?php 

												$errors = array(
													1=>"Invalid user name or password, Try again",
													2=>"Please login to access this area",
													3=>"Unexpected Server Error Occurred with Database Connection - please inform website Admin!",
													4=>"This Area Requires Admin Access - we're really sorry, but you're not an Admin!"
												  );

												$error_id = isset($_GET['err']) ? (int)$_GET['err'] : 0;

												if ($error_id != 0) {
														echo '<p class="text-danger">'.$errors[$error_id].'</p>';
													}
											   ?>  

											<form action="authenticate.php" method="POST" class="form-signin col-md-8 col-md-offset-2" role="form">
												<input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
												<br/>
												<input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
												<br/>
												<input type="button" 
														value="Login" 
														onclick="formhash(this.form, this.form.password);" /> 
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<? include_once('inc/footer.php'); ?>
				</div>
			</div>
		</div>
	</body>
</html>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/jquery.jScale.js"></script>
<script type="text/javascript" src="/js/sha512.js"></script>
<script type="text/javascript" src="http://www.wduffy.co.uk/blog/wp-content/themes/agregado/js/jquery.jscroll.min.js"></script>

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

<script>

function formhash(form, password){
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
 
    // Finally submit the form. 
    form.submit();
}
</script>