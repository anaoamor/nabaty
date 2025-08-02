<?php
  //run to autoload other classes
  require '../init.php';

  //authenticate petani session
  $petani = new Petani();
  $petani->cekPetaniSession();

  require '../template/header2.php';
?>

<div id="main">
  <div id="container" class="container">
    <div class="d-flex justify-content-between mb-4">
      <h4 id="id_pesanan" class="p-6"></h4>
      <p id="tgl_pemesanan" class="p-6 fs-6"></p>
    </div>
    <table id="table-pesanan" class="table table-borderless align-middle">
      <thead>
        <th>Item</th>
        <th>Nama Produk</th>
        <th class="text-end">Jumlah</th>
        <th class="text-end">Harga</th>
        <th class="text-end">Diskon</th>
        <th class="text-end">Subtotal</th>
      </thead>
      <tbody id="table-pesanan-body">
        
      </tbody>
    </table>
    <div class="row">
      <label for="nama_pelanggan" class="col-sm-4 col-form-label">Pelanggan</label>
      <div class="col-sm-4">
        <input type="text" readonly class="form-control-plaintext" id="nama_pelanggan">
      </div>
      <div class="col-sm-4">
        <button class="btn btn-info text-white">Chat</button>
      </div>
      
    </div>
    <div class="row">
      <label for="no_telepon" class="col-sm-4 col-form-label">No Telepon</label>
      <div class="col-sm-8">
        <input type="text" readonly class="form-control-plaintext" id="no_telepon">
      </div>
    </div>
    <div class="row">
      <label for="alamat" class="col-sm-4 col-form-label">Alamat</label>
      <div class="col-sm-8">
        <input type="text" readonly class="form-control-plaintext" id="alamat">
      </div>
    </div>
    <div class="d-flex column justify-content-between">
      <label for="biaya_pengiriman" class="col-form-label">Biaya Pengiriman</label>
      <div class="d-flex column justify-content-between">
        <span class="fw-medium">Rp</span>
        <input type="text" readonly class="form-control-plaintext fw-medium text-end" id="biaya_pengiriman">
      </div>
    </div>
    <div class="d-flex column justify-content-between">
      <label for="total_harga" class="col-form-label">Total Harga</label>
      <div class="d-flex column justify-content-between">
        <span class="fw-medium">Rp</span>
        <input type="text" readonly class="form-control-plaintext fw-medium text-end" id="total_harga">
      </div>
    </div>
    <div class="row">
      <label for="kode_voucher" class="col-form-label col-sm-4">Voucher</label>
      <div class="col-sm-8">
        <input type="text" readonly class="form-control-plaintext" id="kode_voucher">
      </div>
    </div>
    <div class="d-flex column justify-content-between border-bottom">
        <label for="besar_potongan" class="col-form-label">Besar Potongan</label>
        <div class="d-flex column justify-content-between">
          <span class="fw-medium">Rp</span>
          <input type="text" readonly class="form-control-plaintext fw-medium text-end" id="besar_potongan">
        </div>
    </div>
    <div class="d-flex column justify-content-between">
      <label for="total_pembayaran" class="col-form-label">Total Pembayaran</label>
      <div class="d-flex column justify-content-between">
        <span class="fw-medium">Rp</span>
        <input type="text" readonly class="form-control-plaintext fw-medium text-end" id="total_pembayaran">
      </div>
    </div>
    <div class="row">
      <label for="status" class="col-form-label col-sm-4">Status</label>
      <div class="col-sm-8">
        <input type="text" readonly class="form-control-plaintext" id="status">
      </div>
    </div>
    <br>
    <div class="d-flex column justify-content-center">
      <button class="btn btn-info m-2 text-white">Kirim</button>
      <button class="btn btn-warning m-2 text-white">Edit</button>
      <button class="btn btn-danger m-2 text-white">Batal</button>
    </div>
  </div>
</div>

