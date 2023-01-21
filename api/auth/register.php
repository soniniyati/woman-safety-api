<?php
// including the config file
include $_SERVER['DOCUMENT_ROOT']. "/woman-safety-api/config/config.php";
use Rakit\Validation\Validator;

// allowing only POST requests
allowOnlyPostRequests();

// getting the input data
$data = getInputData();

$validator = new Validator;
// array of required fields
$validation = $validator->make((array)$data, array(
    "first_name" => "required",
    "middle_name" => "nullable",
    "last_name" => "required",
    "email" => "required|email",
    "dob" => "required|date:d/m/Y",
    "district" => "required",
    "address" => "required",
    "password" => "required|min:6",
    "role_id" => "required|integer",
    )
);

// then validate
$validation->validate();

if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    http_response_code(400);
    echo json_encode([$errors->firstOfAll(), "role" => ["1"=> "admin", "2"=> "parent", "3"=> "child"]]);
    die();
}

// getting the database connection
$db = getDBConnection();

// if user already exists then return error
$user = $db->from('user')
    ->where('email')->is($data->email)
    ->select()
    ->first();

// checking if the user exists
if ($user) {
    http_response_code(409);
    die();
}

// formatting date of birth
$dob = DateTime::createFromFormat('d/m/Y', $data->dob);

// if user does not exist then create a new user
$user = $db->insert(array(
    "first_name" => $data->first_name,
    "middle_name" => $data->middle_name,
    "last_name" => $data->last_name,
    "gender" => $data->gender,
    "dob" => $dob->format('Y-m-d'),
    "district" => $data->district,
    "address" => $data->address,
    "email" => $data->email,
    "role_id" => $data->role_id,
    "password" => password_hash($data->password, PASSWORD_DEFAULT),
    "created_at" => getCurrentDateTime(),
    "updated_at" => getCurrentDateTime()
))->into('user');

$user = $db->from('user')
    ->where('email')->is($data->email)
    ->select()
    ->first();

// generating the token
try {
    $token = generate_token($user);
    echo json_encode(["success" => true, "token" => $token]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "msg" => $e->getMessage()]);
    die();
}