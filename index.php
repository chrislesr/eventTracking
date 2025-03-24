<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EventTracking</title>
	<!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="row justify-content-center align-items-center m-3">
			<div class="col-lg-6">
			<div class="card">
				<div class="card-body">
					<h4 class="text-center">Event-Tracking</h4>
				<form action="pages/login" method="post">
					<div class="mb-3">
						<label class="form-label">adresse E-mail</label>
						<input type="email" class="form-control" name="email" required autocomplete="off"><br>
					</div>
					<div class="mb-3">
						<label class="form-label">Mot de passe</label>
						<input type="password" class="form-control" name="password" required autocomplete="off"><br>
					</div>
					<div class="mb-3">
						<?php
							 if(isset($_SESSION['message'])){
							 	echo '<p class="text-center text-danger">'.$_SESSION['message'].'</p>';
							 	unset($_SESSION['message']);
							 }
							?>
						<button type="submit" class="btn btn-primary">Connexion</button>
					</div>
					
				</form>
			</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>