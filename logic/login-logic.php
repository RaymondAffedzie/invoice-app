<?php
require_once '../models/DBConnection.php';
require_once '../controllers/Login.php';

date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

try {
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $errors = [];

    if (empty($contact)) {
        $errors[] = "Email is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (!empty($errors)) {
        $response = [
            'status' => false,
            'message' => $errors[0]
        ];
    } else {

        $data = [
            'contact' => $contact,
            'password' => $password,
        ];

        // Create a DBConnection instance
        $dbConnection = new DBConnection();

        // Create a user instance
        $login = new Login($dbConnection);
        $response = $login->createLogin($data);
    }
} catch (Exception $e) {
    throw new Exception("Error: " . $e->getMessage());
}

if (!headers_sent()) {
    echo json_encode($response);
}
