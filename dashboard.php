<?php
include('header.php');
include('sidebar.php');
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-8">

                <!-- News & Updates Traffic -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="socials.php">Create New Chat</a></li>
                        </ul>
                    </div>

                    <div class="card-body pb-0">
                        <h5 class="card-title">All Chats<span> | Today</span></h5>

                        <div class="defaultContainer" id="defaultContainer"></div>

                        <div class="news" id="view_ajax">

                        </div><!-- End sidebar recent chats-->

                    </div>
                </div><!-- End chats & Updates -->

            </div>

            <div class="col-lg-4">

                <?php
                if ($_SESSION['role'] == "Lecturer") {

                    require_once('../classes/moduleChatClass.php');
                    $moduleChat = new ModuleChatClass($db);

                    $response = array();
                    //get lecturer modules groups 
                    $response = $moduleChat->GetLecturerModulesGroupsByLecturerId($_SESSION['userId']);
                    echo "<div class='card'>
                    <div class='card-header'>
                        <h5 class='card-title'>Modules Groups</h5>
                    </div>
                    <div class='card-body'>
                        <div class='news'>";

                    foreach ($response as $key => $module) {
                        echo "<div class='post-item clearfix'>
                        <img src='assets/img/news-1.jpg' alt=''>
                        <h4><a href='module-chat-room.php?id=" . $module['moduleId'] . "'>" . $module['moduleName'] . "</a></h4>
                        <p>" . $module['program'] . " - " . $module['level'] . "<br>" . $module['lecturer'] . " ( " . $module['email'] . " )
                         </p> </div>";
                    }
                    echo "</div>";
                }

                ?>

            
        </div>
        </div>

        </div>
        </div>
    </section>

</main><!-- End #main -->
<script>
    var lastTimeID = 0;
    var chats = -1;

    $(document).ready(function() {
        $('#btnSend').click(function() {
            sendChatText();
            $('#chatInput').val("");
        });
        startChat();
    });

    function startChat() {
        setInterval(function() {
            getChatText();
            if (chats < 0) {
                var defaultHtml = '<div class="row"><hr><h4><a href="socials.php">Create New Chat</a></h4><p></p></div>';
                $('#defaultContainer').html(defaultHtml);
            } else {
                var defaultHtml = "";
                $('#defaultContainer').html(defaultHtml);
            }
        }, 2000);
    }

    function getChatText() {
        $.ajax({
            type: "GET",
            url: "../scripts/chats-script.php?id=" + lastTimeID,
            dataType: "json",
            processData: false
        }).done(function(data) {
            let jsonData = data;
            var jsonLength = jsonData.length;
            console.log(jsonLength);

            var html = "";


            for (var i = 0; i < jsonLength; i++) {
                chats = jsonLength;
                console.log("chats: " + chats);
                var result = jsonData[i];
                var isActive = Number(result.iSActive)
                var isStaff = Number(result.isStaff)

                var userInfor = result.department + " ( "+ result.role+" ) ";

                console.log(result);

                if (isActive > 0) {
                    html += '<div class="post-item clearfix"><img src="assets/img/news-1.jpg" alt=""><h4><a href="chat-room.php?chatId=' + result.chatId + '&rid=' + result.userId + '">' + result.fullname+ '</a> <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Online</span></h4><p>' + userInfor + ' </p></div>';
                } else {
                    html += '<div class="post-item clearfix"><img src="assets/img/news-1.jpg" alt=""><h4><a href="chat-room.php?chatId=' + result.chatId + '&rid=' + result.userId + '">' + result.fullname + '</a> <span class="badge bg-secondary"><i class="bi bi-check-circle me-1"></i> Offline</span></h4><p>' + userInfor + ' </p></div>';
                }
                //html += '<div style="color:#' + result.color + '">(' + result.chattime + ') <b>' + result.usrname + '</b>: ' + result.chattext + '</div>';
                var x = Number(result.lastIndex)

                if (lastTimeID < x) {
                    console.log("Index: " + lastTimeID + " is less that or == " + result.lastIndex);
                    lastTimeID = result.lastIndex;

                } else {

                    console.log("Index: " + lastTimeID);
                }

            }
            //$('#view_ajax').append(html);
            $('#view_ajax').append(html)
            //alert(lastTimeID);

        });


    }

    function sendChatText() {
        var chatInput = $('#chatInput').val();
        if (chatInput != "") {
            $.ajax({
                type: "GET",
                url: "/submit.php?chattext=" + encodeURIComponent(chatInput)
            });
        }
    }
</script>
<?php
include('footer.php');
?>