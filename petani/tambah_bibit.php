<?php
//jalankan init.php (untuk session_start dan autoloader)
require '../init.php';

//buat object petani yang akan dipakai untuk memeriksa login
$petani = new Petani();
$petani->cekPetaniSession();

//buat objek bibit yang akan dipakai untuk proses input
$bibit = new BibitTanaman();
$diskon = new Diskon();

if(!empty($_POST)){

  //beginTransaction, rollBack, dan commit
  $DB = DB::getInstance();
  $DB->beginTransaction();

  //Jika terdeteksi form disubmit, jalankan proses validasi
  $pesanErrorBibit = $bibit->validasi($_POST, $_FILES);

  //Jika terdeteksi form diskon diisi, jalankan validasi diskon
  if(!empty($_POST['nama_diskon'])){
    $pesanErrorDiskon = $diskon->validasi($_POST);
    if(empty($pesanErrorBibit) && empty($pesanErrorDiskon)){
      $insertBibit = $bibit->insert();
      $insertDiskon = $diskon->insert($bibit->getItem('id_bibit_tanaman'));
      if($insertBibit && $insertDiskon){
        $DB->commit();
      }else {
        $DB->rollBack();
      }
      header('Location:bibit.php');
    }
  }else {
    if(empty($pesanErrorBibit)){
      $insertBibit = $bibit->insert();
      if($insertBibit){
        $DB->commit();
      }else{
        $DB->rollBack();
      }
      header('Location:bibit.php');
    }
  }

}
require '../template/header2.php';
?>

    <!-- Main Content -->
    <div id="main">

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-auto py-4">
            <h1 class="h2 me-auto">Tambah</h1>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-8">
            <!-- Form untuk proses insert -->
            <form method="post" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="form_file" class="form-label">Gambar</label>
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                <input class="form-control" type="file" id="form_file" name="form_file" accept="image/*">
                <?php
                  if(!empty($pesanErrorBibit['form_file'])):
                ?>
                <small class="text-danger pesan-error"><?php echo $pesanErrorBibit['form_file']; ?></small>
                <?php
                  endif;
                ?>
              </div>
              <div class="mb-3">
                <label for="nama_bibit" class="form-label">Nama</label>
                <input type="text" class="form-control" name="nama_bibit" id="nama_bibit" value="<?php echo $bibit->getItem('nama'); ?>">
                <?php
                  if(!empty($pesanErrorBibit['nama_bibit'])):
                ?>
                <small class="text-danger pesan-error"><?php echo $pesanErrorBibit['nama_bibit']; ?></small>
                <?php
                  endif;
                ?>
              </div>
              <div class="mb-3">
                <label for="deskripsi_bibit" class="form-label">Deskripsi</label>
                <textarea name="deskripsi_bibit" id="deskripsi_bibit" class="form-control" rows="5"><?php echo $bibit->getItem('deskripsi_bibit'); ?></textarea>
                <?php
                  if(!empty($pesanErrorBibit['deskripsi_bibit'])):
                ?>
                <small class="text-danger pesan-error"><?php echo $pesanErrorBibit['deskripsi_bibit']; ?></small>
                <?php
                  endif;
                ?>
              </div>
              <div class="mb-3">
                <label for="harga" class="form-label">Harga Satuan</label>
                <input type="text" class="form-control" name="harga" id="harga" value="<?php echo $bibit->getItem('harga'); ?>">
                <?php
                  if(!empty($pesanErrorBibit['harga'])):
                ?>
                <small class="text-danger pesan-error"><?php echo $pesanErrorBibit['harga']; ?></small>
                <?php 
                  endif;
                ?>
              </div>
              <div class="mb-3">
                <label for="stok" class="form-label">Stok</label>
                <input type="text" class="form-control" name="stok" id="stok" value="<?php echo $bibit->getItem('stok'); ?>">
                <?php 
                  if(!empty($pesanErrorBibit['stok'])):
                ?>
                <small class="text-danger pesan-error"><?php echo $pesanErrorBibit['stok']; ?></small>
                <?php
                  endif;
                ?>
              </div>
              <fieldset class="border rounded-3 p-3">
                <legend class="float-none w-auto px-3">Diskon (<span class="text-danger">*</span>Opsional)</legend>
                <div class="mb-3">
                  <label for="nama_diskon" class="form-label">Nama Diskon</label>
                  <input type="text" class="form-control" name="nama_diskon" id="nama_diskon" value="<?php echo $diskon->getItem('nama_diskon'); ?>">
                  <?php
                    if(!empty($pesanErrorDiskon['nama_diskon'])):
                  ?>
                  <small class="text-danger pesan-error"><?php echo $pesanErrorDiskon['nama_diskon']; ?></small>
                  <?php
                    endif;
                  ?>
                </div>
                <div class="mb-3">
                  <label for="deskripsi_diskon" class="form-label">Deskripsi</label>
                  <textarea name="deskripsi_diskon" id="deskripsi_diskon" class="form-control" rows="5"><?php echo $diskon->getItem('deskripsi_diskon'); ?></textarea>
                  <?php
                    if(!empty($pesanErrorDiskon['deskripsi_diskon'])):
                  ?>
                  <small class="text-danger pesan-error"><?php echo $pesanErrorDiskon['deskripsi_diskon']; ?></small>
                  <?php
                    endif;
                  ?>
                </div>
                <div class="mb-3">
                  <label for="besar_potongan" class="form-label">Besar Potongan <span class="text-muted">(%)</span></label>
                  <input type="text" class="form-control" name="besar_potongan" id="besar_potongan" value="<?php echo $diskon->getItem('besar_potongan'); ?>">
                  <?php
                    if(!empty($pesanErrorDiskon['besar_potongan'])):
                  ?>
                  <small class="text-danger pesan-error"><?php echo $pesanErrorDiskon['besar_potongan']; ?></small>
                  <?php
                    endif;
                  ?>
                </div>
                <div class="mb-3">
                  <label for="minimal_pembelian" class="form-label">Minimal Pembelian</label>
                  <input type="text" class="form-control" name="minimal_pembelian" id="minimal_pembelian" value="<?php echo $diskon->getItem('minimal_pembelian'); ?>">
                  <?php
                    if(!empty($pesanErrorDiskon['minimal_pembelian'])):
                  ?>
                  <small class="text-danger pesan-error"><?php echo $pesanErrorDiskon['minimal_pembelian']; ?></small>
                  <?php
                    endif;
                  ?>
                </div>
                <div class="mb-3">
                  <label for="waktu_mulai" class="form-label">Jangka Waktu</label>
                  <div class="input-group justify-content-between">
                    <input id="waktu_mulai" name="waktu_mulai" width="276" placeholder="Mulai" value="<?php echo $diskon->getItem('waktu_mulai'); ?>">
                    <span> s.d </span> 
                    <input id="waktu_akhir" name="waktu_akhir" width="276" placeholder="Akhir" value="<?php echo $diskon->getItem('waktu_akhir'); ?>">
                    <?php
                      if(!empty($pesanErrorDiskon['waktu_mulai'])):
                    ?>
                    <small class="text-danger pesan-error"><?php echo $pesanErrorDiskon['waktu_mulai']; ?></small>
                    <?php
                      endif;
                    ?>
                    <?php
                      if(!empty($pesanErrorDiskon['waktu_akhir'])):
                    ?>
                    <small class="text-danger pesan-error"><?php echo $pesanErrorDiskon['waktu_akhir']; ?></small>
                    <?php
                      endif;
                    ?>
                  </div>
                </div>
              </fieldset>
              <br>
              <br>
              <div class="mb-3">
                <input type="submit" value="Simpan" class="btn btn-info text-white" name="submit">
                <a id="btn-cancel" class="btn btn-secondary" style="width: 95px;">Batal</a>
              </div>
              
            </form>
          </div>
        </div>
          
        
      </div>

    </div>

    <script>
      $('#waktu_mulai').datepicker({
        uiLibrary: 'bootstrap5',
        minDate: new Date()
      });
      $('#waktu_akhir').datepicker({
        uiLibrary: 'bootstrap5',
        minDate: new Date()
      });

      $(document).ready(function(){
        $('#btn-cancel').click(function(){
          var konfirmasi = window.confirm("Batal menambah bibit baru?");
          if(konfirmasi){
            window.location = 'bibit.php';
          }
        });
      });
    </script>
<?php
require '../template/footer2.php';
?>