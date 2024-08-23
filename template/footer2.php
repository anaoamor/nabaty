  </div>

  <!-- Chat Widget -->
  <a href="#" class="open-chat-widget">
    <i class="fa-solid fa-comment-dots fa-lg"></i>
    <span class="unread-chat-count bg-danger badge position-absolute top-1 start-100 right-0 translate-middle border border-light rounded-circle" style="border-radius:25px;"></span>
  </a>

  <div class="chat-widget">
    <div class="chat-widget-header">
      <a href="#" class="previous-chat-tab-btn">&lsaquo;</a>
      <p class="nama-partner mb-0 fw-bold"></p>
      <span><button id="delete"><i class="fa fa-trash"></i></button></span>
      <a href="#" class="close-chat-widget-btn">&times;</a>
    </div>
    <div class="chat-widget-content">
      <div class="chat-widget-tabs">
        <div class="chat-widget-tab chat-widget-conversations-tab"></div>
        <div class="chat-widget-tab chat-widget-conversation-tab"></div>
      </div>
    </div>
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
        //updating the view with unseen notification using ajax
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

        //automatically fetch notification
        // setInterval(function(){
        //   if((window.location.pathname).substr(-14) == "notifikasi.php"){
        //     load_notifications('yes', 2);  
        //   }
        //   load_unseen_notification('', 1);
        // }, 5000);
      });
      //Check whether calling function from outsise jquery $(document) in setInterval() can be done

      //Variable for chat widget
      let currentChatTab = 1;
      let idConversation = null;
      let unreadChat = 0;
      let deleteNode = document.getElementById("delete");
      let chatId = [];

      console.log(deleteNode);
      //Onclick event handler to open chat button
      document.querySelector('.open-chat-widget').onclick = event => {
        event.preventDefault();
        //Execute the initialize chat function
        initChat();
      };

      //Initialize chat function - handle all aspects of the chat widget
      const initChat = () => {

        //Show the chat widget
        document.querySelector('.chat-widget').style.display = 'flex';
        //Animate the chat widget
        document.querySelector('.chat-widget').getBoundingClientRect();
        document.querySelector('.chat-widget').classList.add('open');
        //Close button OnClick event handler
        document.querySelector('.close-chat-widget-btn').onclick = event => {
          event.preventDefault();
          //Close the chat
          document.querySelector('.chat-widget').classList.remove('open');
          selectChatTab(1);
          chatId.length = 0;
        };

        //Load chat conversation
        loadConversationList();
        selectChatTab(1);

        //Previous tab button OnClick event handler
        document.querySelector('.previous-chat-tab-btn').onclick = event => {
          event.preventDefault();
          //update the readed messages before out from tab 2
          if(currentChatTab == 2 && idConversation){
            // fetch("update_read.php?id="+idConversation, {cache:"no-store"});
          }
          //Transition to the respective page
          selectChatTab(currentChatTab-1);
        } 
      };

      // Select chat tab - it will be used to smoothly transition between tabs
      const selectChatTab = value => {
        // Update the current tab variable
        currentChatTab = value;
        // Select all tab elements and add the CSS3 property transform
        document.querySelectorAll('.chat-widget-tab').forEach(element => element.style.transform = `translateX(-${(value-1)*100}%)`);
        // If the user is on the first tab, hide the prev tab button element
        document.querySelector('.previous-chat-tab-btn').style.display = value > 1 ? 'block' : 'none';
        // Update the conversation ID variable if the user is on the first or second tab
        if (value == 1) {
            idConversation = null;
            document.querySelector('.mb-0.fw-bold').innerHTML = '';
            
            deleteNode.classList.remove("display");
            chatId.length = 0;
        }
      };
      
      const loadConversationList = () => {
        fetch('conversations.php', { cache: 'no-store' })
        .then(response => {
          if(!response.ok){
            throw new Error("Network response was not oke"+response.statusText);
          }
          return response.json();
        })
        .then(data => {
          //If unread data equals 0, display empty string
          unreadChat = data.unread;
          if(unreadChat !== 0){
            document.querySelector(".unread-chat-count").innerHTML = "<strong>"+unreadChat+"</strong>";
          }else {
            document.querySelector(".unread-chat-count").innerHTML = '';
          }

          //update conversation list
          document.querySelector('.chat-widget-conversations-tab').innerHTML = data.output_conversation;
          // Execute the conversation handler function
          conversationHandler();
        })
        .catch(error => {
          console.error("There was a problem with the fetch operation:", error);
        });
      };

      // Conversation handler function - will add the event handlers to the conversations list and new chat button
      const conversationHandler = () => {
        // Iterate the conversations and add the OnClick event handler to each element
        document.querySelectorAll('.chat-widget-conversation').forEach(element => {
          element.onclick = event => {
              event.preventDefault();
              // Get the conversation
              getConversation(element.dataset.id);
          };
        });
      };

      // Get conversation function - execute an AJAX request that will retrieve the conversation based on the conversation ID column
      const getConversation = id_conversation => {

        //Execute GET AJAX request
        fetch(`conversation.php?id_conversation=${id_conversation}`, {cache: 'no-store'})
        .then(response => {
          if(!response.ok){
            throw new Error ("Network response was not ok "+response.statusText);
          }
          return response.json(); //Parse the JSON from the response
        })
        .then(data => {
          //Update conversation ID variable
          idConversation = id_conversation;

          //For deleting chats
          let start, end, diff;
          var longpress = false;
          
          //Display chatting partner name
          document.querySelector('.mb-0.fw-bold').innerHTML = data.nama_partner;
          //Populate the chats in conversation tab content
          document.querySelector('.chat-widget-conversation-tab').innerHTML = data.output_chat;
          //Transition to the conversation tab (tab 3)
          selectChatTab(2);
          //Retrieve the input chat form element
          let chatWidgetInputMsg = document.querySelector('.chat-form');
          //If the element exists
          if(chatWidgetInputMsg){
            //If there are unread chat
            if(document.querySelector(".notif-label")){
              document.querySelector(".chat-widget-messages").scrollTop = document.querySelector(".notif-label").offsetTop - 50;
            }
            //Scroll to the bottom of the chat container
            else if(document.querySelector('.chat-widget-messages').lastElementChild) {
              document.querySelector('.chat-widget-messages').scrollTop = document.querySelector('.chat-widget-messages').lastElementChild.offsetTop;
            }

            //If the new chat are scrolled until the bottom
            document.querySelector(".chat-widget-messages").addEventListener("scroll", (event) => {
              if((Math.abs(document.querySelector(".chat-widget-messages").scrollHeight - document.querySelector(".chat-widget-messages").clientHeight - document.querySelector(".chat-widget-messages").scrollTop) <= 1) && document.querySelector(".unread-badge") ){
                document.querySelector(".unread-badge").textContent = '';
                // fetch(`update_read.php?id=${idConversation}`, {
                //   cache: 'no-store'
                // });
              }
            });

            //Chat submit event handler
            chatWidgetInputMsg.onsubmit = event => {
              event.preventDefault();
              //Check if white space is entered
              if(chatWidgetInputMsg.querySelector("input").value.trim() === ""){
                console.log("Can't sending empty chat");
              } else {
                //Execute POST AJAX request that will send the captured chat to the server and insert it into the database
                fetch(chatWidgetInputMsg.action, {
                  cache : 'no-store',
                  method: 'POST',
                  body: new FormData(chatWidgetInputMsg)
                })
                .then(response => response.text())
                .then(text => console.log(text))
                .catch(error => console.error('Error:', error));
              }
              
              //Create the new chat element
              let inputChatValue = chatWidgetInputMsg.querySelector('input').value;
              let nowTime = new Date();
              let chatWidgetMsg = "<div class='user-chat d-flex flex-row mb-0 justify-content-end'><div><p class='small p-1 mb-0 rounded-3 me-1 bg-primary text-white' style='font-size:14px;'>"+inputChatValue+"</p><p class='small mb-0 rounded-3 text-muted d-flex justify-content-end me-1' style='font-size:10px;'>"+nowTime.getHours()+":"+nowTime.getMinutes()+"</p></div></div>";
              //Add the element to the chat container, right at the bottom
              document.querySelector('.chat-widget-messages').innerHTML = document.querySelector('.chat-widget-messages').innerHTML + chatWidgetMsg;
              //Reset the message element
              chatWidgetInputMsg.querySelector('input').value = '';
              //Scroll to the bottom of the messages container
              document.querySelector('.chat-widget-messages').scrollTop = document.querySelector('.chat-widget-messages').lastElementChild.offsetTop;
            };
          }

          //Give long press event listener to display delete button
          document.querySelectorAll(".user-chat").forEach(element => {
            element.addEventListener("click", (e) => {
              if(longpress){
                //Display the delete button if chatId list are empty
                if(chatId.length == 0){
                  deleteNode.classList.add("display");
                }
                
                //Add or remove the long pressed messages
                var chosenPstn = chatId.indexOf(element.dataset.id);
                if(chosenPstn == -1){
                  chatId.push(element.dataset.id);
                  element.querySelector(".small.p-1.mb-0.rounded-3.me-1").classList.add("opacity-50");
                }else{
                  chatId.splice(chosenPstn, 1);
                  element.querySelector(".small.p-1.mb-0.rounded-3.me-1").classList.remove("opacity-50");
                }
              }
              //If only short click
              else if(!longpress && chatId.length !== 0){
                //Check wheter the clicked message in message array or not
                var chosenPstn = chatId.indexOf(element.dataset.id);
                if(chosenPstn == -1){
                  chatId.push(element.dataset.id);
                  element.querySelector(".small.p-1.mb-0.rounded-3.me-1").classList.add("opacity-50");
                }else{
                  chatId.splice(chosenPstn,1);
                  element.querySelector(".small.p-1.mb-0.rounded-3.me-1").classList.remove("opacity-50");
                }
              }

              //If there is no message selected
              if(chatId.length === 0){
                deleteNode.classList.remove("display");
              }
            });

            element.addEventListener("mousedown", () => {
              start = new Date().getTime();
            });

            element.addEventListener("mouseup", () => {
              end = new Date().getTime();
              longpress = (end - start < 500) ? false : true;
            });
          });
        })
        .catch(error => {
          console.error("There was a problem with the fetch operation:", error);
        });
      };

      loadConversationList();
    </script>
  </body>
</html>