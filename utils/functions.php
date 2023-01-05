<?php
use Opis\Database\Database;
use Opis\Database\Connection;

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
