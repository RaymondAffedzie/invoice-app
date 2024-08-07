<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

function errorHandler($errno, $errstr, $errfile, $errline)
{
    $eventDate = date("Y-M-d H:i:s");
	$message = "[$eventDate] - Error: [$errno] $errstr - $errfile:$errline";
    error_log($message . PHP_EOL, 3, "../error-log.txt");
}

set_error_handler("errorHandler");

require_once '../controllers/storeController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName  = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastName   = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $otherNames = filter_input(INPUT_POST, 'other_names', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $userEmail  = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $userContact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password   = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $con_password = filter_input(INPUT_POST, 'con_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($otherNames !== null) {
        $otherNames = ucwords($otherNames);
    }
    if ($firstName !== null) {
        $firstName = ucwords($firstName);
    }
    if ($lastName !== null) {
        $lastName = ucwords($lastName);
    }
    if ($userEmail !== null) {
        $userEmail = strtolower($userEmail);
    }
    
    $errors = array();
    if (empty($firstName)) {
        $errors[] = "First name is required.";
    }
    if (empty($lastName)) {
        $errors[] = "Last name is required.";
    }
    if (empty($userEmail)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid Email format.";
    }
    if (empty($userContact)) {
        $errors[] = "Contact is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    if($password !== $con_password){
        $errors[] = "Password and confirm password do not match";
    }

    // Validdate email address using regular expression
    $emailPattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    if (!preg_match($emailPattern, $userEmail)) {
        $response = array(
            'status' => 'error',
            'message' => 'Invalid email format.'
        );
        echo json_encode($response);
        exit;
    }

    // Check if there are any validation errors
    if (!empty($errors)) {
        $response = array(
            'status' => 'error',
            'message' => $errors[0],
        );
    } else {
        include_once "../controllers/uniqueCode.php";

        $role = 'Customer';
        $user_id = $v4uuid;
        $controller = new storeController();
        $password = password_hash($password, PASSWORD_BCRYPT);
        $result = $controller->addUser($user_id, $firstName, $lastName, $otherNames, $userEmail, $userContact, $role, $password);

        if ($result === 'exists') {
            $response = array(
                'status' => 'error',
                'message' => 'A User with the same email already exists.'
            );
        } elseif ($result == true) {
            $response = array(
                'status' => 'success',
                'message' => 'Registration successful. Please login into your account!',
                'redirect' => ' ./login.php'
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Failed to register user.'
            );
        }
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Invalid action.'
    );
}


if (!headers_sent()) {
    echo json_encode($response);
}
