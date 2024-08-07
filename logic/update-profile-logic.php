<?php
require_once '../models/DBConnection.php';
require_once '../controllers/User.php';;

date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName  = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastName   = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $role       = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $contact     = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $firstName = ucwords($firstName);
    $lastName  = ucwords($lastName);

    $errors = [];
    if (empty($firstName)) {
        $errors[] = "Enter your first name.";
    }
    if (empty($lastName)) {
        $errors[] = "Enter your last name.";
    }
    if (empty($contact)) {
        $errors[] = "Enter your contact.";
    }

    // Validate contact number
    $contactPattern = '/^0\d{9}$/';
    if (!preg_match($contactPattern, $contact)) {
        $errors[] = "Invalid contact number format.";
    }

    // Check if there are any validation errors
    if (!empty($errors)) {
        $response = [
            'status' => 'error',
            'message' => $errors[0],
        ];
    } else {
        $profile_details = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'contact' => $contact,
            'role' => $role,
        ];

        // Create a DBConnection instance
        $dbConnection = new DBConnection();

        // Create a user instance
        $user = new User($dbConnection);
        $response = $user->updateUserProfile($profile_details);
    }
}

if (!headers_sent()) {
    echo json_encode($response);
}
