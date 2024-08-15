<?php
//jalankan init.php (untuk autoloader)
require '../init.php';

//buat object petani yang akan dipakai untuk memeriksa login
$petani = new Petani();
$petani->cekPetaniSession();

$db = DB::getInstance();
$db->orderBy('id_bibit_tanaman', 'DESC');
$tabelBibit = $db->get('bibit_tanaman');
require '../template/header2.php';
?>
		
		<!--Main Content-->
		<div id="main">
			<div>
				<div class="row">
					<?php
						if(!empty($tabelBibit)):
							foreach($tabelBibit as $bibit) {
					?>
						<div class="col-sm-4">
							<div class="thumb-wrapper">
								<div class="img-box">
									<img src="../<?php echo $bibit->gambar; ?>" alt="" class="img-fluid">
								</div>
								<div class="thumb-content">
									<h4><?php echo $bibit->nama; ?></h4>
									<p class="item-price"><?php echo "Rp. ".number_format($bibit->harga,0,',','.'); ?></p>
									<p class="item-discount">
										<?php 
											//Dapatkan diskon terakhir
											$db->orderBy('id_diskon', 'DESC');
											$tabelDiskon = $db->getWhereOnce('diskon', ['id_bibit_tanaman', '=', $bibit->id_bibit_tanaman]);
											$sekarang = new DateTime();
											if(!empty($tabelDiskon->besar_potongan)) {
												$waktuMulai = new DateTime($tabelDiskon->waktu_mulai);
												$waktuAkhir = new DateTime($tabelDiskon->waktu_akhir);
												if($waktuMulai < $sekarang && $waktuAkhir > $sekarang){
													echo 100 * $tabelDiskon->besar_potongan."%";
												}
											} ?>  
									</p>
								</div>
							</div>
						</div>

					<?php
						}
						endif;
					?>
					
				</div>
			</div>
		</div>

<?php
require '../template/footer2.php';
?>