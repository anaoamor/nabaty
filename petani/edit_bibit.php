<?php
  //jalankan init.php (untuk session_start dan autoloader)
  require '../init.php';

  //cek apakah petani sudah login atau belum
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

  //jika form disubmit, lakukan validasi dan update
  if(!empty($_POST)){

    // beginTransaction, rollBack, dan commit
    $DB = DB::getInstance();
    $DB->beginTransaction();

    $pesanErrorBibit = $bibit->validasiEdit($_POST, $_FILES);

    //Jika terdeteksi form diskon disubmit, lakukan validasi
    if(isset($_POST['nama_diskon'])){
      $pesanErrorDiskon = $diskon->validasi($_POST);
      
      if(empty($pesanErrorBibit) && empty($pesanErrorDiskon)){
        $updateBibit = $bibit->update($bibit->getItem('id_bibit_tanaman'));
        $updateDiskon = $diskon->update($diskon->getItem('id_diskon'));
        if($updateBibit && $updateDiskon){
          $DB->commit();
        } else{
          $DB->rollBack();
        }
        header('Location:bibit.php');
      }
    } else{
      if(empty($pesanErrorBibit)){
        $updateBibit = $bibit->update($bibit->getItem('id_bibit_tanaman'));
        if($updateBibit){
          $DB->commit();
        }else {
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
            <h1 class="h2 me-auto">Edit</h1>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-8">
            <!-- Form untuk proses update -->
            <form method="post" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="id_bibit_tanaman" class="form-label">ID</label>
                <input type="text" disabled class="form-control" name="id_bibit_tanaman" id="id_bibit_tanaman" value="<?php echo $bibit->getItem('id_bibit_tanaman'); ?>">
              </div>
              <div class="form-group">
                <label for="form_file" class="form-label">Gambar</label>
                <small class="text-muted"><?php echo substr($bibit->getItem('gambar'), -8); ?></small>
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                <input type="file" class="form-control" id="form_file" name="form_file" accept="image/*">
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
                <label for="deskripsi_bibit" class="form-labe">Deskripsi</label>
                <textarea name="deskripsi_bibit" id="deskripsi_bibit" rows="5" class="form-control"><?php echo $bibit->getItem('deskripsi_bibit'); ?></textarea>
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

              <?php
                $sekarang = new DateTime();
                $waktuAkhir = new DateTime($diskon->getItem('waktu_akhir'));
                if(!empty($diskon->getItem('id_diskon')) && ($sekarang <= $waktuAkhir)):
              ?>
                <fieldset class="border rounded-3 p-3">
                  <legend class="float-none w-auto px-3">Diskon (<span class="text-danger">*</span>Opsional)</legend>
                  
                  <div class="mb-3">
                    <label for="id_diskon" class="form-label">ID</label>
                    <input disabled type="text" class="form-control" name="id_diskon" id="id_diskon" value="<?php echo $diskon->getItem('id_diskon'); ?>">
                  </div>
                
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
                    <textarea class="form-control" rows="5" name="deskripsi_diskon" id="deskripsi_diskon"><?php echo $diskon->getItem('deskripsi_diskon'); ?></textarea>
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
                      <input id="waktu_mulai" name="waktu_mulai" width="276" placeholder="Mulai" 
                      value="<?php $waktuMulai = new DateTime($diskon->getItem('waktu_mulai')); echo $waktuMulai->format('m/d/Y'); ?>">
                      <span> s.d </span>
                      <input id="waktu_akhir" name="waktu_akhir" width="276" placeholder="Akhir" value="<?php echo $waktuAkhir->format('m/d/Y'); ?>">
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
              
              <?php
                endif;
              ?>
    
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
          var konfirmasi = window.confirm("Batal memperbarui bibit?");
          if(konfirmasi){
            window.location = 'bibit.php';
          }
        });
      });
    </script>
    
<?php
  require '../template/footer2.php';
?>