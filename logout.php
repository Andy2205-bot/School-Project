<?php
session_start();
include('../config/database.php');
require_once('../classes/accountClass.php');

if (isset($_SESSION['userId'])) {

	// get database connection
	$database = new Database();
	$db = $database->getDbConnection();

	$user = new AccountClass($db);

	if ($user->UpdateUserStatus($_SESSION['userId'], 0)) {
		# code...
		session_destroy();
		echo "<script type='text/javascript'>;";
		echo "window.location.href='login.php';";
		echo "</script>";
	} else {
		# code...
		session_destroy();
		
		echo "<script type='text/javascript'>;";
		echo "window.location.href='login.php';";
		echo "</script>";
	}
} else {

	echo "<script type='text/javascript'>;";
	echo "window.location.href='login.php';";
	echo "</script>";
}
