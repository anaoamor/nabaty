<?php
  class Notifikasi{

    private $_db = null;
    private $_formItem = [];

    public function __construct(){
      $this->_db = DB::getInstance();
    }

    public function updateSeenNotifikasi(){
      $seenNotifikasi = ['seen_notifikasi' => 1];
      return $this->_db->update('notifikasi', $seenNotifikasi, ['seen_notifikasi', '=', '0']);
    }
  }