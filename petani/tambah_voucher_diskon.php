<?php
  //autoloader dan start_session
  require '../init.php';

  //cek login session petani
  $petani = new Petani();
  $petani->cekPetaniSession();

  $voucher = new Voucher();

  //Jika form voucher disubmit
  if(!empty($_POST)){
    $pesanErrorVoucher = $voucher->validasi($_POST);

    if(empty($pesanErrorVoucher)){
      $voucher->insert();
      header('Location:voucher_diskon.php');
    }
  }

  require '../template/header2.php';
?>

    <div id="main">

      <div class="container">
        <div class="row justify-content-center">
          <div class="col-auto py-4">
            <h1 class="h4 me-auto">Tambah Voucher</h1>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-8">
            <!-- Form untuk tambah voucher -->
            <form method="post">
              <div class="mb-3">
                <label for="kode_voucher" class="form-label">Kode Voucher</label>
                <input type="text" class="form-control" name="kode_voucher" id="kode_voucher" value="<?php echo $voucher->getItem('kode_voucher'); ?>">
                <?php
                  if(!empty($pesanErrorVoucher['kode_voucher'])):
                ?>
                <small class="text-danger pesan-error"><?php echo $pesanErrorVoucher['kode_voucher']; ?></small>
                <?php
                  endif;
                ?>
              </div>
              <div class="mb-3">
                <label for="deskripsi_voucher" class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi_voucher" id="deskripsi_voucher" rows="5"><?php echo $voucher->getItem('deskripsi_voucher'); ?></textarea>
                <?php
                  if(!empty($pesanErrorVoucher['deskripsi_voucher'])):
                ?>
                <small class="text-danger pesan-error"><?php echo $pesanErrorVoucher['deskripsi_voucher']; ?></small>
                <?php
                  endif;
                ?>
              </div>
              <div class="mb-3">
                <label for="minimal_pembelian" class="form-label">Minimal Pembelian <span class="text-muted">(Rp)</span></label>
                <input type="text" class="form-control" name="minimal_pembelian" id="minimal_pembelian" value="<?php echo $voucher->getItem('minimal_pembelian'); ?>">
                <?php
                  if(!empty($pesanErrorVoucher['minimal_pembelian'])):
                ?>
                <small class="text-danger pesan-error"><?php echo $pesanErrorVoucher['minimal_pembelian']; ?></small>
                <?php
                  endif;
                ?>
              </div>
              <div class="mb-3">
                <label for="besar_potongan" class="form-label">Besar Potongan <span class="text-muted">(Rp)</span></label>
                <input type="text" class="form-control" name="besar_potongan" id="besar_potongan" value="<?php echo $voucher->getItem('besar_potongan'); ?>">
                <?php
                  if(!empty($pesanErrorVoucher['besar_potongan'])):
                ?>
                <small class="text-danger pesan-error"><?php echo $pesanErrorVoucher['besar_potongan']; ?></small>
                <?php
                  endif;
                ?>
              </div>
              <div class="mb-3">
                <label for="waktu_mulai" class="form-label">Jangka Waktu</label>
                <div class="input-group justify-content-between">
                  <input id="waktu_mulai" name="waktu_mulai" width="276" placeholder="Mulai" value="<?php echo $voucher->getItem('waktu_mulai'); ?>">
                  <span> s.d </span>
                  <input id="waktu_akhir" name="waktu_akhir" width="276" placeholder="Akhir" value="<?php echo $voucher->getItem('waktu_akhir'); ?>">
                  <?php
                    if(!empty($pesanErrorVoucher['waktu_mulai'])):
                  ?>
                  <small class="text-danger pesan-error"><?php echo $pesanErrorVoucher['waktu_mulai']; ?></small>
                  <?php
                    endif;

                    if(!empty($pesanErrorVoucher['waktu_akhir'])):
                  ?>
                  <small class="text-danger pesan-error"><?php echo $pesanErrorVoucher['waktu_akhir']; ?></small>
                  <?php
                    endif;
                  ?>
                </div>
              </div>
              <br>
              <br>
              <div class="mb-3">
                <input type="submit" value="Simpan" class="btn btn-info text-white" name="submit">
                <a id="btn-cancel" class="btn btn-secondary" style="width:95px;">Batal</a>
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
          var konfirmasi = window.confirm("Batal menambah voucher baru?");
          if(konfirmasi){
            window.location = 'voucher_diskon.php';
          }
        });
      });
    </script>

<?php
  require '../template/footer.php';
?>