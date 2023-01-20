<?php
// include configuration file
include $_SERVER['DOCUMENT_ROOT']. "/woman-safety-api/config/config.php";

// set content type to html
header("Content-type: text/html");
    // get email and password from request
    $email = $_POST['email'];
    $password = $_POST['password'];
    // check if email and password are not empty
    if (!empty($email) && !empty($password)) {
        // check if email is valid
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            // getting the database connection
            $db = getDBConnection();

            // checking if the user exists
            $user = $db->from('user')
                ->where('email')->is($email)
                ->andWhere('password')->is($password)
                ->select()
                ->first();

            // checking if the user exists
            if (empty($user)) {
                // redirect to login page
                $_SESSION['error'] = "Invalid email or password";
                header("Location: ../login.php");
            }else{
                // setting the session
                $_SESSION['user_id'] = $user->id;
                // redirect to home page
                header("Location: ../index.php");
            }

        } else {
            // set error message
            $_SESSION['error'] = "Invalid email or password";
            // redirect to login page
            header("Location: ../login.php");
            exit();
        }
    } else {
        // set error message
        $_SESSION['error'] = "Invalid email or password";
        // redirect to login page
        header("Location: ../login.php");
        exit();
    }