<?php
header("Content-Type: application/json");
require "../init.php";

//Check whether Petani login or not
$petani = new Petani();
$petani->cekPetaniSession();

//Retrieve all chat according the conversation id
if(!Input::get("id_conversation")){
  exit("error");
}

//Retrieve the chat of conversation based on id_conversation 
$DB = DB::getInstance();
$conversation = $DB->getWhereOnce("conversation", ['id_conversation', '=', $_GET['id_conversation']]);

$DB->orderBy("timestamp");
$results = $DB->getWhere("chat", ["id_conversation", "=", $_GET['id_conversation']]);

//Group all chat by timestamp
$chats = [];
$unread_id = [];
foreach($results as $result){
  $chats[date("Y/m/d", strtotime($result->timestamp))][] = $result;
  if($result->baca === 0 && $result->id_pengirim !== $_SESSION['id_petani']){
    $unread_id[] = $result->id_chat;
  }
}

//Display chat list
$unread_display = true;
$output = "<div class='chat-widget-messages'>";
foreach($chats as $date => $chatGroup){
  if(!empty($unread_id) && $chatGroup[0]->id_chat == $unread_id[0]) {
    $output .= "<div class='notif-label text-center p-sm-1 mb-1' role='alert'><span class='text-muted rounded-pill p-sm-1 px-sm-1' style='background-color:#bed6f3; font-size:0.5em;'>Pesan belum dibaca</span></div>";
    $unread_display = false;
  }
  if($date==date("Y/m/d")){
    $output .= "<p class='date'>Today</p>";
  }else{
    $output .= "<p class='date'>".date('d M', strtotime($date))."</p>";
  }
  foreach($chatGroup as $chat){
    if($unread_display && !empty($unread_id) && $chat->id_chat == $unread_id[0]){
      $output .= "<div class='notif-label text-center p-sm-1 mb-1' role='alert'><span class='text-muted rounded-pill p-sm-1 px-sm-1' style='background-color:#bed6f3; font-size:0.5em;'>Pesan belum dibaca</span></div>";
    }
    if($_SESSION['id_petani'] == $chat->id_pengirim){
      $output .= "<div data-id='{$chat->id_chat}' class='user-chat d-flex flex-row mb-0 justify-content-end'><div><p class='small p-1 mb-0 rounded-3 me-1 bg-primary text-white' style='font-size:14px;'>".htmlspecialchars($chat->pesan, ENT_QUOTES)."</p><p class='small mb-0 rounded-3 text-muted d-flex justify-content-end me-1' style='font-size:10px;'>".date('H:i', strtotime($chat->timestamp))."</p></div></div>";
    }else{
      $output .= "<div data-id='{$chat->id_chat}'  class='d-flex flex-row mb-0 justify-content-start'><div><p class='small p-1 mb-0 rounded-3 ms-1' style='background-color:#f5f6f6; font-size:14px;'>".htmlspecialchars($chat->pesan, ENT_QUOTES)."</p><p class='small mb-0 rounded-3 text-muted d-flex justify-content-end ms-1' style='font-size:10px;'>".date('H:i', strtotime($chat->timestamp))."</p></div></div>";
    }
  }
}
if(count($unread_id) != 0){
  $output .= "<p class='m-0'><span class='badge unread-badge text-muted position-absolute translate-middle-x border rounded-circle' style='background-color:#bed6f3; font-size:8px; right:0em; bottom:10em;'>".count($unread_id)."</span></p>";
}
$output .= "</div>";

$output .= "<form action='add_chat.php' method='POST' class='chat-form' >"; //autocomplete='off'
$output .= "<div class='card-footer text-muted d-flex justify-content-start align-items-center p-2'>";
$output .= "<div class='input-group align-items-center mb-0 h-25'>";
$output .= "<input type='text' class='form-control h-25 fs-6' placeholder='Message' name='pesan' required>";
$output .= "<input type='hidden' name='id_conversation' value='{$conversation->id_conversation}'>";
$output .= "<button class='btn btn-outline' style='padding-top: .55rem;'><i class='fa-solid fa-paper-plane fa-lg'></i></buton>";
$output .= "</div>";
$output .= "</div>";
$output .= "</form>";

$data = array(
  'id_conversation' => $conversation->id_conversation,
  'nama_partner' => $_SESSION['id_petani'] === $conversation->id_pengirim ? findUsername($DB,$conversation->id_penerima) : findUsername($DB, $conversation->id_pengirim),
  'output_chat' => $output
);
$jsonData = json_encode($data);
echo $jsonData;

//Find name of user
function findUsername($DB, $id_user){
  $type = substr($id_user, 0, 1); 
  $nama = "";

  switch($type){
    case 'P':
      $DB->select('nama_pelanggan');
      $nama = $DB->getWhereOnce('pelanggan', ['id_pelanggan', '=', $id_user]);
      return $nama->nama_pelanggan;
      break;
    case 'D':
      $DB->select('username');
      $nama = $DB->getWhereOnce('petani', ['id_petani', '=', $id_user]);
      return $nama->username;
      break;
    case 'K':
      $DB->select('username');
      $nama = $DB->getWhereOnce('kurir', ['id_kurir', '=', $id_user]);
      return $nama->username;
      break;
  }
  
}
?>