<?php
  require '../init.php';

  //cek apakah petani sudah login atau belum
  $petani = new Petani();
  $petani->cekPetaniSession();

  if(empty(Input::get('id_diskon'))){
    die('Maaf halaman ini tidak bisa diakses langsung');
  }

  $diskon = new Diskon();

  if($diskon->delete(Input::get('id_diskon'))){
    $_SESSION['sukses'] = "Diskon berhasil dihapus";
  }

  header('Location: diskon.php');