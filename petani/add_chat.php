<?php
  header("Content-Type: application/json");
  require '../init.php';

  //Cek Petani login session
  $petani = new Petani();
  $petani->cekPetaniSession();

  //Halaman tidak bisa diakses langsung, harus ada query string pesan
  if(empty(Input::get("pesan"))){
    die('Maaf, halaman ini tidak bisa diakses langsung');
  }

  $chat = new Chat();
  $chat->validasi($_POST);
  $result = $chat->insert($_SESSION['id_petani']);
  exit($result ? "success" : "failed");