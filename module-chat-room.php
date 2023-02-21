<?php
include('header.php');
include('sidebar.php');
require_once('../classes/moduleChatClass.php');

if (isset($_GET['id'])) {

  $response = array();
  // pass connection to objects
  $moduleChat = new ModuleChatClass($db);
  $modId  = $_GET['id'];

  $moduleGroupChatId =  $moduleChat->GetModuleGroupChatId($modId, $_SESSION['academicSessionId']);

  //check if group is already created
  if ($moduleGroupChatId == 0) {
    //redirect to not found page
    echo "<script type='text/javascript'>;";
    echo "window.location.href='not-found.php';";
    echo "</script>";
  } else {
    //get module details
    $response = $moduleChat->GetModuleDetailsByGroupChatId($moduleGroupChatId);
    //storing chat id in session
    $_SESSION['moduleGroupChatId'] = $moduleGroupChatId;
  }
}

?>

<style>
  #chat_box {
    -ms-overflow-style: none;
    /* Internet Explorer 10+ */
    scrollbar-width: none;
    /* Firefox */
  }

  #chat_box::-webkit-scrollbar {
    display: none;
    /* Safari and Chrome */
  }

  #chat_box {
    display: block;
    overflow: auto;
    height: 300px;
    margin: 0 auto;
    margin-top: 20px;
  }

  #sender_message_box {
    text-align: right;
    padding-right: 5px;
    margin-top: 5px;
    border-radius: 5px;
    margin-left: 20%;
    width: auto;
  }

  #receiver_message_box {
    text-align: left;
    padding-left: 5px;
    margin-top: 5px;
    border-radius: 5px;
    margin-right: 20%;
    width: auto;
  }

  #sender_message {
    text-align: justify;
    border-radius: 10px;
    background-color: lightgreen;
    display: inline-block;
    padding: 10px;
    border: 1px solid lightgreen;
    margin-top: 10px;
  }

  #receiver_message {
    text-align: justify;
    border-radius: 10px;
    background-color: silver;
    display: inline-block;
    padding: 10px;
    border: 1px solid silver;
    margin-top: 10px;
  }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Module Chat Room</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
        <li class="breadcrumb-item">Pages</li>
        <li class="breadcrumb-item active">Blank</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-8">

        <!-- News & Updates Traffic -->
        <div class="card">
          <div class="card-header news">
            <div class="post-item clearfix">
              <img src="assets/img/news-1.jpg" alt="">
              <h4><a href="#"><?php echo $response['moduleName']; ?></a></h4>
              <p><?php echo $response['program'] . " - " . $response['level']; ?><br>
                <?php echo $response['lecturer'] . " ( " . $response['email'] . " )"; ?>
              </p>
            </div>
          </div>

          <div class="card-body pb-0">
            <!-- chatbox -->
            <div class="" id="chat_box">


            </div>
            <!-- card footer -->
            <div class="card-footer col-sm-12">
              <form action="" method="post" class="col-sm-12">
                <div class="row col-sm-12">
                  <div class="form-group col-sm-8">
                    <textarea id="chatMessage" name="chatMessage" rows="2" cols="1" class="form-control"></textarea>


                  </div>
                  <div class="form-group col-sm-4">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#verticalycentered"><i class="bi bi-image"></i></button>&nbsp;&nbsp;<span> <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#pdfverticalycentered"><i class="bi bi-file-earmark-pdf-fill"></i></button></span>&nbsp;&nbsp;<span><button id="btnSend" type="submit" class="btn btn-primary">Send Message</button></span>
                  </div>
                </div>

              </form>

            </div>
          </div><!-- End News & Updates -->

        </div>

        <div class="col-lg-4">

          <!-- <div class="card">
            <div class="card-body">
              <h5 class="card-title">Example Card</h5>
              <p>This is an examle page with no contrnt. You can use it as a starter for your custom pages.</p>
            </div>
          </div> -->

        </div>
      </div>
  </section>

  <!-- upload image modal -->
  <div class="modal fade" id="verticalycentered" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Upload Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form id="uploadForm" method="post">
            <div class="form-group">
              <input id="imageFile" name="userImage" type="file" class="form-controller" />
            </div>
        </div>
        <div class="modal-footer">
          <input type="submit" value="Submit" class="btn btn-success" />
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
    </div>
  </div><!-- End Vertically centered Modal-->

  <!-- upload pdf modal -->
  <div class="modal fade" id="pdfverticalycentered" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Upload File</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form id="uploadPdfForm" method="post">
            <div class="form-group">
              <input id="pdfFile" name="userFile" type="file" class="form-controller" />
            </div>
        </div>
        <div class="modal-footer">
          <input type="submit" value="Submit" class="btn btn-success" />
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
    </div>
  </div><!-- End Vertically centered Modal-->

</main><!-- End #main -->

