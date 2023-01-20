<?php
include $_SERVER['DOCUMENT_ROOT']. "/woman-safety-api/config/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Rakit\Validation\Validator;

// allowing only POST requests
allowOnlyPostRequests();

// getting the input data
$data = getInputData();

$validator = new Validator;
// array of required fields
$validation = $validator->make((array)$data, array(
        "email" => "required|email"
    ));

// then validate
$validation->validate();

if ($validation->fails()) {
    // handling errors
    $errors = $validation->errors();
    http_response_code(400);
    echo json_encode($errors->firstOfAll());
    die();
}


$mail = new PHPMailer(true);
// generate 4 digit otp
$otp = rand(1000, 9999);

$email = $data->email;
try {
    //  Creating object of PHPMailer
    $mail = new PHPMailer(true);

    //Set PHPMailer to use SMTP.
    $mail->isSMTP();

    //Set SMTP host name
    $mail->Host = "smtp-mail.outlook.com";

    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;

    //Provide username and password
    $mail->Username = "vfa2023@outlook.com";
    $mail->Password = "vfa@password";

    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";

    //Set TCP port to connect to
    $mail->Port = 587;
    $mail->isHTML(true);
    $mail->From = "vfa2023@outlook.com";
    $mail->FromName = "women safety";                                 //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    $mail->addAddress($email);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = "women safety OTP verification";
    $body = "<!DOCTYPE html><html lang='en'><head><title>Email Verification</title><style>body {background-color: #f5f5f5;font-family: Arial, sans-serif;margin: 0;padding: 0;}.container {background-color: white;margin: 30px auto;padding: 30px;border-radius: 10px;box-shadow: 0 0 10px 0 #ccc;}h1 {text-align: center;margin-bottom: 30px;color: #333;}p {line-height: 1.5;margin-bottom: 20px;color: #555;}strong {color: #f44336;}.button {display: block;width: 200px;margin: 30px auto;padding: 15px;background-color: #f44336;color: white;text-align: center;text-decoration: none;border-radius: 5px;}.button:hover {background-color: #d32f2f;}</style></head><body><div class='container'><h1>Verify Your Email for Safe and Secure Women Safety Services</h1><p>Dear User,</p><p>We are committed to providing safe and secure services for women. In order to ensure that only authorized individuals have access to our women's safety resources, we require email verification.</p><p>Please use the One-Time Password (OTP) below to verify your email and activate your account. This OTP is valid for only 15 minutes, so please use it as soon as possible.</p><p>OTP: <strong>$otp</strong></p><p>Once you have verified your email, you will be able to access our wide range of resources and services, such as emergency contacts, self-defense tips, and more.</p><p>Thank you for your cooperation and for helping us to keep our community safe.</p><p>Sincerely<br>Women Safety Team.</p></div></body></html>";
    $mail->Body    = $body;
    $mail->AltBody = "your otp is $otp";

    $mail->send();
    echo $otp;
} catch (Exception $e) {
    http_response_code(500);
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}