<?php
  require '../init.php';

  $DB = DB::getInstance();
  $notifikasi = new Notifikasi;

  if(isset($_POST['view'])){

    if($_POST["view"] != ''){
      $notifikasi->updateSeenNotifikasi();
    }

    $output = '';

    $DB->orderBy('id_notifikasi', 'DESC');
    //untuk notifikasi navbar
    if($_POST['tipe'] == 1){
      $DB->limit(5);
    }
    
    $result = $DB->getWhere('notifikasi', ['id_penerima', '=', $_SESSION['id_petani']]);
    if($result){
      foreach($result as $recordNotifikasi){
        //menampilkan informasi waktu notifikasi
        $sekarang = new DateTime();
        $waktuNotifikasi = new DateTime($recordNotifikasi->waktu_notifikasi);
        $interval = $sekarang->diff($waktuNotifikasi);
        if($interval->format('%a') < 1){
          $tampilWaktu = $waktuNotifikasi->format('H:i');
        }else if($interval->format('%a') == 1){
          $tampilWaktu = 'Kemarin';
        }else {
          $tampilWaktu = $waktuNotifikasi->format('d M');
        }

        if($recordNotifikasi->id_pesanan != null){
          
          switch($recordNotifikasi->tipe_notifikasi){
            case 'tgl_pemesanan':
              $tampilNotifikasi = "Ada pesanan bibit tanaman baru";
            break;
  
            case 'selesai':
              $tampilNotifikasi = "Pesanan bibit tanaman diterima";
            break;
  
            case 'pengembalian':
              $tampilNotifikasi = "Pesanan bibit tanaman dikembalikan";
            break;
  
            case 'pembatalan':
              $tampilNotifikasi = "Pesanan bibit tanaman dibatalkan";
            break;
  
            case 'pembaruan':
              $tampilNotifikasi = "Pesanan bibit tanaman diperbarui";
            break;
          }

          if($_POST['tipe'] == 1){ //for dropdown notifications
            $output .= "<li><a href='detail_pesanan.php?id_pesanan={$recordNotifikasi->id_pesanan}' class='dropdown-item'><div class='d-flex justify-content-between'><h4>ID Pesanan : {$recordNotifikasi->id_pesanan}</h4><h4><small><small><small class='text-muted'>{$tampilWaktu}</small></small></small></h4></div><p style='font-size:17px;'>{$tampilNotifikasi}</p></a></li>";
          }else if($_POST['tipe'] == 2){ //for notifikasi page
            $output .= "<a href='detail_pesanan.php?id_pesanan={$recordNotifikasi->id_pesanan}' class='list-group-item list-group-item-action'><div class='d-flex w-100 justify-content-between'><h6 class='mb-1'>ID Pesanan : {$recordNotifikasi->id_pesanan}</h6><small>{$tampilWaktu}</small></div><p class='mb-1'>{$tampilNotifikasi}</p></a>";
          }
          
        }
        if($recordNotifikasi->id_bibit_tanaman != null){
          switch($recordNotifikasi->tipe_notifikasi){
            case 'menipis':
              $tampilNotifikasi = "Stok bibit tanaman menipis";
            break;

            case 'kosong':
              $tampilNotifikasi = "Stok bibit tanaman telah kosong";
            break;
          }
          
          if($_POST['tipe'] == 1){
            $output .= "<li><a href='tampil_bibit.php?id_bibit_tanaman={$recordNotifikasi->id_bibit_tanaman}' class='dropdown-item'><div class='d-flex justify-content-between'><h4>ID Bibit Tanaman : {$recordNotifikasi->id_bibit_tanaman}</h4><h4><small><small><small class='text-muted'>{$tampilWaktu}</small></small></small></h4></div><p style='font-size:17px;'>{$tampilNotifikasi}</p></a></li>";
          }else if($_POST['tipe'] == 2){
            $output .= "<a href='tampil_bibit.php?id_bibit_tanaman={$recordNotifikasi->id_bibit_tanaman}' class='list-group-item list-group-item-action'><div class='d-flex w-100 justify-content-between'><h6 class='mb-1'>ID Bibit Tanaman : {$recordNotifikasi->id_bibit_tanaman}</h6><small>{$tampilWaktu}</small></div><p class='mb-1'>{$tampilNotifikasi}</p></a>";
          }
          
        }
      }
    }else {
      $output .= "<li><a href='#' class='dropdown-item'><p style='font-size:17px;'>Tidak ada pemberitauan baru</p></a></li>";
    }
    

    $sum_notifikasi = count($DB->getWhere('notifikasi', ['seen_notifikasi', '=', 0]));

    $data = array(
      'notification' => $output,
      'unseen_notification' => $sum_notifikasi
    );

   echo json_encode($data) ;
  }