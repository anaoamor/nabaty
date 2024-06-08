<?php
class BibitTanaman {
  private $_db = null;
  private $_formItem = [];

  public function __construct(){
    $this->_db = DB::getInstance();
  }

  public function validasi($formMethod, $formFile){
    $validateFile = new ValidateFile($formFile);
    $validate = new Validate($formMethod);

    $this->_formItem['file_tmp'] = $validateFile->setRules('form_file', 'Gambar', [
      'error' => 0,
      'size' => 10485760,
      'image' => true
    ]);
    
    $this->_formItem['file_nama'] = $formFile['form_file']['name'];

    if(!isset($formMethod['id_bibit_tanaman'])){
      $this->_formItem['id_bibit_tanaman'] = $this->getId();
    }

    $this->_formItem['nama'] = $validate->setRules('nama_bibit', 'Nama Bibit', [
      'required' => true,
      'sanitize' => 'string'
    ]);

    $this->_formItem['deskripsi_bibit'] = $validate->setRules('deskripsi_bibit', 'Deskripsi Bibit', [
      'required' => true,
      'sanitize' => 'string'
    ]);

    $this->_formItem['harga'] = $validate->setRules('harga', 'Harga Bibit', [
      'numeric' => true,
      'min_value' => 0
    ]);

    $this->_formItem['stok'] = $validate->setRules('stok', 'Stok Bibit', [
      'numeric' => true,
      'min_value' => 0
    ]);

    if(!$validateFile->passed() || !$validate->passed()){
      return array_merge($validateFile->getError(), $validate->getError());
    }
  }

  public function validasiEdit($formMethod, $formFile){
    $validate = new Validate($formMethod);

    $this->_formItem['nama'] = $validate->setRules('nama_bibit', 'Nama Bibit', [
      'required' => true,
      'sanitize' => 'string'
    ]);

    $this->_formItem['deskripsi_bibit'] = $validate->setRules('deskripsi_bibit', 'Deskripsi Bibit', [
      'required' => true,
      'sanitize' => 'string'
    ]);

    $this->_formItem['harga'] = $validate->setRules('harga', 'Harga Bibit', [
      'numeric' => true,
      'min_value' => 0
    ]);

    $this->_formItem['stok'] = $validate->setRules('stok', 'Stok Bibit', [
      'numeric' => true,
      'min_value' => 0
    ]);

    if(!empty($formFile['form_file']['name'])){
      $validateFile = new ValidateFile($formFile);

      $this->_formItem['file_tmp'] = $validateFile->setRules('form_file', 'Gambar', [
        'error' => 0,
        'size' => 10485760,
        'image' => true
      ]);
      $this->_formItem['file_nama'] = $formFile['form_file']['name'];

      if(!$validate->passed() || !$validateFile->passed()){
        return array_merge($validate->getError(), $validateFile->getError());
      }
    } else{

      if(!$validate->passed()){
        return $validate->getError();
      }
    }
  }

  public function getItem($item){
    return isset($this->_formItem[$item]) ? $this->_formItem[$item] : "";
  }

  public function getId(){
    $this->_db->select("id_bibit_tanaman");
    $this->_db->orderBy("id_bibit_tanaman", "DESC");
    $this->_db->limit(1);
    $number = substr($this->_db->get('bibit_tanaman')[0]->id_bibit_tanaman, 1, 3);
    $number++;
    return "A".str_pad($number,3,"0", STR_PAD_LEFT);
  }

  public function insert(){
    $file_info = pathinfo($this->getItem('file_nama'));
    $ext_file = $file_info['extension'];
    $newBibit = [ 
      'id_bibit_tanaman' => $this->getItem('id_bibit_tanaman'),
      'nama' => $this->getItem('nama'),
      'deskripsi_bibit' => $this->getItem('deskripsi_bibit'),
      'gambar' => "bibit_tanaman/{$this->getItem('id_bibit_tanaman')}.{$ext_file}",
      'harga' => $this->getItem('harga'),
      'stok' => $this->getItem('stok')
    ];

    move_uploaded_file($this->getItem('file_tmp'), "../".$newBibit['gambar']);
    return $this->_db->insert('bibit_tanaman', $newBibit);
  }

  public function generate($idBibitTanaman){
    $result = $this->_db->getWhereOnce('bibit_tanaman', ['id_bibit_tanaman', '=', $idBibitTanaman]);
    foreach($result as $key => $value){
      $this->_formItem[$key] = $value;
    }
  }

  public function update($idBibitTanaman){
    if(empty($this->getItem('file_tmp'))){
      $newBibit = [
        'nama' => $this->getItem('nama'),
        'deskripsi_bibit' => $this->getItem('deskripsi_bibit'),
        'harga' => $this->getItem('harga'),
        'stok' => $this->getItem('stok')
      ];
    }else {
      unlink("../".$this->getItem('gambar'));
      $file_info = pathinfo($this->getItem('file_nama'));
      $ext_file = $file_info['extension'];
      $newBibit = [
        'nama' => $this->getItem('nama'),
        'deskripsi_bibit' => $this->getItem('deskripsi_bibit'),
        'gambar' => "bibit_tanaman/{$this->getItem('id_bibit_tanaman')}.{$ext_file}",
        'harga' => $this->getItem('harga'), 
        'stok' => $this->getItem('stok')
      ];
      move_uploaded_file($this->getItem('file_tmp'), "../{$newBibit['gambar']}");
    }

    return $this->_db->update('bibit_tanaman', $newBibit, ['id_bibit_tanaman', '=', $idBibitTanaman]);
  }

  public function delete($idBibitTanaman){
    $this->generate($idBibitTanaman);
    unlink("../".$this->getItem('gambar'));
    return $this->_db->delete('bibit_tanaman', ['id_bibit_tanaman', '=', $idBibitTanaman]);
  }
}