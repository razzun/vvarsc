

<h2>Military Flight Formations Viewer</h2>
<div id="TEXT">
	<div id="rankStructure_main">
		<div class="div_filters_container">
			<div class="div_filters_entry">
				<a href="<? $link_base; ?>/wiki/">&#8672; Back to Wiki Home</a>
			</div>
		</div>
		<br />	
		<div class="table_header_block">
		</div>
		<div class="unit_details_container">
			<p style="text-align: center; font-style: italic">
				Use the tool below to experiment and view the different common Formations for a 4-ship Flight of small Military Vessels. These Formations will be heavily used by Fighter, Reconnaissance, and Attack Squadrons during Organized Operations.
			</p>
			<div id="formations">
				<div class="div_filters_container" style="margin-left: 0; text-align: center">
					<div class="div_filters_entry" id="formation_box">
						Box
					</div>
					<div class="div_filters_entry" id="formation_leftEchelon">
						Echelon Left
					</div>
					<div class="div_filters_entry" id="formation_rightEchelon">
						Echelon Right
					</div>
					<div class="div_filters_entry" id="formation_finger4">
						Finger Four
					</div>
					<div class="div_filters_entry" id="formation_fluidFour">
						Fluid Four
					</div>
					<div class="div_filters_entry" id="formation_ladder">
						Ladder
					</div>
					<div class="div_filters_entry" id="formation_spread">
						Spread
					</div>
					<div class="div_filters_entry" id="formation_trail">
						Trail
					</div>
					<div class="div_filters_entry" id="formation_wall">
						Wall
					</div>
				</div>
				<br />
				<div id="formations_description">
					Formation Description goes here!
				</div>
				
				<div class="shipDetails_info1_table_ship_desc" style="font-style: normal">
					<div class="corner corner-top-left">
					</div>
					<div class="corner corner-top-right">
					</div>
					<div class="corner corner-bottom-left">
					</div>
					<div class="corner corner-bottom-right">
					</div>
					<br />
					<div id="formations_inner">
						<div class="formation_ship" id="formation_ship01" >
							<div class="formation_ship_text1">
								1
							</div>
							<img class="formation_ship" id="formation_ship01_img" src="../images/silo_topDown/hornet_super.png"/>
							<div class="formation_ship_text2">
								Flight Lead
							</div>
						</div>
						<div class="formation_ship" id="formation_ship02" >
							<div class="formation_ship_text1">
								2
							</div>
							<img class="formation_ship" id="formation_ship02_img" src="../images/silo_topDown/hornet_super.png"/>
							<div class="formation_ship_text2">
								
							</div>
						</div>
						<div class="formation_ship" id="formation_ship03" >
							<div class="formation_ship_text1">
								3
							</div>
							<img class="formation_ship" id="formation_ship03_img" src="../images/silo_topDown/hornet_super.png"/>
							<div class="formation_ship_text2">
								Element Lead
							</div>
						</div>
						<div class="formation_ship" id="formation_ship04" >
							<div class="formation_ship_text1">
								4
							</div>
							<img class="formation_ship" id="formation_ship04_img" src="../images/silo_topDown/hornet_super.png"/>
							<div class="formation_ship_text2">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>


<script type="text/javascript" src="/js/jquery.jScale.js"></script>

