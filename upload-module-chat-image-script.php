<?php
session_start();
include('../config/database.php');
require_once('../classes/moduleChatClass.php');

if (isset($_SESSION['moduleGroupChatId'])){

    //response array declaration
    $response = array();
    // get database connection
    $database = new Database();
    $db = $database->getDbConnection();
    // pass connection to objects
    $moduleChat = new ModuleChatClass($db);

    if (is_array($_FILES)) {
        if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
            $nameEx = "image".$_SESSION['moduleGroupChatId']."".$_SESSION['userId'];

            $sourcePath = $_FILES['userImage']['tmp_name'];
            $targetPath = "C:/xampp/htdocs/solusi.chatroom/pages/assets/chat-images/".$nameEx."". $_FILES['userImage']['name'];
            if (move_uploaded_file($sourcePath, $targetPath)) {
                $saveName = $nameEx."". $_FILES['userImage']['name'];

                  if ($moduleChat->SendNewMessage($_SESSION['moduleGroupChatId'],$_SESSION['userId'],0,1, $saveName)) {
                    $response['error'] = false;
                $response['message'] = "message send.";
                } else {
                    $response['error'] = false;
                $response['message'] = "send failed.chatId: ".$_SESSION['chatId']." userId: (".$_SESSION['userId'].") receiverId: (".$_SESSION['rid'].") Message: ".$mess;
                } 
            }
        }
    }
    echo json_encode($response);
}
