<?php
// including the config file
include $_SERVER['DOCUMENT_ROOT'] . "/woman-safety-api/config/config.php";

use Rakit\Validation\Validator;

// allowing only POST requests
allowOnlyPostRequests();

// getting the input data
$data = getInputData();

$validator = new Validator;
// array of required fields
$validation = $validator->make((array)$data, array(
    "user_id" => "required",
    "primary_contact" => "required",
    "emergency_contact_1" => "required",
    "emergency_contact_2" => "required",
    )
);

// then validate
$validation->validate();

if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    http_response_code(400);
    echo json_encode([$errors->firstOfAll()]);
    die();
}

// generating the token
try {
    // getting the database connection
    $db = getDBConnection();
    // if user does not exist then create a new user
    $result = $db->insert(array(
        "user_id" => $data->user_id,
        "primary_contact" => $data->primary_contact,
        "emergency_contact_1" => $data->emergency_contact_1,
        "emergency_contact_2" => $data->emergency_contact_2,
        "created_at" => getCurrentDateTime(),
        "updated_at" => getCurrentDateTime()
    ))->into('emergency_contacts');
    http_response_code(201);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "msg" => $e->getMessage()]);
    die();
}