<script>
  let idPesananNode = document.getElementById("id_pesanan");
  let queryString = window.location.search;
  let urlParams = new URLSearchParams(queryString);
  let idPesanan = urlParams.get('id_pesanan');
  let tablePesananBodyNode = document.getElementById("table-pesanan-body");
  let tglPemesananNode = document.getElementById("tgl_pemesanan");
  let namaPelangganNode = document.getElementById("nama_pelanggan");
  let noTeleponNode = document.getElementById("no_telepon");
  let alamatNode = document.getElementById("alamat");
  let biayaPengirimanNode = document.getElementById("biaya_pengiriman");
  let totalHargaNode = document.getElementById("total_harga");
  let kodeVoucherNode = document.getElementById("kode_voucher");
  let besarPotonganNode = document.getElementById("besar_potongan");
  let totalPembayaranNode = document.getElementById("total_pembayaran");
  let statusNode = document.getElementById("status");  

  //Fetch detail of pesanan from DB
  const fetchPesananDetail = async (idPesanan) =>{
    try{
      let response = await fetch(`pesanan_controller.php?id_pesanan=${idPesanan}`);
      if(!response.ok) {
        throw new Error(`Terjadi kesalahan dengan kode : ${response.status}`);
      }

      let jsonData = await response.json();
      console.log(jsonData);
      for(let pesanan of jsonData){
        //status
          let statusText = "";
          switch(pesanan.status_pesanan){
            case "1":
              statusText = "Perlu Dikirim";
              break;
            case "2":
              statusText = "Dikirim";
              break;
            case "3":
              statusText = "Selesai";
              break;
            case "4":
              statusText = "Dibatalkan";
              break;
            case "5":
              statusText = "Dikembalikan";
              break;
            default:
              ;
          }
        idPesananNode.innerText = "Pesanan "+pesanan.id_pesanan;
        let date = new Date(pesanan.tgl_pemesanan);
        let dateMonth = date.getDate().toString().padStart(2,"0");
        let tglPemesananText = `${date.getDate().toString().padStart(2,"0")}-${(date.getMonth()+1).toString().padStart(2,"0")}-${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
        tglPemesananNode.innerText = tglPemesananText;
        namaPelangganNode.value = pesanan.nama_pelanggan;
        noTeleponNode.value = pesanan.no_telepon;
        alamatNode.value = pesanan.nama_alamat;
        biayaPengirimanNode.value = `${pesanan.biaya_pengiriman.toLocaleString('id-ID')}`;
        totalHargaNode.value = pesanan.total_harga.toLocaleString('id-ID');
        kodeVoucherNode.value = pesanan.kode_voucher;
        besarPotonganNode.value = `-${pesanan.besar_potongan.toLocaleString('id-ID')}`;
        totalPembayaranNode.value = pesanan.total_pembayaran.toLocaleString('id-ID');
        statusNode.value = statusText;
        let subtotalSum = 0;
        for(let item of pesanan.pesanan_item){
          let trNode = tablePesananBodyNode.insertRow();
          let itemCol = trNode.insertCell(0);
          let namaProdukCol = trNode.insertCell(1);
          let jumlahCol = trNode.insertCell(2);
          jumlahCol.classList.add("text-end");
          let hargaCol = trNode.insertCell(3);
          hargaCol.classList.add("text-end");
          let diskonCol = trNode.insertCell(4);
          diskonCol.classList.add("text-end");
          let subtotalCol = trNode.insertCell(5);
          subtotalCol.classList.add("text-end");
          itemCol.innerHTML = `<div class=\"img-box\"><img src=\"../${item.gambar}" class=\"img-fluid\" alt=\"\"></div>`;
          namaProdukCol.innerText = item.nama;
          jumlahCol.innerText = item.jumlah_pesan;
          hargaCol.innerText = `Rp ${item.harga.toLocaleString('id-ID')}`;
          diskonCol.innerText = `${item.besar_potongan*100}%`;
          subtotalCol.innerHTML = `Rp ${item.subtotal.toLocaleString('id-ID')}`;
          subtotalSum += item.subtotal;
          console.log(item.nama);
        }
        let tableResume = tablePesananBodyNode.insertRow();
        tableResume.classList.add("text-end", "border-top", "fw-medium");
        let tdTableResume0 = tableResume.insertCell(0);
        let tdTableResume1 = tableResume.insertCell(1);
        let tdTableResume2 = tableResume.insertCell(2);
        let tdTableResume3 = tableResume.insertCell(3);
        let tdTableResume4 = tableResume.insertCell(4);
        let tdTableResume5 = tableResume.insertCell(5);
        tdTableResume5.innerText = `Rp ${subtotalSum.toLocaleString('id-ID')}`;
      }
      let pesananDetail = jsonData[0];
      
    }catch(error){
      console.log(error);
    }
  }

  fetchPesananDetail(idPesanan);
</script>

<?php
  require '../template/footer2.php';
?>