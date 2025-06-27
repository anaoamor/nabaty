<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="../img/leaf3.png" type="image/png">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/style.css">
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
			<a class="navbar-brand" href="home.php">
				<img src="../img/leaf3.png" width="30" height="24" class="d-inline-block">Nabaty
			</a>
			<a class="nav-link p-3 text-black <?php echo basename($_SERVER['PHP_SELF']) == "tentang_kami.php" ? "active" : ""; ?>" href="tentang_kami.php">Tentang Kami</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item dropdown">
						<!-- <a class="nav-link p-3 text-black" href="notifikasi.php">
							<img src="../img/bell.png" width="16" height="16">Notifikasi</a>
						 -->
						<a href="#" class="nav-link p-3 text-black dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="count alert-danger badge" style="border-radius:25px;"></span> Notifikasi</a>
						<ul class="dropdown-menu dropdown-notifikasi">
						</ul>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link p-3 text-black dropdown-toggle <?php echo basename($_SERVER['PHP_SELF']) == 'profil_petani.php' ? 'active' : ''; ?>" href="profil_petani.php" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="../img/user.png" width="16" height="16">Petani</a>
						<ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
							<!-- <li><a class="dropdown-item" href="#">Petani</a></li> -->
							<li><a class="dropdown-item" href="#">Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>

		</div>
	</nav>

	<div class="wrapper">

		<!--Sidebar-->
		<nav id="sidebar">

			<ul class="list-unstyled components">
				<li class="<?php echo substr(basename($_SERVER['PHP_SELF']), -9) == 'bibit.php' ? 'active' : ''; ?>">
					<a href="bibit.php">Produk</a>
				</li>
				<li class="<?php echo substr(basename($_SERVER['PHP_SELF']), -10) == 'diskon.php' ? 'active' : ''; ?>">
					<a href="diskon.php">Diskon</a>
				</li>
				<li class="menu-notifikasi <?php echo substr(basename($_SERVER['PHP_SELF']), -14) == 'notifikasi.php' ? 'active' : ''; ?>">
					<a href="notifikasi.php">Notifikasi</a>
				</li>
				<li class="<?php echo substr(basename($_SERVER['PHP_SELF']), -11) == 'pesanan.php' ? 'active' : ''?>">
					<a href="pesanan.php">Transaksi Penjualan</a>
				</li>
				<li>
					<a href="#">Laporan</a>
				</li>
				<li>
					<a href="#">Daftar Pembeli</a>
				</li>
				<li>
					<a href="#">Profil Nabaty</a>
				</li>
			</ul>
		</nav>