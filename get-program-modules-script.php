<?php
session_start();
include('../config/database.php');
require_once('../classes/programModulesClass.php');

if (isset($_GET['pid'])) {
    //response array declaration
    $response= array();
    // get database connection
    $database = new Database();
    $db = $database->getDbConnection();

    //get user id from session
    //$userId = $_SESSION['userId'];
    $programId = $_GET['pid'];
    $levelId =  $_GET['lid'];
    // pass connection to objects
    $modules = new ProgramModulesClass($db);

    $response  = $modules->GetAllProgramModulesForSelectList($programId,$levelId);
    
    echo json_encode($response);
}