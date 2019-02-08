<?
	if(isset($userID))
	{
		$welcomeText = 'Welcome back ' . $userName . '!';
	}else
	{
		$welcomeText = 'Most areas of the site are secure, please <a href=/login.php>login</a> to view them.';
	}
?>

<h2>VVarMachine Star Citizen Division Home</h2>
<div id="TEXT">

	<div id="HomePage_MainContainer">
		<img height="125" width="125" class="TextWrapLeft" src="<? $link_base; ?>/images/logos/vvar-logo.png">
		<p>
			<? echo $welcomeText; ?>
		</p>		
		<p>The now infamous paramilitary unit known as VVarMachine can trace its origins back to the mid-27th Century, prior to the first Vanduul incursion into UEE space in 2681. Upon completing their term of service within the UEE Navy, a small group of Sailors and Pilots departed the relative safety of their HQ in the Killian System to find a corner of the galaxy for themselves. Seeking freedom and adventure after a regimented career of military servitude, they eventually found a home at the edge of the known galaxy: a newly terraformed Planet known as Mya, in the Leir System.</p>
		<p>Fast forward a handful of years, a radical group of civilians known as the "Outsiders" arose to power within the Leir system. Enacting stringent laws and enforcing taxes with brute strength, they suppressed trading and communication with outside parties, especially the UEE. The former UEE members within Leir opposed the power shift. The Sailors and Pilots who came seeking freedom resented the idea of another entity dominating their lives. The former brothers-in-arms convened an emergency meeting. Old connections were reforged, memories relived, and a rag-tag ensemble of experienced Veterans vowed to take back their lives for themselves. VVarMachine was born.</p>
		<p>Realizing they had the experience but not the numbers to become combat effective, they began recruiting. It offered an attractive alternative to the residents of the Leir system compared to the oppressive bully-mongering of the Outsiders. Its founding members committed to upholding the UEE ideals, with the goal of building a self-sufficient Organization that could operate within the restrictions placed-upon the system by the Outsiders, while covertly resisting its rule.</p>
		<p>VVarMachine’s faction began gaining popularity among the civilians of Leir. New members joined daily, and an outright assault plan to liberate Leir was carefully crafted. Ruling Leir was not important. Living a free man’s life was paramount. But before VVAR could make its move, another threat arose: to Leir, and to the entire human race.</p>
		<p>In 2681, Humanity got its first taste of conflict with the Vanduul. It wasn't known at the time, but the Leir system held a small jump-point to a frontier world in Vanduul space – a system known today as "Vanguard". Upon the discovery of this jump-point by local explorers, it became clear that the Leir system would become a frequent target for Vanduul attacks, due to its high number of trade routes into UEE space that existed before the rise of the Outsiders. VVarMachine saw this threat arise, and took it upon themselves to ensure the safety of Leir's residents, and ultimately, the UEE’s limited ties to the system.</p>
		<p>Since the war with the Vanduul broke out, and lacking the true-grit military experience and hardware of the UEE, the Outsiders infrastructure faltered. VVarMachine was ready. Eager to respond to incoming threats with utmost urgency and prowess, it has expanded its ranks within the Leir system to include former civilians who are now dedicating their time and assets to resource extraction and processing to support the ongoing war efforts in and around the system. While there is little likelihood of the "Outsiders" relinquishing control the government, the residents of the Leir system have come to honor and appreciate the bravery of VVarMachine personnel risking their lives on a daily basis to protect what those residents hold dearly – freedom.</p>
		<p>You can find more information about us on our <a href="http://rsi.vvarmachine.com/" target="_blank">Recruitment Thread</a> or come ask us questions on our <a href="http://discord.vvarmachine.com/" target="_blank">Discord</a>.</p>
	</div>
	<div align="center">
		<p>
			<h3>Featured Video</h3>
			<a href="https://www.youtube.com/watch?v=I5HvHtsOnXA" target="_blank"><img src="images/featured_video.jpg" alt=""></a>
			<!-- <iframe width="640" height="360" src="https://www.youtube.com/embed/I5HvHtsOnXA" frameborder="0" allowfullscreen></iframe> -->
		</p>
	</div>	
</div>