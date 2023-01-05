<?php
header("Content-type: application/json");
require_once "{$_SERVER['DOCUMENT_ROOT']}/woman-safety-api/vendor/autoload.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/woman-safety-api/config/constants.php";

// including function files
include "{$_SERVER['DOCUMENT_ROOT']}/woman-safety-api/utils/functions.php";


$date = new DateTimeImmutable();

