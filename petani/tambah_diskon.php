<?php
  //jalankan init.php (untuk autoloader dan start_session)
  require '../init.php';

  //buat objek petani untuk memeriksa login
  $petani = new Petani();
  $petani->cekPetaniSession();

  //halaman tidak bisa diakses langsung, harus ada query string id_bibit_tanama
  if(empty(Input::get('id_bibit_tanaman'))){
    die('Maaf halaman ini tidak bisa diakses langusng');
  }

  $bibit = new BibitTanaman();
  $bibit->generate(Input::get('id_bibit_tanaman'));

  $diskon = new Diskon();

  if(!empty($_POST)){
    $pesanErrorDiskon = $diskon->validasi($_POST);
    if(empty($pesanErrorDiskon)){
      $diskon->insert($bibit->getItem('id_bibit_tanaman'));
      header('Location: diskon.php');
    }
  }

  require '../template/header2.php';
?>
    <!-- Maint Content -->
    <div id="main">

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-auto py-4">
            <h1 class="h4 me-auto">Tambah Diskon</h1>
          </div>
        </div> 
      </div>

      <div class="row justify-content-center">
        <div class="col-8">
          <div class="img-box">
            <img src="../<?php echo $bibit->getItem('gambar'); ?>" class="img-fluid" alt="">
          </div>
          <fieldset class="border rounded-3 p-3">
            <legend class="float-none w-auto px-3">Bibit Tanaman</legend>
            <div class="mb-3 form-group row">
              <label class="col-sm-3 col-form-label">ID</label>
              <div class="col-sm-9">
                <label class="col-form-label"><?php echo $bibit->getItem('id_bibit_tanaman'); ?></label>
              </div>
            </div>
            <div class="mb-3 form-group row">
              <label class="col-sm-3 col-form-label">Nama</label>
              <div class="col-sm-9">
                <label class="col-form-label"><?php echo $bibit->getItem('nama'); ?></label>
              </div>
            </div>
            <div class="mb-3 form-group row">
              <label class="col-sm-3 col-form-label">Deskripsi</label>
              <div class="col-sm-9">
                <label class="col-form-label"><?php echo $bibit->getItem('deskripsi_bibit'); ?></label>
              </div>
            </div>
            <div class="mb-3 form-group row">
              <label class="col-sm-3 col-form-label">Harga Satuan</label>
              <div class="col-sm-9">
                <label class="col-form-label"><?php echo $bibit->getItem('harga'); ?></label>
              </div>
            </div>
            <div class="mb-3 form-group row">
              <label class="col-sm-3 col-form-label">Stok</label>
              <div class="col-sm-9">
                <label class="col-form-label"><?php echo $bibit->getItem('stok'); ?></label>
              </div>
            </div>
          </fieldset>
          <br>
          <br>
          <form method="post">
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
              <textarea class="form-control" name="deskripsi_diskon" id="deskripsi_diskon" rows="5"><?php echo $diskon->getItem('deskripsi_diskon'); ?></textarea>
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
          var konfirmasi = window.confirm("Batal menambah diskon baru?");
          if(konfirmasi){
            window.location = 'pilih_bibit_diskon.php';
          }
        })
      });
    </script>

<?php
  require '../template/footer.php';
?>