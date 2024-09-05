<?php
  class Chat {

    private $_db =null;
    private $_formItem = [];

    public function __construct(){
      $this->_db = DB::getInstance();
    }

    public function validasi($formMethod){
      $validate = new Validate($formMethod);

      if(empty($this->getItem('id_chat'))){
        $this->_formItem['id_chat'] = $this->getId();
      }
      // echo $formMethod['pesan'];
      $this->_formItem['pesan'] = $validate->setRules('pesan', 'Pesan', [
        'required' => true,
        'sanitize' => 'string'
      ]);

      $this->_formItem['id_conversation'] = $validate->setRules('id_conversation', 'ID Conversation', [
        'required' => true
      ]);
      if(!$validate->passed()){
        return $validate->getError();
      }
    }

    public function getItem($item) {
      return isset($this->_formItem[$item]) ? $this->_formItem[$item] : "";
    }

    //To generate new id of chat
    public function getId(){
      $this->_db->select('id_chat');
      $this->_db->orderBy('id_chat', 'DESC');
      $this->_db->limit(1);
      $number = substr($this->_db->get('chat')[0]->id_chat,1,6);
      $number++;
      return "G".str_pad($number,6,"0", STR_PAD_LEFT);
    }

    public function insert($id_user){
      $newChat = [
        'id_chat' => $this->getItem('id_chat'),
        'pesan' => $this->getItem('pesan'),
        'timestamp' => (new DateTime())->format('Y-m-d H:i:s'),
        'baca' => '0',
        'id_pengirim' => $id_user,
        'id_conversation' => $this->getItem('id_conversation')
      ];

      return $this->_db->insert('chat', $newChat);
    }

    //Delete some chats using their ids
    public function deleteChats($chatIds){
      return $this->_db->deleteDataArray('chat', ['id_chat', $chatIds]);
    }
  }
?>