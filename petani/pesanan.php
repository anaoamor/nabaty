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
          <input type="text" name="search" id="search" class="form-control" placeholder="Pelanggan" onkeyup="filterPesanan()">
          <input type="submit" class="btn btn-outline-secondary" value="Cari">
        </div>
      </form>
    </div>

    <!-- Sub menu to choose transaction option -->
    <nav class="navbar nav-underline navbar-light py-4">
      <a href="#" data-status = "0" class="status-nav nav-link text-black">Semua</a>
      <a href="#" data-status = "1" class="status-nav nav-link text-black">Perlu Dikirim</a>
      <a href="#" data-status = "2" class="status-nav nav-link text-black">Dikirim</a>
      <a href="#" data-status = "3" class="status-nav nav-link text-black">Selesai</a>
      <a href="#" data-status = "4" class="status-nav nav-link text-black">Pembatalan</a>
      <a href="#" data-status = "5" class="status-nav nav-link text-black">Pengembalian</a>
    </nav>

    <table class="table table-hover table-striped align-middle mt-3" id="pesanan-table">
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

  //  display table based on state options
  let statusNav = document.querySelectorAll(".status-nav"); //Status Nav
  var currentStatusTab = 1; //initial status nav
  let tableBodyNode = document.getElementById("table-body");
  let emptyTableNode = document.getElementById("empty-table-div");
  let tableNode = document.getElementById("pesanan-table");
 
  //Fetch record pesanan from server
  const fetchPesanan = async (status) =>{
    try{
      let response = await fetch(`pesanan_controller.php?status=${status}`);
      if(!response.ok){
        throw new Error(`Terjadi kesalahan dengan kode : ${response.status}`);
      }
      
      //get JSON value from response object
      let jsonData = await response.json();
      
      //Displaying the result
      let recordsHtml = "";
      if(jsonData.length == 0){
        recordsHtml = `<p class=\"text-sm-center table-empty\">Kosong</p>`;
        emptyTableNode.innerHTML = recordsHtml;  
      }else{
        for(let record of jsonData){
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
          let date = new Date(record.tgl_pemesanan);
          let day = date.getDay();
          let tglPemesanan = "";
          switch(day){
            case 0:
              tglPemesanan += `Minggu, `;
              break;
            case 1:
              tglPemesanan += `Senin, `;
              break;
            case 2:
              tglPemesanan += `Selasa, `;
              break;
            case 3:
              tglPemesanan += `Rabu, `;
              break;
            case 4:
              tglPemesanan += `Kamis, `;
              break;
            case 5:
              tglPemesanan += `Jumat, `;
              break;
            case 6:
              tglPemesanan += `Sabtu, `;
              break;
            default:
              ;
          }
          tglPemesanan += `${date.getDate()}-${date.getMonth()+1}-${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
          
          let row = tableBodyNode.insertRow();

          let idCell = row.insertCell(0);
          let waktuCell = row.insertCell(1);
          let itemCell = row.insertCell(2);
          let pelangganCell = row.insertCell(3);
          let totalPembayaranCell = row.insertCell(4);
          let statusCell = row.insertCell(5);
          let aksiCell = row.insertCell(6);
          idCell.innerText = record.id_pesanan;
          waktuCell.innerText = tglPemesanan;
          itemCell.innerText = record.item;
          pelangganCell.innerText = record.nama_pelanggan;
          totalPembayaranCell.innerText = record.total_pembayaran.toLocaleString('id-ID');
          statusCell.innerText = fieldStatus;
          aksiCell.innerHTML = `<a href=\"detail_pesanan.php?id_pesanan=${record.id_pesanan}\" class=\"btn btn-info btn-sm text-white\">Lihat</a>`;
          row.addEventListener("click", () =>{
            window.location.href = "detail_pesanan.php?id_pesanan="+record.id_pesanan;
          });
        }
        
        emptyTableNode.innerHTML = "";
      }
      
    }catch(error){
      console.log(error);
    }
  }

  //Underlying current status nav
  const displayStatusNav = () => {
    statusNav.forEach((status) => {
      let chosenStatus = parseInt(status.getAttribute("data-status"));
      status.classList.toggle("active", chosenStatus === currentStatusTab);
    });
  }

  // give an event listener to status nav tab
  statusNav.forEach((status) =>{
    status.addEventListener("click", (e) => {
      e.preventDefault();
      let chosenStatus = parseInt(status.getAttribute("data-status"));
      
      if(chosenStatus !== currentStatusTab){
        currentStatusTab = chosenStatus;
        fetchPesanan(currentStatusTab);
        displayStatusNav();
      }
    });
    displayStatusNav();
  });

  fetchPesanan(currentStatusTab);

  let trNode = tableNode.getElementsByTagName("tr");
 
  //Filter for query search
  const filterPesanan = () => {
    let searchNode = document.getElementById("search");
    let filter = searchNode.value.toUpperCase();

    for(let i = 0; i < trNode.length; i++){
      let namaTdNode = trNode[i].getElementsByTagName("td")[3];
      
      if(namaTdNode){
        let tdValue = namaTdNode.textContent || namaTdNode.innerText;
        if(tdValue.toUpperCase().indexOf(filter) > -1){
          trNode[i].style.display = "";
        }else{
          trNode[i].style.display = "none";
        }
      }
    }
    
  }
</script>
<?php
require '../template/footer2.php';
?>  