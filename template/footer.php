  </div>

<!-- FOOTER -->
    <footer id="main-footer" class="py-4">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <small>
          <?php 
            $tanggal = new DateTime('now');
            echo "<p class=\"text-center\">Copyright © ".$tanggal->format("Y")." Nabaty</p>";
          ?>
          </small>
        </div>
      </div>
    </div>
    </footer>

    <script src="../js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>

    <script>
      
      $(document).ready(function(){
        //updating the view with notification using ajax
        function load_unseen_notification(view = '', tipe = ''){
          
          $.ajax({
            url:"fetch.php",
            method: "POST",
            data:{view:view, tipe:tipe},
            dataType:"json",
            success:function(data){
              $('.dropdown-notifikasi').html(data.notification);
              if(data.unseen_notification > 0){
                $('.count').html(data.unseen_notification);
              }
            }
          });
        }

        //updating the view with notificaiton using ajax
        function load_notifications(view = '', tipe = ''){

          $.ajax({
            url:"fetch.php",
            method: "POST",
            data:{view:view, tipe:tipe},
            dataType: "json",
            success:function(data){
              $('.list-notifikasi').html(data.notification);
            }
          });
        }

        load_unseen_notification('', 1);    
        load_notifications('', 2);      

        //click notifications navbar and load new notifications
        $(document).on('click', '.dropdown-toggle', function(){
          $('.count').html('');
          load_unseen_notification('yes', 1);
        });
        //click menu notifikasi
        $(document).on('click', '.menu-notifikasi', function(){
          $('.count').html('');
          load_notifications('yes', 2);
        });

        setInterval(function(){
        load_notifications('', 2);
        }, 5000);

        setInterval(function(){
          load_unseen_notification('', 1);
        }, 5000);

      });
      
    </script>
  </body>
</html>