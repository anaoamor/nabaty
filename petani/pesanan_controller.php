<?php
  require "../init.php";
  $DB = DB::getInstance();

  //Retrieve pesanan records based on status
  if(isset($_GET['status'])){
    $status = $_GET["status"];
    
    if($status == 0){
      $DB->select("pesanan.id_pesanan, pesanan.tgl_pemesanan, pesanan.total_pembayaran, pelanggan.nama_pelanggan, pesanan.status_pesanan, (SELECT SUM(jumlah_pesan) FROM bibit_tanaman_dipesan WHERE id_pesanan = pesanan.id_pesanan) AS item");
      $DB->orderBy("id_pesanan", "DESC");
      $pesanan = $DB->getMultipleTable([["INNER JOIN",["pesanan","pelanggan"], "id_pelanggan"]]);
      echo json_encode($pesanan);
    }else{
      $DB->select("pesanan.id_pesanan, pesanan.tgl_pemesanan, pesanan.total_pembayaran, pelanggan.nama_pelanggan, pesanan.status_pesanan, (SELECT SUM(jumlah_pesan) FROM bibit_tanaman_dipesan WHERE id_pesanan = pesanan.id_pesanan) AS item");
      $DB->orderBy("id_pesanan", "DESC");
      $pesanan = $DB->getMultipleTableConditions([["INNER JOIN",["pesanan","pelanggan"], "id_pelanggan"]],["pesanan.status_pesanan", "=", $status]);
      echo json_encode($pesanan);
    }
  }
  
  //Get pesanan detail, if query string is id_pesanan
  if(isset($_GET['id_pesanan'])){
    $id_pesanan = $_GET['id_pesanan'];

    $DB->select("pesanan.id_pesanan,pesanan.tgl_pemesanan, pesanan.biaya_pengiriman, pesanan.total_harga, pesanan.besar_potongan, pesanan.total_pembayaran, pesanan.status_pesanan, pelanggan.nama_pelanggan, pelanggan.no_telepon, alamat.nama_alamat,voucher.kode_voucher");
    $pesanan = $DB->getMultipleTableConditions([["INNER JOIN",["pesanan","pelanggan"], "id_pelanggan"],["INNER JOIN", ["pesanan", "alamat"], "id_alamat"],["LEFT JOIN", ["pesanan", "voucher"], "id_voucher"]], ["pesanan.id_pesanan", "=", $id_pesanan]);

    $DB->select("bibit_tanaman.gambar, bibit_tanaman.nama, bibit_tanaman_dipesan.jumlah_pesan,bibit_tanaman_dipesan.harga,bibit_tanaman_dipesan.subtotal,diskon.besar_potongan");
    $pesananItem = $DB->getMultipleTableConditions([["INNER JOIN", ["pesanan","bibit_tanaman_dipesan"], "id_pesanan"],["INNER JOIN",["bibit_tanaman_dipesan","bibit_tanaman"], "id_bibit_tanaman"],["LEFT JOIN",["bibit_tanaman_dipesan","diskon"],"id_diskon"]],["pesanan.id_pesanan","=",$id_pesanan]);
    $pesanan[0]->pesanan_item = $pesananItem;
    // var_dump($pesananItem);
    echo json_encode($pesanan);
  }