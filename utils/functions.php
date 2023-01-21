<?php
use Opis\Database\Database;
use Opis\Database\Connection;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



// Getting the database connection
const SECRET_KEY = "secret-key-for-jwt-token-generation-and-validation";

// Function to get database connection
function getDBConnection() {
    try {
        // Creating the database connection
        $connection = new Connection(
            DSN,
            DB_USER,
            DB_PASS
        );
        return  new Database($connection);
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        die();
    }
}

// Function to get the current date and time
function getCurrentDateTime(): string
{
    $date = new DateTimeImmutable();
    return $date->format('Y-m-d H:i:s');
}

// Function to get input data
function getInputData() {
    return json_decode(file_get_contents("php://input"));
}

// Function to validate request
function validateRequest($data, $required_fields): array
{
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($data->$field)) {
            $errors[] = "$field is required";
        }
    }
    return $errors;
}

// Function to allow only POST requests
function allowOnlyPostRequests(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["success" => false, "msg" => "Method not allowed"]);
        die();
    }
}

// Function to allow only GET requests
function allowOnlyGetRequests(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(["success" => false, "msg" => "Method not allowed"]);
        die();
    }
}

// function to generate new token
function generate_token($result): string
{
    $date = new DateTimeImmutable();
    $request_data = [
        'iat' => $date->getTimestamp(),
        'exp' => $date->getTimestamp() + 60 * 60 * 24 * 7,
        'data' => $result
    ];
    return JWT::encode(
        $request_data,
        SECRET_KEY,
        'HS512'
    );
}

// function to verify user & admin
function verify_token(){
    try {
        $request = apache_request_headers();
        if (isset($request['Authorization'])) {
            $token = explode(' ', $request['Authorization']);
            $token = $token[1];
            $data = JWT::decode($token, new Key(SECRET_KEY, 'HS512'));
            $data = (array)$data->data;
            return authenticate_user("user", "id", $data['id'], $data);
        } else {
            http_response_code(401);
            exit();
        }
    } catch (Exception $ex) {
        http_response_code(401);
        echo json_encode($ex->getMessage());
        exit();
    }
}

// Function to authenticate user
function authenticate_user($table, $column, $value, $data)
{
    $db = getDBConnection();
    $validate = $db->from($table)->where($column)->is($value)->select()->count();
    if ($validate == 1) {
        return $data;
    } else {
        http_response_code(401);
        exit();
    }
}
