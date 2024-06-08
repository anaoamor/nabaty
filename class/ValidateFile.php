<?php
  class ValidateFile{
    private $_errors = array();
    private $_formFile = null;

    public function __construct($formFile){
      $this->_formFile = $formFile;
    }

    public function setRules($file, $fileName, $rules){
      
      $file_tmp = $this->_formFile[$file]["tmp_name"];

      foreach($rules as $rule => $ruleValue){
        switch($rule){
          case 'error':
            $upload_error = $this->_formFile[$file][$rule];
            if($upload_error !== 0){
              // file gagal diupload siapkan pesan error
              $arr_upload_error = array(
                1 => "Ukuran $fileName melewati batas maksimal",
                2 => "Ukuran $fileName melewati batas maksimal 10MB",
                3 => "$fileName hanya terupload sebagian",
                4 => "Tidak ada $fileName yang terupload",
                5 => "Server Error", 
                6 => "Server Error",
                7 => "Server Error",
                8 => "Server Error"
              );

              $this->_errors[$file] = $arr_upload_error[$upload_error];
            }
          break;

          case 'size':
            if($this->_formFile[$file][$rule] > $ruleValue){
              $this->_errors[$file] = "Ukuran $fileName melebihi 10MB";
            }
          break;

          case 'image':
            if(getimagesize($this->_formFile[$file]["tmp_name"]) === FALSE){
              $this->_errors[$file] = "Mohon upload file gambar (png atau jpg)";
            }
          break;
        }

        //cek jika sudah ada error di item yang sama, langsung keluar dari looping
        if(!empty($this->_errors[$file])){
          break;
        }
      }

      return $file_tmp;
    }

    public function getError(){
      return $this->_errors;
    }

    public function passed(){
      return empty($this->_errors) ? true : false;
    }
  }