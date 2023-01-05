<?php
// including the config file
include $_SERVER['DOCUMENT_ROOT']. "/woman-safety-api/config/config.php";
use Rakit\Validation\Validator;

// allowing only POST requests
allowOnlyPostRequests();

// getting the input data
$data = getInputData();

// validating all required fields
/*
 * Table structure for table `user`
 * name
 * gender
 * marital_status
 * source
 * dob
 * emergency_contact
 * district
 * address
 * status
 * email
 * password
 * created_at
 * updated_at
 * */
$validator = new Validator;
// array of required fields
$validation = $validator->make((array)$data, array(
    "name" => "required",
    "gender" => "required",
    "marital_status" => "required",
    "source" => "required",
    "dob" => "required|date:d-m-Y",
    "emergency_contact" => "required",
    "district" => "required",
    "address" => "required",
    "status" => "required",
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

// if user already exists then return error
$user = $db->from('user')
    ->where('email')->is($data->email)
    ->select()
    ->first();

// checking if the user exists
if ($user) {
    http_response_code(400);
    echo json_encode(["success" => false, "msg" => "User already exists"]);
    die();
}

// formatting date of birth
$dob = DateTime::createFromFormat('d/m/Y', $data->dob);

// if user does not exist then create a new user
$user = $db->insert(array(
    "name" => $data->name,
    "gender" => $data->gender,
    "marital_status" => $data->marital_status,
    "source" => $data->source,
    "dob" => $dob,
    "status" => $data->status,
    "emergency_contact" => $data->emergency_contact,
    "district" => $data->district,
    "address" => $data->address,
    "email" => $data->email,
    "password" => password_hash($data->password, PASSWORD_DEFAULT),
    "created_at" => getCurrentDateTime(),
    "updated_at" => getCurrentDateTime()
))->into('user');

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
