<?php
//jalankan init.php (untuk autoloader)
require '../init.php';

//buat object petani yang akan dipakai untuk proses login
$petani = new Petani();

if(!empty($_POST)) {
	//Jika terdeteksi form $_POST disubmit, jalankan proses validasi
	$pesanError = $petani->validasiLogin($_POST);
	if (empty($pesanError)) {
		//jika tidak ada error, proses login user
		$petani->login();
	}
}

require '../template/header1.php';
?>
	
	<div class="container pt-5">
		
		<div class="row">
			<div class="col-10 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto">
				<div class="card">
					<div class="card-header">
						<h4>Login</h4>
					</div>

					<div class="card-body">
						<form method="post" autocomplete="off">
							<div class="mb-3">
								<label for="email" class="form-label">Email</label>
								<input type="text" class="form-control" name="email" id="email" value="<?php echo $petani->getItem('email'); ?>">
								<?php
									if(!empty($pesanError['email'])):
								?>
								<small class="text-danger pesan-error"><?php echo $pesanError['email']; ?></small>
								<?php
									endif;
								?>
							</div>

							<div class="mb-3">
								<label for="password" class="form-label">Password</label>
								<input type="password" class="form-control" name="password" id="password">
								<?php
									if(!empty($pesanError['password'])):
								?>
								<small class="text-danger pesan-error"><?php echo $pesanError['password']; ?></small>
								<?php
									endif;
								?>
							</div>

							<div class="d-grid">
								<input type="submit" class="btn btn-info text-white" value="Login">
							</div>

						</form>

					</div>
				</div>
			</div>
		</div>


	</div>

<?php
require '../template/footer.php';
?>