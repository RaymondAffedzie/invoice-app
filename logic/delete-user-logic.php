<?php
date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

require_once '../models/DBConnection.php';
require_once '../controllers/User.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId =  filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Create a DBConnection instance
    $dbConnection = new DBConnection();

    // Create a user instance
    $user = new User($dbConnection);
    $response = $user->deleteUser($userId);
    
} else {
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method.',
    ];
}

if (!headers_sent()) {
    echo json_encode($response);
}
