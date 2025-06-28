<?php
  require "../init.php";

  $status = $_GET["status"];
  $DB = DB::getInstance();
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