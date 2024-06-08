<?php
  //jalankan init.php (untuk session_start dan autoloader)
  require '../init.php';

  //cek login session petani
  $petani = new Petani();
  $petani->cekPetaniSession();

  //buat koneksi ke database dan hasil query di orderBy
  $DB = DB::getInstance();

  $sekarang = new DateTime();

  $DB->orderBy('id_bibit_tanaman', 'DESC');
  $daftarBibit = $DB->get('bibit_tanaman');

  include '../template/header2.php';
?>
    <!-- Main Content -->
    <div id="main">

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-auto py-4">
            <h1 class="h2 me-auto">Tambah</h1>
          </div>
        </div>

        <div class="row">
          <?php
            if(!empty($daftarBibit)){
              //Dapatkan diskon terakhir
              foreach($daftarBibit as $bibit){
                $DB->orderBy('id_diskon', 'DESC');
                $daftarDiskon = $DB->getWhereOnce('diskon', ['id_bibit_tanaman', '=', $bibit->id_bibit_tanaman]);
                if(!empty($daftarDiskon)){
                  $waktuAkhirDiskonTerakhir = new DateTime($daftarDiskon->waktu_akhir);
                }
                if(empty($daftarDiskon) || ($waktuAkhirDiskonTerakhir < $sekarang)){
          ?>
            <div class="col-sm-4">
              <div class="thumb-wrapper">
                <div class="img-box">
                  <img src="../<?php echo $bibit->gambar; ?>" alt="" class="img-fluid">
                </div>
                <div class="thumb-content">
                  <h4><?php echo $bibit->nama; ?></h4>
                  <p class="item-price"><?php echo "Rp. ".number_format($bibit->harga,0,',','.'); ?></p>
                  <a href="tambah_diskon.php?id_bibit_tanaman=<?php echo $bibit->id_bibit_tanaman; ?>" class="btn btn-primary w-25">Pilih</a>
                </div>
              </div>
            </div>
          <?php
                }
              }
            }
          ?>
        </div>
      </div>

    </div>

<?php
  require '../template/footer.php';
?>  