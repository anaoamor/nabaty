<?php
  require '../init.php';

  //cek apakah petani sudah login atau belum
  $petani = new Petani();
  $petani->cekPetaniSession();

  if(empty(Input::get('id_voucher'))){
    die('Maaf halaman ini tidak bisa diakses langsung');
  }

  $voucher = new Voucher();

  if($voucher->delete(Input::get('id_voucher'))){
    $_SESSION['sukses'] = "Voucher berhasil dihapus";
  }

  header('Location: voucher_diskon.php');