<?php
  header("Content-Type: application/json");
  require '../init.php';

  $chat = new Chat();
  $chat->validasi($_POST);
  // $chat->insert($_SESSION['id_petani']);