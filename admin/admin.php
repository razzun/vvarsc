<h2>VVARMachine SCD - ADMIN ZONE</h2>
<div id="TEXT">
	<div id="adminMenuContainer">
		<? include_once('admin_menu.php'); ?>
	</div>
	<div id="adminMainTextContainer">
		<h3>
			Admin Zone Home
		</h3>
		<div class="top-line">
		</div>
		<div class="corner4 corner-diag-blue-topLeft">
		</div>
		<div class="corner4 corner-diag-blue-topRight">
		</div>
		<div class="corner4 corner-diag-blue-bottomLeft">
		</div>
		<div class="corner4 corner-diag-blue-bottomRight">
		</div>		
		<div id="adminMainText">
			<p>
				Welcome back <? print_r ($_SESSION['sess_username']); ?>.  What would you like to do?
			</p>
			<br />
			<p>
				<strong>Manufacturer Management - </strong> Used to manage the different manufacturers used in Star Citizen.
				</br>
				<strong>Member Management - </strong> Used to manage VVARMachine members, and their owned ships.
				</br>
				<strong>Ship Management - </strong> Not currently active.  Check back later.
				</br>
				<strong>Roles Management - </strong> Used to manage the different roles within the VVARMachine Star Citizen Division.
				</br>
				<strong>Unit Management - </strong> Used to manage the different units within the VVARMachine Star Citizen Division, including members.
			</p>
		</div>
	</div>
</div>