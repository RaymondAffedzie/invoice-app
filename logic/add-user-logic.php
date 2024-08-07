<?php
date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

require_once '../models/DBConnection.php';
require_once '../controllers/User.php';
require_once '../controllers/Code.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName  = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastName   = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($firstName !== null) {
        $firstName = ucwords($firstName);
    }
    if ($lastName !== null) {
        $lastName = ucwords($lastName);
    }

    // Validate contact number
    $contactPattern = '/^0\d{9}$/';
    if (!preg_match($contactPattern, $contact)) {
        $errors[] = "Invalid contact number format.";
    }

    $errors = [];
    if (empty($firstName)) {
        $errors[] = "Enter your first name.";
    }
    if (empty($lastName)) {
        $errors[] = "Enter you last name.";
    }
    if (empty($password)) {
        $errors[] = "Enter you password.";
    }
    if (empty($confirm_password)) {
        $errors[] = "Enter confirm your password.";
    }

    // Check if there are any validation errors
    if (!empty($errors)) {
        $response = [
            'status' => 'error',
            'message' => $errors[0],
        ];
    } else {
        $code = new Code();
        $user_details = [
            'user_id' => $code->uuid_v4(),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'contact' => $contact,
            'password' => $password,
            'confirm_password' => $confirm_password,
            'role' => $role,
        ];

        // Create a DBConnection instance
        $dbConnection = new DBConnection();

        // Create a user instance
        $user = new User($dbConnection);
        $response = $user->createUser($user_details);
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid action.'
    ];
}

if (!headers_sent()) {
    echo json_encode($response);
}
