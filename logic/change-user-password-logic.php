<?php
date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

require_once '../models/DBConnection.php';
require_once '../controllers/Users.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id  = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $old_password  = filter_input(INPUT_POST, 'old_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $new_password  = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $con_password  = filter_input(INPUT_POST, 'con_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $errors = array();
    if (empty($old_password)) {
        $errors[] = "Enter old password.";
    }
    if (empty($new_password)) {
        $errors[] = "Enter new password.";
    }
    if (empty($con_password)) {
        $errors[] = "Confirm new password.";
    }
    
    // Check if there is any valiation errors
    if (!empty($errors)) {
        $response = [
            'status' => 'error',
            'message' => $errors[0],
        ];
    } else {
        $password_details = [
            'user_id' => $user_id,
            'old' => $old_password,
            'new' => $new_password,
            'confirm' => $con_password,
        ];

        // Create a DBConnection instance
        $dbConnection = new DBConnection();

        // Create a users instance
        $user = new Users($dbConnection);
        $response = $user->changeUserPassword($password_details);
        error_log(json_encode($response));
    }
}

if (!headers_sent()) {
    echo json_encode($response);
}
