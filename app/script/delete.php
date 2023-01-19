<?php
// include configuration file
include $_SERVER['DOCUMENT_ROOT']. "/woman-safety-api/config/config.php";

// set content type to html
header("Content-type: text/html");
// get email and password from request
$user_id = $_GET['id'];

// if user id is 1 it can not be deleted
if ($user_id == 1) {
    // redirect to login page
    $_SESSION['error'] = "You can not delete this user";
    header("Location: ../index.php");
    exit();
}

// getting the database connection
$db = getDBConnection();

// delete user
$db->from('user')
    ->where('id')->is($user_id)
    ->delete();

// redirect to home page
header("Location: ../index.php");

