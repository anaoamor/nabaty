<?php
  require '../init.php';

  //cek apakah petani sudah login atau belum
  $petani = new Petani();
  $petani->cekPetaniSession();

  if(empty(Input::get('id_bibit_tanaman'))){
    die('Maaf halaman ini tidak bisa diakses langsung');
  }

  $idBibitTanaman = Input::get('id_bibit_tanaman');
  $bibit = new BibitTanaman();
  $diskon = new Diskon();

  $DB = DB::getInstance();
  $DB->beginTransaction();
  
  $deleteBibit = $bibit->delete($idBibitTanaman);
  $deleteDiskon = $diskon->deleteDiskonBibit($idBibitTanaman);
  
  if($deleteBibit && $deleteDiskon){
    $_SESSION['sukses'] = "Bibit tanaman berhasil dihapus";
    $DB->commit();
  }else {
    $DB->rollBack();
  }
  
  header('Location: bibit.php');