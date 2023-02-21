<?php
session_start();
include('../config/database.php');
require_once('../classes/notificationClass.php');

if (isset($_GET['id'])) {
    //response array declaration
    $response= array();
    // get database connection
    $database = new Database();
    $db = $database->getDbConnection();

    //get user id from session
    $id = $_GET['id'];
    // pass connection to objects
    $notification = new NotificationClass($db);

    $response  = $notification->DeleteNotification( $id);
    
    echo json_encode($response);
}