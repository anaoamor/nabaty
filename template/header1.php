<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="../img/leaf3.png" type="image/png">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/style.css">
	<title>Nabaty</title>
</head>
<body>

	<!--NAVBAR-->
	<nav id="main-navbar" class="navbar navbar-expand-md navbar-light sticky-top py-0">
		<div class="container">
			<a class="navbar-brand" href="#">
				<img src="../img/leaf3.png" width="30" height="24" class="d-inline-block">Nabaty
			</a>
			<a class="nav-link p-3 text-black <?php echo basename($_SERVER['PHP_SELF']) == "tentang_kami.php" ? "active" : ""; ?>" href="tentang_kami.php">Tentang Kami</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item">
						<a class="nav-link p-3 text-black <?php echo basename($_SERVER['PHP_SELF']) == "login.php" ? "active" : ""; ?>" href="login.php">Login</a>
					</li>
					<li class="nav-item">
						<a class="nav-link p-3 text-black <?php echo basename($_SERVER['PHP_SELF']) == "daftar.php" ? "active" : ""; ?>" href="daftar.php">Daftar</a>
					</li>
				</ul>
			</div>

		</div>
	</nav>