<?php
  header("Content-Type: application/json");
  require '../init.php';

  //Check Petani login session
  $petani = new Petani();
  $petani->cekPetaniSession();
  
  //Ensure that the page isn't accessed directly
  if(empty(Input::get("id_conversation"))){
    exit("Error");
  }

  //Make sure the user is associated with the conversation
  $DB = DB::getInstance();
  $idConversation = Input::get("id_conversation");
  $conversation = $DB->getQuery("SELECT id_conversation FROM conversation WHERE id_conversation = ? AND (id_pengirim = ? OR id_penerima = ?)", [$idConversation, $_SESSION["id_petani"], $_SESSION["id_petani"]]);
  if(!$conversation){
    //The user isn't not associated with the conversation, output error
    exit('error');
  }
  
  //Update the readed chat
  $chat = new Chat();
  exit($chat->updateRead($idConversation, $_SESSION['id_petani']) ? 'success' : 'failed');