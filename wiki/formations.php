<br />
<div class="div_filters_container">
	<div class="div_filters_entry">
		<a href="http://sc.vvarmachine.com/wiki/">&#8672; Back to Wiki Home</a>
	</div>
</div>
<h2>Military Flight Formations Viewer</h2>
<div id="TEXT">

	<div id="rankStructure_main">
		<div class="table_header_block">
		</div>
		<div class="unit_details_container">
			<p style="text-align: center; font-style: italic">
				Use the tool below to experiment and view the different common Formations for a 4-ship Flight of small Military Vessels. These Formations will be heavily used by Fighter, Reconnaissance, and Attack Squadrons during Organized Operations.
			</p>
			<div id="formations">
				<div class="shipDetails_info1_table_ship_desc" style="font-style: normal">
					<div class="corner corner-top-left">
					</div>
					<div class="corner corner-top-right">
					</div>
					<div class="corner corner-bottom-left">
					</div>
					<div class="corner corner-bottom-right">
					</div>
					<div class="div_filters_container" style="margin-left: 0">
						<div class="div_filters_entry" id="formation_finger4">
							Finger-Four
						</div>
						<div class="div_filters_entry" id="formation_leftEchelon">
							Left Echelon
						</div>
						<div class="div_filters_entry" id="formation_rightEchelon">
							Right Echelon
						</div>
						<div class="div_filters_entry" id="formation_spread">
							Spread
						</div>
						<div class="div_filters_entry" id="formation_fluidFour">
							Fluid Four
						</div>
						<div class="div_filters_entry" id="formation_trail">
							Trail
						</div>
					</div>
					<br />
					<div id="formations_description">
						Formation Description goes here!
					</div>
					<br />
					<div id="formations_inner">
						<div class="formation_ship" id="formation_ship01" >
							<div class="formation_ship_text1">
								1
							</div>
							<img class="formation_ship" src="../images/silo_topDown/hornet_super.png"/>
							<div class="formation_ship_text2">
								Flight Lead
							</div>
						</div>
						<div class="formation_ship" id="formation_ship02" >
							<div class="formation_ship_text1">
								2
							</div>
							<img class="formation_ship" src="../images/silo_topDown/hornet_super.png"/>
							<div class="formation_ship_text2">
								
							</div>
						</div>
						<div class="formation_ship" id="formation_ship03" >
							<div class="formation_ship_text1">
								3
							</div>
							<img class="formation_ship" src="../images/silo_topDown/hornet_super.png"/>
							<div class="formation_ship_text2">
								Element Lead
							</div>
						</div>
						<div class="formation_ship" id="formation_ship04" >
							<div class="formation_ship_text1">
								4
							</div>
							<img class="formation_ship" src="../images/silo_topDown/hornet_super.png"/>
							<div class="formation_ship_text2">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>

<script>
	$(document).ready(function() {
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
	});
	
	//Finger-Four
	$('#formation_finger4').click(function() {
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
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_spread').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '0%',
			left: '35%'
		});
		$('#formation_ship02').css({
			top: '0%',
			left: '15%'
		});
		$('#formation_ship03').css({
			top: '0%',
			left: '55%'
		});
		$('#formation_ship04').css({
			top: '0%',
			left: '75%'
		});		
	});
	
	//Fluid Four
	$('#formation_fluidFour').click(function() {
		$('.div_filters_entry').removeClass('div_filters_selected');
		$('#formation_fluidFour').addClass('div_filters_selected');
	
		$('#formation_ship01').css({
			top: '0%',
			left: '35%'
		});
		$('#formation_ship02').css({
			top: '25%',
			left: '15%'
		});
		$('#formation_ship03').css({
			top: '0%',
			left: '55%'
		});
		$('#formation_ship04').css({
			top: '25%',
			left: '75%'
		});		
	});
	
	//Trail
	$('#formation_trail').click(function() {
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
</script>

<script type="text/javascript" src="/js/jquery.jScale.js"></script>
<!--Script to Resize Fleet Images-->
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