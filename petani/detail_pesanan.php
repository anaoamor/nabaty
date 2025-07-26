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
      <h4 class="p-6">Pesanan 0000027</h4>
      <p class="p-6 fs-6">20-07-2025 15:33</p>
    </div>
    <table id="table-pesanan" class="table table-borderless align-middle">
      <thead>
        <th>Item</th>
        <th>Nama Produk</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Diskon</th>
        <th>Subtotal</th>
      </thead>
      <tbody>
        <tr>
          <td><div class="img-box"><img src="../bibit_tanaman/A001.jpg" class="img-fluid" alt=""></div></td>
          <td>Mangga Harum Manis</td>
          <td>5</td>
          <td>Rp 50.000</td>
          <td>5%</td>
          <td>Rp 47.500</td>
        </tr>
        <tr>
          <td><div class="img-box"><img src="../bibit_tanaman/A001.jpg" class="img-fluid" alt=""></div></td>
          <td>Mangga Harum Manis</td>
          <td>5</td>
          <td>Rp 50.000</td>
          <td>5%</td>
          <td>Rp 47.500</td>
        </tr>
        <tr class="border-top">
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="fw-medium">Rp 95.000</td>
        </tr>
      </tbody>
    </table>
    <div class="row">
      <label for="nama_pelanggan" class="col-sm-4 col-form-label">Pelanggan</label>
      <div class="col-sm-4">
        <input type="text" readonly class="form-control-plaintext" id="nama_pelanggan" value="Tina">
      </div>
      <div class="col-sm-4">
        <button class="btn btn-info text-white">Chat</button>
      </div>
      
    </div>
    <div class="row">
      <label for="no_telepon" class="col-sm-4 col-form-label">No Telepon</label>
      <div class="col-sm-8">
        <input type="text" readonly class="form-control-plaintext" id="no_telepon" value="081200000000">
      </div>
    </div>
    <div class="row">
      <label for="alamat" class="col-sm-4 col-form-label">Alamat</label>
      <div class="col-sm-8">
        <input type="text" readonly class="form-control-plaintext" id="alamat" value="Jl. Kertamkukti No 37A, RT 01 RW 05, Talaho, Lintau, Tanah Datar">
      </div>
    </div>
    <div class="d-flex column justify-content-between">
      <label for="biaya_pengiriman" class="col-form-label">Biaya Pengiriman</label>
      <div class="">
        <input type="text" readonly class="form-control-plaintext fw-medium" id="biaya_pengiriman" value="                          Rp 20.000">
      </div>
    </div>
    <div class="d-flex column justify-content-between">
      <label for="total_harga" class="col-form-label">Total Harga</label>
      <div class="">
        <input type="text" readonly class="form-control-plaintext fw-medium" id="total_harga" value="                          Rp 115.000">
      </div>
    </div>
    <div class="d-flex column justify-content-between border-bottom">
      <label for="besar_potongan" class="col-form-label">Besar Potongan (Voucher)</label>
      <div class="d-flex column">
        <input type="text" readonly class="form-control-plaintext fw-medium" id="besar_potongan" value="(Jumat Sale)      -Rp 2.000">
      </div>
    </div>
    <div class="d-flex column justify-content-between">
      <label for="total_pembayaran" class="col-form-label">Total Pembayaran</label>
      <div>
        <input type="text" readonly class="form-control-plaintext fw-medium" id="total_pembayaran" value="                          Rp 113.000">
      </div>
    </div>
    <div class="row">
      <label for="status" class="col-form-label col-sm-4">Status</label>
      <div class="col-sm-8">
        <input type="text" readonly class="form-control-plaintext" id="status" value="Perlu Dikirim">
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

    

<?php
  require '../template/footer2.php';
?>