<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<? include_once('inc/meta.php'); ?>
	</head>
<body>
	<div id="MainWebsiteInner">
		<? include_once('inc/header.php'); ?>
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
								3=>"Unexpected Server Error Occurred with Database Connection - please inform website Admin!"
							  );

							$error_id = isset($_GET['err']) ? (int)$_GET['err'] : 0;

							if ($error_id != 0) {
									echo '<p class="text-danger">'.$errors[$error_id].'</p>';
								}
						   ?>  

						<form action="authenticate.php" method="POST" class="form-signin col-md-8 col-md-offset-2" role="form">
							<input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
							<br/>
							<input type="password" name="password" class="form-control" placeholder="Password" required>
							<br/>
							<button class="btn btn-lg btn-primary btn-block" type="submit">
								Sign in
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	<? include_once('inc/footer.php'); ?>
	</div>
</body>