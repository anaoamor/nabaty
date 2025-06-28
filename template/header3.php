<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="img/leaf3.png" type="image/png">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.1.1/css/all.css">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
	<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
	<title>Nabaty</title>
</head>
<body>

	<!--NAVBAR-->
	<nav id="main-navbar" class="navbar navbar-expand-md navbar-light sticky-top py-0">
		<div class="container">
			<a class="navbar-brand" href="#">
				<img src="img/leaf3.png" width="30" height="24" class="d-inline-block">Nabaty
			</a>
			<a class="nav-link p-3 text-black <?php echo basename($_SERVER['PHP_SELF']) == "tentang_kami.php" ? "active" : ""; ?>" href="#">Tentang Kami</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item">
						<a class="nav-link p-3 text-black <?php echo basename($_SERVER['PHP_SELF']) == "login.php" ? "active" : ""; ?>" href="petani/login.php">Login</a>
					</li>
					<li class="nav-item">
						<a class="nav-link p-3 text-black <?php echo basename($_SERVER['PHP_SELF']) == "daftar.php" ? "active" : ""; ?>" href="#">Daftar</a>
					</li>
				</ul>
			</div>

		</div>
	</nav>

	<div class="wrapper">