<script>
  var lastTimeID = 0;
  var request;

  $(document).ready(function() {
    $('#btnSend').click(function() {
      sendChatText();
      $('#chatMessage').val("");
    });
    startChat();

    //upload image form
    $("#uploadForm").on('submit', (function(e) {
      e.preventDefault();
      $.ajax({
        url: "../scripts/upload-module-chat-image-script.php",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          // $("#targetLayer").html(data);
          $('#imageFile').val("");

        },
        error: function() {}
      });
    }));

    //upload pdf form
    $("#uploadPdfForm").on('submit', (function(e) {
      e.preventDefault();
      $.ajax({
        url: "../scripts/upload-module-chat-pdf-script.php",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          // $("#targetLayer").html(data);
          $('#pdfFile').val("");
          console.log(data);
        },
        error: function(data) {
          console.log(data);
        }
      });
    }));
  });

  function startChat() {
    setInterval(function() {
      getChatText();
    }, 2000);
  }

  function getChatText() {
    $.ajax({
      type: "GET",
      url: "../scripts/get-module-chat-messages-script.php?id=" + lastTimeID,
      dataType: "json",
      processData: false
    }).done(function(data) {
      let jsonData = data;
      var jsonLength = jsonData.length;

      var html = "";

      for (var i = 0; i < jsonLength; i++) {

        var result = jsonData[i];

        var sender = Number(result.isSender)
        var isStaff = Number(result.isStaff)
        var isImage = Number(result.isImage);
        var isFile = Number(result.isFile);
        //alert(sender);
        if (sender == 1) {
          if (isStaff == 1) {

            if (isImage == 1) {
              //sender box
              html += '<div id="sender_message_box"><div id="sender_message"><img class="img-fluid" width="80%" height="80" src="assets/chat-images/' + result.message + '"><br><hr><i style="color:red;"> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';

            } else if (isFile == 1) {
              html += '<div id="sender_message_box"><div id="sender_message"><a href="assets/chat-files/' + result.message + '">' + result.message + '</a><br><hr><i style="color:red;"> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';
            } else {
              //sender box
              html += '<div id="sender_message_box"><div id="sender_message">' + result.message + '<br><hr><i style="color:red;"> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';

            }

          } else {

            if (isImage == 1) {
              //sender box
              html += '<div id="sender_message_box"><div id="sender_message"><img class="img-fluid" width="80%" height="80" src="assets/chat-images/' + result.message + '"><br><hr><i> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';

            } else if (isFile == 1) {
              html += '<div id="sender_message_box"><div id="sender_message"><a href="assets/chat-files/' + result.message + '">' + result.message + '</a><br><hr><i> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';

            } else {
              //sender box
              html += '<div id="sender_message_box"><div id="sender_message">' + result.message + '<br><hr><i> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';

            }
          }
        } else {
          if (isStaff == 1) {

            if (isImage == 1) {
              html += '<div id="receiver_message_box"> <div id="receiver_message"><img class="img-fluid" width="80%" height="80" src="assets/chat-images/' + result.message + '"><br><hr><i style="color:red;"> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';
            } else if (isFile == 1) {
              html += '<div id="receiver_message_box"> <div id="receiver_message"><a href="assets/chat-files/' + result.message + '">' + result.message + '</a><br><hr><i style="color:red;"> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';
            } else {
              //load message
              html += '<div id="receiver_message_box"> <div id="receiver_message">' + result.message + '<br><hr><i style="color:red;"> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';

            }
          } else {

            if (isImage == 1) {
              html += '<div id="receiver_message_box"> <div id="receiver_message"><img class="img-fluid" width="80%" height="80" src="assets/chat-images/' + result.message + '"><br><hr><i> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';
            } else if (isFile == 1) {
              html += '<div id="receiver_message_box"> <div id="receiver_message"><a href="assets/chat-files/' + result.message + '">' + result.message + '</a><br><hr><i> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';

            } else {
              html += '<div id="receiver_message_box"> <div id="receiver_message">' + result.message + '<br><hr><i> ' + result.senderEmail + " - " + result.sendTime + '</i></div></div>';
            }
          }

        }

        // if (isActive > 0) {
        //   html += '<div class="post-item clearfix"><img src="assets/img/news-1.jpg" alt=""><h4><a href="chat-room.php?chatId=' + result.chatId + '">' + result.firstnames + ' ' + result.lastname + '</a> <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Online</span></h4><p>' + userInfor + ' </p></div>';
        // } else {
        //   html += '<div class="post-item clearfix"><img src="assets/img/news-1.jpg" alt=""><h4><a href="chat-room.php?chatId=' + result.chatId + '">' + result.firstnames + ' ' + result.lastname + '</a> <span class="badge bg-secondary"><i class="bi bi-check-circle me-1"></i> Offline</span></h4><p>' + userInfor + ' </p></div>';
        // }
        //html += '<div style="color:#' + result.color + '">(' + result.chattime + ') <b>' + result.usrname + '</b>: ' + result.chattext + '</div>';
        var x = Number(result.lastChatIndex)

        if (lastTimeID < x) {
          console.log("Index: " + lastTimeID + " is less that or == " + x);
          lastTimeID = x;

        } else {

        }

      }
      //$('#view_ajax').append(html);
      $('#chat_box').append(html);
      $('#chat_box').scrollTop($('#chat_box')[0].scrollHeight);


    });
  }

  function sendChatText() {
    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();

    // Abort any pending request
    if (request) {
      request.abort();
    }

    var chatMessage = $("#chatMessage").val();
    if (chatMessage != "") {
      $.post('../scripts/send-module-chat-message-script.php', {
          message: chatMessage
        },
        function(data, status) {

          console.log(data);

        });
    } else {

    }
  }
</script>
<?php
include('footer.php');
?>