<script>
	$(document).ready(function() {
		$('.formation_ship').hide();
		$('.formation_ship').jScale({w: '100%'});
		//Initial Setup (Finger-Four is default)
		$('#formation_finger4').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '0%',
			left: '35%'
		});
		$('#formation_ship02').css({
			top: '25%',
			left: '15%'
		});
		$('#formation_ship03').css({
			top: '25%',
			left: '55%'
		});
		$('#formation_ship04').css({
			top: '50%',
			left: '75%'
		});
		
		$('.formation_ship').show();
	});
	
	//Finger-Four
	$('#formation_finger4').click(function() {
		$('.formation_ship').jScale({w: '100%'});
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_finger4').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '0%',
			left: '35%'
		});
		$('#formation_ship02').css({
			top: '25%',
			left: '15%'
		});
		$('#formation_ship03').css({
			top: '25%',
			left: '55%'
		});
		$('#formation_ship04').css({
			top: '50%',
			left: '75%'
		});	
	});
	
	//Left Echelon
	$('#formation_leftEchelon').click(function() {
		$('.formation_ship').jScale({w: '100%'});
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_leftEchelon').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '0%',
			left: '75%'
		});
		$('#formation_ship02').css({
			top: '25%',
			left: '55%'
		});
		$('#formation_ship03').css({
			top: '50%',
			left: '35%'
		});
		$('#formation_ship04').css({
			top: '75%',
			left: '15%'
		});		
	});
	
	//Right Echelon
	$('#formation_rightEchelon').click(function() {
		$('.formation_ship').jScale({w: '100%'});
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_rightEchelon').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '0%',
			left: '15%'
		});
		$('#formation_ship02').css({
			top: '25%',
			left: '35%'
		});
		$('#formation_ship03').css({
			top: '50%',
			left: '55%'
		});
		$('#formation_ship04').css({
			top: '75%',
			left: '75%'
		});		
	});
	
	//Spread
	$('#formation_spread').click(function() {
		$('.formation_ship').jScale({w: '100%'});
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_spread').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '25%',
			left: '35%'
		});
		$('#formation_ship02').css({
			top: '25%',
			left: '15%'
		});
		$('#formation_ship03').css({
			top: '25%',
			left: '55%'
		});
		$('#formation_ship04').css({
			top: '25%',
			left: '75%'
		});		
	});
	
	//Fluid Four
	$('#formation_fluidFour').click(function() {
		$('.formation_ship').jScale({w: '100%'});
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_fluidFour').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '20%',
			left: '35%'
		});
		$('#formation_ship02').css({
			top: '45%',
			left: '15%'
		});
		$('#formation_ship03').css({
			top: '20%',
			left: '55%'
		});
		$('#formation_ship04').css({
			top: '45%',
			left: '75%'
		});		
	});
	
	//Trail
	$('#formation_trail').click(function() {
		$('.formation_ship').jScale({w: '100%'});
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_trail').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '0%',
			left: '45%'
		});
		$('#formation_ship02').css({
			top: '25%',
			left: '45%'
		});
		$('#formation_ship03').css({
			top: '50%',
			left: '45%'
		});
		$('#formation_ship04').css({
			top: '75%',
			left: '45%'
		});		
	});
	
	//Ladder
	$('#formation_ladder').click(function() {
		$('.formation_ship').jScale({w: '100%'});
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_ladder').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '0%',
			left: '45%'
		});
		
		$('#formation_ship02').css({
			top: '25%',
			left: '45%'
		});
		$('#formation_ship02_img').jScale({w: '75%'});
		
		$('#formation_ship03').css({
			top: '50%',
			left: '45%'
		});
		$('#formation_ship03_img').jScale({w: '50%'});
		
		$('#formation_ship04').css({
			top: '75%',
			left: '45%'
		});		
		$('#formation_ship04_img').jScale({w: '25%'});
	});

	//Box
	$('#formation_box').click(function() {
		$('.formation_ship').jScale({w: '100%'});
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_box').addClass('div_filters_selected');
		
		$('#formation_ship01').css({
			top: '20%',
			left: '35%'
		});
		$('#formation_ship02').css({
			top: '20%',
			left: '55%'
		});
		
		$('#formation_ship03').css({
			top: '55%',
			left: '35%'
		});
		$('#formation_ship03_img').jScale({w: '50%'});
		
		$('#formation_ship04').css({
			top: '55%',
			left: '55%'
		});
		$('#formation_ship04_img').jScale({w: '50%'});
	});
	
	//Wall
	$('#formation_wall').click(function() {
		$('.formation_ship').jScale({w: '100%'});
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_wall').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '25%',
			left: '15%'
		});
		
		$('#formation_ship02').css({
			top: '25%',
			left: '35%'
		});
		$('#formation_ship02_img').jScale({w: '50%'});
		
		$('#formation_ship03').css({
			top: '25%',
			left: '55%'
		});
		$('#formation_ship03_img').jScale({w: '75%'});
		
		$('#formation_ship04').css({
			top: '25%',
			left: '75%'
		});		
		$('#formation_ship04_img').jScale({w: '25%'});	
		
	});
</script>


<!--Script to Resize Fleet Images-->
<!--
<script>

	$(document).ready(function() {
	
		var imageClass = $('.formation_ship');
		
		if(($( window ).width() < 800)) {
			imageClass.jScale({w: '60%'});
			imageClass.css({
					"margin": '0px'
				});
		}	
		else if(($( window ).width() < 1200)){
			imageClass.jScale({w: '80%'});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '100%'});
			imageClass.css({
					"margin": '2px'
				});		
		}
	});
	
	$(window).resize(function () {
	
		var imageClass = $('.formation_ship');
		
		if(($( window ).width() < 800)) {
			imageClass.jScale({w: '60%'});
			imageClass.css({
					"margin": '0px'
				});
			
		}	
		else if(($( window ).width() < 1200)){
			imageClass.jScale({w: '80%'});
			imageClass.css({
					"margin": '1px'
				});
		}
		else {
			imageClass.jScale({w: '100%'});
			imageClass.css({
					"margin": '2px'
				});		
		}
	});	

</script>
-->