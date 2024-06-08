<?php
  //autoloader dan session_start
  require '../init.php';

  //buat objek petani untuk dipakai memeriksa login
  $petani = new Petani();
  $petani->cekPetaniSession();

  //buat koneksi ke database
  $DB = DB::getInstance();
  $DB->orderBy('id_voucher', 'DESC');

  //Jika form pencarian tidak kosong
  if(!empty($_GET)){
    $recordsVoucher = $DB->getLike('voucher', 'kode_voucher', '%'.Input::get('search').'%');
  } else{
    //jika form kosong, ambil semua isi tabel voucher
    $recordsVoucher = $DB->get('voucher');
  }

  require '../template/header2.php';
?>

    <div id="main">

      <div class="row">
        <div class="col-12">

          <!-- Form Pencarian -->
          <div class="py-4 d-flex justify-content-between align-items-center">
            <a href="tambah_voucher_diskon.php" class="btn btn-primary">Tambah</a>

            <form class="w-50 ms-4" method="get">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Kode Voucher">
                <input type="submit" class="btn btn-outline-secondary" value="Cari">
              </div>
            </form>
          </div>

          <!-- Tabel Voucher -->
          <?php
            if(!empty($recordsVoucher)):
          ?>

          <table class="table table-striped align-middle mt-3">
            <thead>
              <tr>
                <th>ID</th>
                <th>Kode Voucher</th>
                <!-- <th>Deskripsi</th> -->
                <th>Minimal Pembelian (Rp)</th>
                <th>Besar Potongan (Rp)</th>
                <th>Jangka Waktu</th>
                <th class="col-2">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach($recordsVoucher as $voucher){
                  echo "<tr>";
                  echo "<td> {$voucher->id_voucher} </td>";
                  echo "<td> {$voucher->kode_voucher}</td>";
                  // echo "<td> {$voucher->deskripsi_voucher}</td>";
                  echo "<td>".number_format($voucher->minimal_pembelian,0,',','.')."</td>";
                  echo "<td>".number_format($voucher->besar_potongan,'0',',','.')."</td>";
                  $waktuMulai = new DateTime($voucher->waktu_mulai);
                  $waktuAkhir = new DateTime($voucher->waktu_akhir);
                  if($waktuMulai->format('m') == $waktuAkhir->format('m')){
                    echo "<td>".$waktuMulai->format('d')."-".$waktuAkhir->format('d M Y')."</td>";
                  }else {
                    echo "<td>".$waktuMulai->format('d-m-Y')." s.d ".$waktuAkhir->format('d-m-Y')."</td>";
                  }
                  echo "<td>";
                  echo "<a href='edit_voucher_diskon.php?id_voucher={$voucher->id_voucher}' class='btn btn-secondary btn-sm text-white' style='width: 70px;'>Edit</a> ";
                  echo "<a href='hapus_voucher_diskon.php?id_voucher={$voucher->id_voucher}' class='btn btn-danger btn-sm alert_notif'>Hapus</a>";
                  echo "</td>";
                  echo "</tr>";
                }
              ?>
            </tbody>
          </table>

          <?php
            endif;
          ?>

        </div>
      </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
    <!-- Jika ada session sukses, maka tampilkan alert dengan pesan yang telah diset dari session sukses -->
    <?php
      if(@$_SESSION['sukses']){
    ?>

    <script>
      Swal.fire({
        icon: 'success',
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

    <!-- Konfirmasi hapus voucher dengan sweet alert -->
    <script>
      $('.alert_notif').on('click', function(){
        var getLink = $(this).attr('href');
        Swal.fire({
          title: 'Yakin menghapus voucher?',
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