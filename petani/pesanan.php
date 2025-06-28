<?php
//Run init.php (to autoloading)
require '../init.php';

//Create petani object that will be used to authenticate
$petani = new Petani();
$petani->cekPetaniSession(); 

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
      <a href="#" data-status = "0" class="status-nav nav-link text-black">Semua Pesanan</a>
      <a href="#" data-status = "1" class="status-nav nav-link text-black">Perlu Dikirim</a>
      <a href="#" data-status = "2" class="status-nav nav-link text-black">Dikirim</a>
      <a href="#" data-status = "3" class="status-nav nav-link text-black">Selesai</a>
      <a href="#" data-status = "4" class="status-nav nav-link text-black">Pembatalan</a>
      <a href="#" data-status = "5" class="status-nav nav-link text-black">Pengembalian</a>
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
        
      </tbody>
    </table>
      
    <div id="empty-table-div"></div>
  </div>

</div>
<script>
  //create event listener to connect a table row to a specific pesanan detail
  
  //execute search query using keyword
  //create pagination to large result from server
  
  let statusNav = document.querySelectorAll(".status-nav"); //Status Nav
  console.log(statusNav[0]);
  //  display table based on state options
  var currentStatusTab = 1; //initial status nav
  let tableBodyNode = document.getElementById("table-body");
  let emptyTableNode = document.getElementById("empty-table-div");

  //Fetch record pesanan from server
  const fetchPesanan = async (status) =>{
    try{
      let response = await fetch(`pesanan_controller.php?status=${status}`);
      if(!response.ok){
        throw new Error(`Terjadi kesalahan dengan kode : ${response.status}`);
      }
      
      //get JSON value from response object
      let pesanan = await response.json();
      
      //Displaying the result
      let recordsHtml = "";
      if(pesanan.length == 0){
        recordsHtml = `<p class=\"text-sm-center table-empty\">Kosong</p>`;
        tableBodyNode.innerHTML = "";
        emptyTableNode.innerHTML = recordsHtml;  
      }else{
        for(let record of pesanan){
          //Column Status
          let fieldStatus = "";
          switch(record.status_pesanan){
            case "1":
              fieldStatus = "Perlu Dikirim";
              break;
            case "2":
              fieldStatus = "Dikirim";
              break;
            case "3":
              fieldStatus = "Selesai";
              break;
            case "4":
              fieldStatus = "Dibatalkan";
              break;
            case "5":
              fieldStatus = "Dikembalikan";
              break;
            default:
              ;
          }
          recordsHtml += `<tr id=\"${record.id_pesanan}\"><td>${record.id_pesanan}</td><td>${record.tgl_pemesanan}</td><td>${record.item}</td><td>${record.nama_pelanggan}</td><td>Rp${record.total_pembayaran.toLocaleString('id-ID')}</td><td>${fieldStatus}</td><td><a href=\"#\" class=\"btn btn-info btn-sm text-white\">Lihat</a></td></tr>`;
        }
        tableBodyNode.innerHTML = recordsHtml;
        emptyTableNode.innerHTML = "";
      }
      
    }catch(error){
      console.log(error);
    }
  }

  // give an event listener to status nav tab
  statusNav.forEach((status) =>{
    status.addEventListener("click", (e) => {
      e.preventDefault();
      let page = parseInt(status.getAttribute("data-status"));
      
      console.log(status.classList);
      if(page !== currentStatusTab){
        currentStatusTab = page;
        fetchPesanan(currentStatusTab);
      }
    });
  });

  fetchPesanan(currentStatusTab);
</script>
<?php
require '../template/footer2.php';
?>  