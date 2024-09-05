<?php
  header("Content-Type: application/json");
  require '../init.php';

  //Check Petani login session
  $petani = new Petani();
  $petani->cekPetaniSession();

  //Create authorization function to prevent page being accessed directly

  $chat = new Chat();

  //Read the chat ids sent
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $idChats = file_get_contents('php://input');
    $ids = json_decode($idChats, true);
    
    //Check if data is valid
    if(is_array($ids)){
      if($chat->deleteChats($ids)){
        $data = array('message' => 'OK');
        echo json_encode($data);
      } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to delete data']);
      }
      // if($stmt->rowCount()){
      //   $data = array('message' => 'OK');
      //   echo json_encode($data);  
      // } else {
      //   http_response_code(500);
      //   echo json_encode(['message' => 'Failed to delete data']);
      // }

    } else {
      http_response_code(400);
      echo json_encode(['message' => 'Invalid input data']);
    }
  }