<?php
include('../config/database.php');
require_once('../classes/humanTitle.php');

// get database connection
$database = new Database();
$db = $database->getDbConnection();

// pass connection to objects
$title = new HumanTitleClass($db);

$response= array();

$stmt  = $title->GetAllTitles();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //extract data
    extract($row);

    $response[] = array(
        'id' => $id,
        'name'=>$name);
}

 echo json_encode($response);


