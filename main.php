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
		<p>
			This website is designed to give VVarMachine an ability to manage our Star Citizen Division by recording number of players, ships and organizational structure.  If you are interested in joining VVarMachine please go to <a href="http://vvarmachine.com" target="_top">http://vvarmachine.com</a> or visit our RSI Organization page at <a href="https://robertsspaceindustries.com/orgs/VVAR" target="_blank">https://robertsspaceindustries.com/orgs/VVAR</a>.
		</p>
		<p>
			VVarMachine is proudly aligned with Cognition Corp, Interstellar Logistics & Transport Service and the Auraxis Military Pact organizations.
		</p>	
		<p>
			Use the menu options above to navigate this site to find out information about our Star Citizen Division.
		</p>
		<p>
			If you have questions or other issues please contact one of our Generals or Senior Officers on our <a href="http://discord.vvarmachine.com" target="_blank">Discord server</a>.
		</p>
		<p>
			Thanks,
			<br />
			VVarMachine Officers
		</p>
	</div>
</div>