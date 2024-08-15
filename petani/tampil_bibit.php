<?php
  require '../init.php';

  //cek login session petani
  $petani = new Petani();
  $petani->cekPetaniSession();

  //halaman tidak bisa diakses langsung, harus ada query string id_bibit_tanaman
  if(empty(Input::get('id_bibit_tanaman'))){
    die('Maaf halaman ini tidak bisa diakses langsung');
  }

  //ambil semua data bibit tanaman yang akan ditampilkan
  $bibit = new BibitTanaman();
  $bibit->generate(Input::get('id_bibit_tanaman'));

  $diskon = new Diskon();
  $diskon->getDiskonTerakhir($bibit->getItem('id_bibit_tanaman'));

  require '../template/header2.php';
?>

  <!-- Main Content -->
  <div id="main">

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-auto py-4">
          <h1 class="h4 me-auto"><?php echo $bibit->getItem('nama'); ?></h1>
        </div>
      </div>
    
      <div class="row justify-content-center">
        <div class="col-8">
          
          <div class="mb-3">
            <div class="img-box">
              <img src="../<?php echo $bibit->getItem('gambar'); ?>" class="img-fluid">
            </div>
          </div>

          <?php
            if($bibit->getItem('stok') === 0){
              echo "<div class='alert alert-danger' role='alert'>Stok Bibit Tanaman telah kosong</div>";
            } else if($bibit->getItem('stok') <= 10) {
              echo "<div class='alert alert-warning' role='alert'>Stok Bibit Tanaman menipis</div>";
            }
          ?>
          

          <div class="mb-3">
            <label for="id_bibit_tanaman" class="form-label">ID</label>
            <input readonly type="text" name="id_bibit_tanaman" id="id_bibit_tanaman" class="form-control" value="<?php echo $bibit->getItem('id_bibit_tanaman'); ?>">
          </div>

          <div class="mb-3">
            <label for="deskripsi_bibit" class="form-label">Deskripsi</label>
            <textarea readonly name="deskripsi_bibit" id="deskripsi_bibit" rows="5" class="form-control"><?php echo $bibit->getItem('deskripsi_bibit'); ?></textarea>
          </div>

          <div class="mb-3">
            <label for="harga_" class="form-label">Harga Satuan</label>
            <input readonly type="text" class="form-control" name="harga" id="harga" value="<?php echo $bibit->getItem('harga'); ?>">
          </div>

          <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input readonly type="text" class="form-control" name="stok" id="stok" value="<?php echo $bibit->getItem('stok'); ?>">
          </div>

          <?php
            $sekarang = new DateTime();
            $waktuAkhir = new DateTime($diskon->getItem('waktu_akhir'));
            if(!empty($diskon->getItem('id_diskon')) && ($sekarang <= $waktuAkhir)):
          ?>

            <fieldset class="border rounded-3 p-3">
              <legend class="float-none w-auto px-3">Diskon (<span class="text-danger">*</span>Opsional)</legend>
              <div class="mb-3">
                <label for="id_diskon" class="form-label">ID</label>
                <input readonly type="text" class="form-control" name="id_diskon" id="id_diskon" value="<?php echo $diskon->getItem('id_diskon'); ?>">
              </div>
              <div class="mb-3">
                <label for="nama_diskon" class="form-label">Nama Diskon</label>
                <input readonly type="text" class="form-control" name="nama_diskon" id="nama_diskon" value="<?php echo $diskon->getItem('nama_diskon'); ?>">
              </div>
              <div clas="mb-3">
                <label for="deskripsi_diskon" class="form-labe">Deskripsi</label>
                <textarea readonly name="deskripsi_diskon" id="deskripsi_diskon" rows="5" class="form-control"><?php echo $diskon->getItem('deskripsi_diskon'); ?></textarea>
              </div>
              <div class="mb-3">
                <label for="besar_potongan" class="form-label">Besar Potongan <span class="text-muted">(%)</span></label>
                <input readonly type="text" class="form-control" name="besar_potongan" id="besar_potongan" value="<?php echo $diskon->getItem('besar_potongan'); ?>">
              </div>
              <div class="mb-3">
                <label for="minimal_pembelian" class="form-label">Minimal Pembelian</label>
                <input readonly type="text" class="form-control" name="minimal_pembelian" id="minimal_pembelian" value="<?php echo $diskon->getItem('minimal_pembelian'); ?>">
              </div>
              <div class="mb-3">
                <label for="waktu_mulai" class="form-label">Jangka Waktu</label>
                <div class="d-flex justify-content-between">
                  <input readonly type="text" class="form-control" id="waktu_mulai" name="waktu_mulai" style="width: 276px;" 
                    value="<?php $waktuMulai = new DateTime($diskon->getItem('waktu_mulai')); echo $waktuMulai->format('D, d M Y H:i'); ?>">
                  <span> s.d </span>
                  <input readonly type="text" class="form-control" id="waktu_akhir" name="waktu_mulai" style="width: 276px;" 
                    value="<?php echo $waktuAkhir->format('D, d M Y H:i'); ?>">
                </div>
              </div>
            </fieldset>

          <?php
            endif;
          ?>

          <br>
          <br>
          <div class="mb-3">
            <a href="bibit.php" class="btn btn-info text-white" style="width: 95px;">OK</a>
          </div>

        </div>
      </div>
    </div>
  </div>

<?php
  require '../template/footer2.php';
?>