<?php
//Run init.php (to autoloading)
require '../init.php';

//Create petani object that will be used to authenticate
$petani = new Petani();
$petani->cekPetaniSession(); 

//create connection to database
$DB = DB::getInstance();
$DB->select("pesanan.id_pesanan, pesanan.tgl_pemesanan, pesanan.total_pembayaran, pelanggan.nama_pelanggan, (SELECT SUM(jumlah_pesan) 
FROM bibit_tanaman_dipesan WHERE id_pesanan = pesanan.id_pesanan ) AS item");
$DB->orderBy('id_pesanan', 'DESC');

//show the pesanan list first based on 'Perlu Dikirm' state, create an initial page variable to hold selected table base

$pesananRecord = $DB->getMultipleTableConditions([['INNER JOIN', ['pesanan', 'pelanggan'], 'id_pelanggan']], ["pesanan.status_pesanan", "=", 1], []);
var_dump($pesananRecord);

require '../template/header2.php';
?>

<!-- Main Content -->
<div id="main">
  
  <div id="container" class="container">

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
      <a href="#" data-status = "0" class="nav-link text-black">Semua Pesanan</a>
      <a href="#" data-status = "1" class="nav-link text-black">Perlu Dikirim</a>
      <a href="#" data-status = "2" class="nav-link text-black">Dikirim</a>
      <a href="#" data-status = "3" class="nav-link text-black">Selesai</a>
      <a href="#" data-status = "4" class="nav-link text-black">Pembatalan</a>
      <a href="#" data-status = "5" class="nav-link text-black">Pengembalian</a>
    </nav>

    <table class="table table-striped align-middle mt-3">
      <thead>
        <tr>
          <th>ID Pesanan</th>
          <th>Waktu</th>
          <th>Item</th>
          <th>Pelanggan</th>
          <th>Total Pembayaran</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="table-body">
        <?php
          if(!empty($pesananRecord)): 
            foreach($pesananRecord as $pesanan){
        ?>  
            <tr id="<?=$pesanan->id_pesanan?>">
              <td class="selectable"><?=$pesanan->id_pesanan?></td>
              <td class="selectable"><?=$pesanan->tgl_pemesanan?></td>
              <td><?=$pesanan->item?>
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
  //create event listener to connect a table row to a specific pesanan detail
  var pesananList = document.getElementsByClassName("selectable");
  console.log(pesananList.length);
  for(let i = 0; i<pesananList.length; i++){
    pesananList[i].addEventListener("click", () => {
      window.location.href = "tampil_penjualan.php?id_pesanan="+i;
    });
    console.log(pesananList[i]);
  }
  
  //execute search query using keyword
  //create pagination to large result from server
  
  //  display table based on state options
  var currentTab = 1;
  let tableBodyNode = document.getElementById("table-body");
  function displayPesanan(status){
    let request = new XMLHttpRequest();
    request.open("GET", "pesanan_controller.php?status=", false);
    request.send();
    let pesanan = JSON.parse(request.responseText);
    let recordsHtml = "";
    for(let record of pesanan){
      recordsHtml += `<tr id=\"${record.id_pesanan}\"><td>${record.id_pesanan}</td><td>${record.tgl_pemesanan}</td><td>${record.item}</td><td>${record.nama_pelanggan}</td><td>${record.total_pembayaran}</td><td>Perlu Dikirim</td><td><a href=\"#\" class=\"btn btn-info btn-sm text-white\">Lihat</a></td></tr>`
    }
    tableBodyNode.innerHTML += recordsHtml;
    console.log(pesanan);
    // containerDiv.innerHTML += request.responseText;
  }
  displayPesanan(0);
</script>
<?php
require '../template/footer2.php';
?>  