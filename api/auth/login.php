<?php
// including the config file
include $_SERVER['DOCUMENT_ROOT']. "/woman-safety-api/config/config.php";
use Rakit\Validation\Validator;

// allowing only POST requests
allowOnlyPostRequests();

// SOME CHANGES


// getting the input data
$data = getInputData();

$validator = new Validator;
// array of required fields
$validation = $validator->make((array)$data, array(
        "email" => "required|email",
        "password" => "required|min:6")
);

// then validate
$validation->validate();

if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    http_response_code(400);
    echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
    die();
}

// getting the database connection
$db = getDBConnection();

// checking if the user exists
$user = $db->from('user')
    ->where('email')->is($data->email)
    ->select()
    ->first();

// checking if the user exists
if (!$user) {
    http_response_code(401);
    echo json_encode(["success" => false, "msg" => "Invalid credentials"]);
    die();
}

// checking if the password is correct
if (!password_verify($data->password, $user->password)) {
    http_response_code(401);
    echo json_encode(["success" => false, "msg" => "Invalid credentials"]);
    die();
}

// generating the token
try {
    $token = bin2hex(random_bytes(32));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "msg" => $e->getMessage()]);
    die();
}

// sending the token to user in the response
echo json_encode(["success" => true, "token" => $token]);
