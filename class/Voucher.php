<?php
  class Voucher{

    private $_db = null;
    private $_formItem = [];

    public function __construct(){
      $this->_db = DB::getInstance();
    }

    public function validasi($formMethod){
      $validate = new Validate($formMethod);

      $this->_formItem['id_voucher'] = $this->getItem('id_voucher') ? $this->getItem('id_voucher') : $this->getId();

      $this->_formItem['kode_voucher'] = $validate->setRules('kode_voucher', 'Kode Voucher', [
        'sanitize' => 'string',
        'required' => true
      ]);

      $this->_formItem['deskripsi_voucher'] = $validate->setRules('deskripsi_voucher', 'Deskripsi Voucher', [
        'sanitize' => 'string',
        'required' => true
      ]);

      $this->_formItem['minimal_pembelian'] = $validate->setRules('minimal_pembelian', 'Minimal Pembelian', [
        'numeric' => true,
        'min_value' => 0
      ]);

      $this->_formItem['besar_potongan'] = $validate->setRules('besar_potongan', 'Besar Potongan', [
        'numeric' => true,
        'min_value' => 0
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
      $this->_db->select('id_voucher');
      $this->_db->orderBy('id_voucher', 'DESC');
      $this->_db->limit(1);
      $number = substr($this->_db->get('voucher')[0]->id_voucher, 1, 3);
      $number++;
      return "C".str_pad($number,3,"0", STR_PAD_LEFT);
    }

    public function generate($idVoucher){
      $result = $this->_db->getWhereOnce('voucher', ['id_voucher', '=', $idVoucher]);
      foreach($result as $key => $value){
        $this->_formItem[$key] = $value;
      }
    }

    public function insert(){
      //menambah waktu 23:59:59 untuk waktu akhir
      $waktuAkhir = new DateTime($this->getItem('waktu_akhir'));
      $waktuAkhir->modify('+23 hour +59 minute +59 second');
      $newVoucher = [
        'id_voucher' => $this->getItem('id_voucher'),
        'kode_voucher' => $this->getItem('kode_voucher'),
        'deskripsi_voucher' =>$this->getItem('deskripsi_voucher'),
        'minimal_pembelian' => $this->getItem('minimal_pembelian'),
        'besar_potongan' => $this->getItem('besar_potongan'),
        'waktu_mulai' => (new DateTime($this->getItem('waktu_mulai')))->format('Y-m-d'),
        'waktu_akhir' => $waktuAkhir->format('Y-m-d H:i:s')
      ];

      return $this->_db->insert('voucher', $newVoucher);
    }

    public function update($idVoucher){
      //menambah waktu 23:59:59 untuk waktu akhir
      $waktuAkhir = new DateTime($this->getItem('waktu_akhir'));
      $waktuAkhir->modify('+23 hour +59 minute +59 second');
      $newVoucher = [
        'kode_voucher' => $this->getItem('kode_voucher'),
        'deskripsi_voucher' => $this->getItem('deskripsi_voucher'),
        'minimal_pembelian' => $this->getItem('minimal_pembelian'),
        'besar_potongan' => $this->getItem('besar_potongan'),
        'waktu_mulai' => (new DateTime($this->getItem('waktu_mulai')))->format('Y-m-d'),
        'waktu_akhir' => $waktuAkhir->format('Y-m-d H:i:s')
      ];

      return $this->_db->update('voucher', $newVoucher, ['id_voucher', '=', $idVoucher]);
    }

    public function delete($idVoucher){
      return $this->_db->delete('voucher', ['id_voucher', '=', $idVoucher]);
    }
  }