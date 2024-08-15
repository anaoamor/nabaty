<?php
  require '../init.php';

  require '../template/header2.php';
?>

    <div id="main">
      <div class="row justify-content-center">
        <div class="col-8">
          <div class="list-group list-notifikasi shadow-sm">
            <!-- <a href="#" class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">ID Pesanan : 0000001</h6>
                <small>03:12</small>
              </div>
              <p class="mb-1">Ada pesanan bibit tanaman baru</p>
            </a>
            <a href="#" class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">ID Pesanan : 0000001</h6 >
                <small>03:12</small>
              </div>
              <p class="mb-1">Ada pesanan bibit tanaman baru</p>
            </a> -->
          </div>
        </div>
      </div>
    </div>

    <script>

      $(document).ready(function(){
        //updating the view with notificaiton using ajax
        function load_notifications(view = '', tipe = ''){

          $.ajax({
            url:"fetch.php",
            method: "POST",
            // data:{view:view, tipe:tipe},
            dataType: "json",
            success:function(data){
              $('.list-notifikasi').html(data.notification);
            }
          });
        }

        load_notifications('', 2);

        //click menu notifikasi
        $(document).on('click', '.menu-notifikasi', function(){
          $('.count').html('');
          load_notifications('yes', 2);
        });

        setInterval(function(){
          load_notifications('', 2);
        }, 5000);

      });
    </script>

<?php
  require '../template/footer2.php';
?>