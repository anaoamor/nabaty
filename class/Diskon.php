<?php
  class Diskon{

    private $_db = null;
    private $_formItem = [];

    public function __construct(){
      $this->_db = DB::getInstance();
    }

    public function validasi($formMethod){
      $validate = new Validate($formMethod);

      if(!empty($this->getItem('id_diskon'))) {
        // echo empty($formMethod['id_diskon']);
        $this->_formItem['id_diskon'] = $this->getItem('id_diskon');
      }else{
        $this->_formItem['id_diskon'] = $this->getId();
      }

      $this->_formItem['nama_diskon'] = $validate->setRules('nama_diskon', 'Nama Diskon', [
        'sanitize' => 'string',
        'required' => true
      ]);

      $this->_formItem['deskripsi_diskon'] = $validate->setRules('deskripsi_diskon', 'Deskripsi Diskon', [
        'required' => true,
        'sanitize' => 'string'
      ]);

      $this->_formItem['besar_potongan'] = $validate->setRules('besar_potongan', 'Besar Potongan', [
        'numeric' => true,
        'min_value' => 1,
        'max_value' => 99
      ]);

      $this->_formItem['minimal_pembelian'] = $validate->setRules('minimal_pembelian', 'Minimal Pembelian', [
        'numeric' => true,
        'min_value' => 1
      ]);

      $this->_formItem['waktu_mulai'] = $validate->setRules('waktu_mulai', 'Waktu Mulai', [
        'required' => true
      ]);

      $this->_formItem['waktu_akhir'] = $validate->setRules('waktu_akhir', 'Waktu Akhir', [
        'required' => true,
        'timelessthan' => 'waktu_mulai'
      ]);

      if(!$validate->passed()){
        return $validate->getError();
      }
    }

    public function getItem($item){
      return isset($this->_formItem[$item]) ? $this->_formItem[$item] : "";
    }

    public function getId(){
      $this->_db->select('id_diskon');
      $this->_db->orderBy('id_diskon', 'DESC');
      $this->_db->limit(1);
      $number = substr($this->_db->get('diskon')[0]->id_diskon, 1, 3);
      $number++;
      return "B".str_pad($number,3,"0", STR_PAD_LEFT);
    }

    public function insert($idBibitTanaman){
      //menambah waktu 23:59:59 untuk waktu akhir
      $waktuAkhir = new DateTime($this->getItem('waktu_akhir'));
      $waktuAkhir->modify('+23 hour +59 minute +59 second');
      $newDiskon = [
        'id_diskon' => $this->getItem('id_diskon'),
        'nama_diskon' => $this->getItem('nama_diskon'),
        'deskripsi_diskon' => $this->getItem('deskripsi_diskon'),
        'besar_potongan' => ($this->getItem('besar_potongan') / 100),
        'minimal_pembelian' => $this->getItem('minimal_pembelian'),
        'waktu_mulai' => (new DateTime($this->getItem('waktu_mulai')))->format('Y-m-d'),
        'waktu_akhir' => $waktuAkhir->format('Y-m-d H:i:s'),
        'id_bibit_tanaman' => $idBibitTanaman
      ];

      return $this->_db->insert('diskon', $newDiskon);
    }

    //mendapatkan diskon terakhir dari bibit tanaman
    public function getDiskonTerakhir($idBibitTanaman){
      $this->_db->select('*');
      $this->_db->orderBy('id_diskon', 'DESC');
      $result = $this->_db->getWhereOnce('diskon', ['id_bibit_tanaman', '=', $idBibitTanaman]);
      if($result){
        foreach($result as $key => $value){
          $this->_formItem[$key] = $value;
          if($key == 'besar_potongan'){
            $this->_formItem[$key] = $value * 100;
          }
        }
      } 
    }

    //Generate diskon berdasarkan id diskon
    public function generate($idDiskon){
      $result = $this->_db->getWhereOnce('diskon', ['id_diskon', '=', $idDiskon]);
      foreach($result as $key => $value){
        $this->_formItem[$key] = $value;
        if($key == 'besar_potongan'){
          $this->_formItem[$key] = $value * 100;
        }
      }
    }

    public function update($idDiskon){
      //menambah waktu 23:59:59 untuk waktu akhir
      $waktuAkhir = new DateTime($this->getItem('waktu_akhir'));
      $waktuAkhir->modify('+23 hour +59 minute +59 second');
      $newDiskon = [
        'nama_diskon' => $this->getItem('nama_diskon'),
        'deskripsi_diskon' => $this->getItem('deskripsi_diskon'),
        'besar_potongan' => $this->getItem('besar_potongan')/100,
        'minimal_pembelian' => $this->getItem('minimal_pembelian'),
        'waktu_mulai' => (new DateTime($this->getItem('waktu_mulai')))->format('Y-m-d'),
        'waktu_akhir' => $waktuAkhir->format('Y-m-d H:i:s'),
      ];

      return $this->_db->update('diskon', $newDiskon, ['id_diskon', '=', $idDiskon]);
    }

    public function deleteDiskonBibit($idBibitTanaman){
      return $this->_db->delete('diskon', ['id_bibit_tanaman', '=', $idBibitTanaman]);
    }

    // Delete diskon berdasarkan id diskon
    public function delete($idDiskon){
      return $this->_db->delete('diskon', ['id_diskon', '=', $idDiskon]);
    }
  }