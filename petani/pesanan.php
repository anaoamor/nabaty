<?php
<?php
//Run init.php (to autoloading)
require '../init.php';

//Create petani object that will be used to authenticate
$petani = new Petani();
$petani->cekPetaniSession(); 

//create connection to database
$DB = DB::getInstance();
// $DB->select("id_pesanan, tgl_pemesanan, total_pembayaran, nama_pelanggan");
$DB->select("pesanan.id_pesanan, pesanan.tgl_pemesanan, pesanan.total_pembayaran, bibit_tanaman.nama, pelanggan.nama_pelanggan");
$DB->orderBy('id_pesanan', 'DESC');

//show the pesanan list first based on 'Perlu Dikirm' state, create an initial page variable to hold selected table base
// $pesananRecord = $DB->getInnerJoinTwoTables('pesanan', 'pelanggan', 'id_pelanggan');

$pesananRecord = $DB->getMultipleTable([['INNER JOIN', ['pesanan', 'pelanggan'], 'id_pelanggan'],
                              ['INNER JOIN', ['pesanan', 'bibit_tanaman_dipesan'], 'id_pesanan'],
                              ['INNER JOIN', ['bibit_tanaman_dipesan', 'bibit_tanaman'], 'id_bibit_tanaman']]);
var_dump($pesananRecord);

require '../template/header2.php';
?>

<!-- Main Content -->
<div id="main">
  
  <div class="container">

    <div class="row justify-content-center">
      <div class="col-auto py-2">
        <h1 class="h4 me-auto">Pesanan</h1>
      </div>
    </div>

    <div class="py-1 d-flex justify-content-end align-items-center">
      <form class="w-50 ms-4 me-1" method="get">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="ID Pesanan / Pelanggan">
          <input type="submit" class="btn btn-outline-secondary" value="Cari">
        </div>
      </form>
    </div>

    <!-- Sub menu to choose transaction option -->
    <nav class="navbar navbar-light py-4">
      <a href="#" class="nav-link text-black">Semua Pesanan</a>
      <a href="#" class="nav-link text-black">Perlu Dikirm</a>
      <a href="#" class="nav-link text-black">Dikirm</a>
      <a href="#" class="nav-link text-black">Selesai</a>
      <a href="#" class="nav-link text-black">Pembatalan</a>
      <a href="#" class="nav-link text-black">Pembelian</a>
    </nav>

    <table class="table table-striped align-middle mt-3">
      <thead>
        <tr>
          <th>ID Pesanan</th>
          <th>Waktu</th>
          <th>Produk</th>
          <th>Pelanggan</th>
          <th>Total Pembayaran</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if(!empty($pesananRecord)): 
            foreach($pesananRecord as $pesanan){
        ?>  
            <tr id="<?=$pesanan->id_pesanan?>">
              <td class="selectable"><?=$pesanan->id_pesanan?></td>
              <td class="selectable"><?=$pesanan->tgl_pemesanan?></td>
              <td>Bibit Mangga Harum Manis<br>Bibit Kokoa
              </td>
              <td class="selectable"><?=$pesanan->nama_pelanggan?></td>
              <td class="selectable">Rp<?=number_format($pesanan->total_pembayaran,2,'.','.')?></td>
              <td class="selectable">Perlu Dikirim</td>
              <td><a href="#" class="btn btn-info btn-sm text-white">Lihat</a></td>
            </tr>

        <?php
            }
        ?>
      </tbody>
    </table>
        <?php
          else :
        ?>
      </tbody>
    </table>
    <p class="text-sm-center table-empty">Kosong</p>
        <?php
          endif;
        ?>
        
      
    
  </div>

</div>
<script>
  var pesananList = document.getElementsByClassName("selectable");
  console.log(pesananList.length);
  for(let i = 0; i<pesananList.length; i++){
    pesananList[i].addEventListener("click", () => {
      window.location.href = "tampil_penjualan.php?id_pesanan="+i;
    });
    console.log(pesananList[i]);
  }
  
  //execute search query using keyword and state options
  var statusTab = "Dikirim??";
</script>
<?php
require '../template/footer2.php';
?>  