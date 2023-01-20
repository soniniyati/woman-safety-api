<?php
// include configuration file
include $_SERVER['DOCUMENT_ROOT']. "/woman-safety-api/config/config.php";

// remove all session variables
session_unset();

// destroy the session
session_destroy();

// redirect to login page
header("Location: login.php");