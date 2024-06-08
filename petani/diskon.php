<?php
  require '../init.php';

  //cek login session petani
  $petani = new Petani();
  $petani->cekPetaniSession();

  //buat koneksi ke database dan hasil query di orderBy
  $db = DB::getInstance();
  $db->orderBy('id_diskon', 'DESC');

  $sekarang = new DateTime();
  
  //Jika form terdeteksi disubmit, tampilkan hasil pencarian
  if(!empty($_GET)){
    $recordsDiskon = $db->getLeftJoinTwoTablesWhereConditionsAnd('bibit_tanaman', 'diskon', 'id_bibit_tanaman', 
    [
      ['waktu_akhir', '>', $sekarang->format('Y-m-d')],
      ['nama_diskon', 'LIKE', "%".Input::get('search')."%"]
    ]);
  }else{
    $recordsDiskon = $db->getLeftJoinTwoTablesWhere('bibit_tanaman', 'diskon', 'id_bibit_tanaman', ['waktu_akhir', '>', $sekarang->format('Y-m-d')]);
  }

  require '../template/header2.php';
?>
    
    <!-- Main Content -->
    <div id="main">
      <div>
        <div class="container">

          <div class="row justify-content-center">
            <div class="col-auto py-4">
              <h1 class="h2 me-auto">Diskon</h1>
            </div>
          </div>

          <div class="py-4 d-flex justify-content-between align-items-center">
            <a href="pilih_bibit_diskon.php" class="btn btn-primary">Tambah</a>

            <div class="w-75 d-flex justify-content-end align-items-center">
              <form class="w-50 ms-4 me-2" method="get">
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Nama Diskon">
                  <input type="submit" class="btn btn-outline-secondary" value="Cari">
                </div>
              </form>
              <hr>
              <a href="voucher_diskon.php" class="btn btn-primary">Voucher</a>
            </div>
          </div>

          <div class="row">
            <?php
              foreach($recordsDiskon as $record){
            ?>
              <div class="col-sm-4">
                <div class="thumb-wrapper">
                  <div class="img-box">
                    <img src="../<?php echo $record->gambar; ?>" class="img-fluid" alt="">
                  </div>
                  <div class="thumb-content">
                    <h4><?php echo $record->nama; ?></h4>
                    <p class="item-price"><?php echo "Rp. ".number_format($record->harga,0,',', '.'); ?></p>
                    <p class="item-discount">
                      <?php 
                        $waktuMulai = new DateTime($record->waktu_mulai);
                        $waktuAkhir = new DateTime($record->waktu_akhir);
                        if($waktuMulai->format('m') == $waktuAkhir->format('m')){
                          echo $record->nama_diskon." ".$waktuMulai->format('d')."-".$waktuAkhir->format('d M Y');
                        }else {
                          echo $record->nama_diskon." ".$waktuMulai->format('d-m-y')." s.d ".$waktuAkhir->format('d-m-y');
                        }
                      ?>
                    </p>
                    <a href="edit_diskon.php?id_diskon=<?php echo $record->id_diskon; ?>" class="btn btn-primary w-25">Edit</a>
                    <a href="hapus_diskon.php?id_diskon=<?php echo $record->id_diskon; ?>" class="btn btn-primary w-25 alert_notif">Hapus</a>
                  </div>
                </div>
              </div>

            <?php } ?>
        </div>

        </div>
      </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
    
    <!-- Jika ada session sukses, maka tampilkan sweet alert dengan pesan yang telah diset didalam session sukses -->
    <?php
      if(@$_SESSION['sukses']){
    ?>

    <script>
      Swal.fire({
        icon : 'success',
        title: 'Sukses',
        text: 'Data berhasil dihapus',
        timer: 3000,
        showConfirmButton: false
      })
    </script>

    <?php
        unset($_SESSION['sukses']);
      }
    ?>

    <!-- Konfirmasi hapus dengan sweet alert -->
    <script>
      $('.alert_notif').on('click', function(){
        var getLink = $(this).attr('href');
        Swal.fire({
          title: 'Yakin menghapus diskon?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: "Ya",
          cancelButtonColor: '#3085d6',
          cancelButtonText: "Batal"
        }).then(result => {
          if(result.isConfirmed){
            window.location.href = getLink
          }
        })
        return false;
      });
    </script>

<?php
  require '../template/footer.php';
?>