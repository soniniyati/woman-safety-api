<?php
include $_SERVER['DOCUMENT_ROOT']. "/woman-safety-api/config/config.php";

send_otp_email($_POST['email']);

// change the status of the user to active