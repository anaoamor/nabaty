<?php
  header("Content-Type: application/json");
  require '../init.php';

  //Check Petani login session
  $petani = new Petani();
  $petani->cekPetaniSession();
  echo 'HAI';
  if(empty(Input::get("id_conversation"))){
    exit("Error");
  }