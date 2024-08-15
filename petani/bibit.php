<?php
  // Jalankan init.php (untuk autoloader)
  require('../init.php');

  //buat objek petani yang akan dipakai untuk memeriksa login
  $petani = new Petani();
  $petani->cekPetaniSession();

  //buat koneksi ke database 
  $DB = DB::getInstance();
  $DB->orderBy('id_bibit_tanaman', 'DESC');

  //Jika form pencarian tidak kosong
  if(!empty($_GET)){
    $tabelBibit = $DB->getLike('bibit_tanaman', 'nama', '%'.Input::get('search').'%');
  } else {
    //jika form kosong, ambil semua isi tabel bibit tanaman
    $tabelBibit = $DB->get('bibit_tanaman');
  }

  require '../template/header2.php';
?>

    <!-- Main Content -->
    <div id="main">

      <div class="row">
        <div class="col-12">

          <!-- Form Pencarian -->
          <div class="py-4 d-flex justify-content-between align-items-center">
            <a href="tambah_bibit.php" class="btn btn-primary">Tambah</a>

            <form class="w-50 ms-4" method="get">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Nama Bibit">
                <input type="submit" class="btn btn-outline-secondary" value="Cari">
              </div>
            </form>
          </div>

          <!-- Tabel Bibit Tanaman -->
          <?php
            if(!empty($tabelBibit)):
          ?>
            <table class="table table-striped align-middle mt-3">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Gambar</th>
                  <th>Nama</th>
                  <th>Harga (Rp)</th>
                  <th>Stok</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach($tabelBibit as $bibit){
                    echo "<tr>";
                    echo "<td> {$bibit->id_bibit_tanaman}</td>";
                    echo "<td><div class=\"img-box\"><img src=\"../{$bibit->gambar}\" class=\"img-fluid\"></div></td>";
                    echo "<td>{$bibit->nama}</td>";
                    echo "<td>".number_format($bibit->harga,0,',','.')."</td>";
                    echo "<td>{$bibit->stok}</td>";
                    echo "<td>";
                    echo "<a href=\"tampil_bibit.php?id_bibit_tanaman={$bibit->id_bibit_tanaman}\" class=\"btn btn-info btn-sm text-white\">Tampil</a> ";
                    echo "<a href=\"edit_bibit.php?id_bibit_tanaman={$bibit->id_bibit_tanaman}\" class=\"btn btn-secondary btn-sm text-white\">Edit</a> ";
                    echo "<a href=\"hapus_bibit.php?id_bibit_tanaman={$bibit->id_bibit_tanaman}\" class=\"btn btn-danger btn-sm alert_notif\">Hapus</a>";
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
    <!-- Jika ada session sukses, maka tampilkan sweet alert dengan pesan yang telah diset didalam session sukses -->
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
        unset ($_SESSION['sukses']); 
      }
    ?>

    <!-- Konfirmasi hapus dengan sweet alert -->
    <script>
      $('.alert_notif').on('click', function(){
        var getLink = $(this).attr('href');
        Swal.fire({
          title: "Yakin menghapus bibit?",
          icon : 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          confirmButtonText: 'Ya',
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
require '../template/footer2.php';
?>