<?php
include('../config/database.php');
require_once('../classes/studentLevel.php');

// get database connection
$database = new Database();
$db = $database->getDbConnection();

// pass connection to objects
$levels = new StudentLevelClass($db);

$response= array();

$stmt  = $levels->GetAllStudentLevels();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //extract data
    extract($row);

    $response[] = array(
        'id' => $id,
        'name'=>$name);
}
 echo json_encode($response);


