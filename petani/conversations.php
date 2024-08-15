<?php
  header("Content-Type: application/json");
  require "../init.php.";
  
  //Check Petani whether login or not
  $petani = new Petani();
  $petani->cekPetaniSession();

  //Unread chat
  $unread = 0;

  //Retrieve all the conversations associated with the user along with the most recent message
  $DB = DB::getInstance();
  $conversations = $DB->getQuery("SELECT conversation.*, (SELECT pesan FROM chat WHERE id_conversation = conversation.id_conversation ORDER BY timestamp DESC LIMIT 1) AS pesan, (SELECT COUNT(*) FROM chat WHERE id_conversation = conversation.id_conversation AND baca = 0 AND id_pengirim != ?) as unread, (SELECT timestamp FROM chat WHERE id_conversation = conversation.id_conversation ORDER BY timestamp DESC LIMIT 1) as timestamp FROM conversation WHERE conversation.id_pengirim = ? OR conversation.id_penerima = ? GROUP BY conversation.id_conversation;", [$_SESSION['id_petani'],$_SESSION['id_petani'], $_SESSION['id_petani']]);
  
  //Sort the conversatins by the most recent message date
  usort($conversations, function($a, $b){
    $timestamp_a = strtotime($a->timestamp);
    $timestamp_b = strtotime($b->timestamp);
    return $timestamp_b - $timestamp_a;
  });

  //Return html output
  $output = "<div class='chat-widget-conversations'>";
  foreach($conversations as $conversation){
    $nama_pengirim = findUsername($DB, $conversation->id_pengirim);
    $nama_penerima = findUsername($DB, $conversation->id_penerima);
    $output .= "<a href='#' class='chat-widget-conversation' data-id='{$conversation->id_conversation}'>";
    $output .= "<div class='icon' style='background-color:".color_from_string($conversation->id_pengirim != $_SESSION['id_petani'] ? $nama_pengirim : $nama_penerima).";'>".substr($conversation->id_pengirim != $_SESSION['id_petani'] ? $nama_pengirim : $nama_penerima, 0, 1)."</div>";
    $output .= "<div class='details'>";
    $output .= "<div class='title'>".htmlspecialchars($conversation->id_pengirim != $_SESSION['id_petani'] ? $nama_pengirim : $nama_penerima, ENT_QUOTES)."</div>";
    $output .= "<div class='chat'>".htmlspecialchars($conversation->pesan, ENT_QUOTES)."</div>";
    $output .= "</div>";
    $output .= "<div class='date'>";
    $output .=  (date('Y/m/d') === date('Y/m/d', strtotime($conversation->timestamp))) ? date('H:i', strtotime($conversation->timestamp)) : date('d/m/y', strtotime($conversation->timestamp));
    $output .= "<br><span class='badge bg-primary bg-opacity-50 rounded-circle'>";
    $output .= $conversation->unread !== 0 ? $conversation->unread : "";
    $output .= "</span>";
    $output .= "</div>";
    $output .= "</a>";
    $unread += $conversation->unread;
  }
  $output .= "</div>";

  //returning data
  $data = array(
    'unread' => $unread,
    'output_conversation' => $output
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

  // The following function will be used to assign a unique icon color to our users
  function color_from_string($string) {
    // The list of hex colors
    $colors = ['#34568B','#FF6F61','#6B5B95','#88B04B','#F7CAC9','#92A8D1','#955251','#B565A7','#009B77','#DD4124','#D65076','#45B8AC','#EFC050','#5B5EA6','#9B2335','#DFCFBE','#BC243C','#C3447A','#363945','#939597','#E0B589','#926AA6','#0072B5','#E9897E','#B55A30','#4B5335','#798EA4','#00758F','#FA7A35','#6B5876','#B89B72','#282D3C','#C48A69','#A2242F','#006B54','#6A2E2A','#6C244C','#755139','#615550','#5A3E36','#264E36','#577284','#6B5B95','#944743','#00A591','#6C4F3D','#BD3D3A','#7F4145','#485167','#5A7247','#D2691E','#F7786B','#91A8D0','#4C6A92','#838487','#AD5D5D','#006E51','#9E4624'];
    // Find color based on the string
    $colorIndex = hexdec(substr(sha1($string), 0, 10)) % count($colors);
    // Return the hex color
    return $colors[$colorIndex];
  }
?>