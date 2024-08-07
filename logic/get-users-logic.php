<?php
require_once '../models/DBConnection.php';
require_once '../controllers/Users.php';

date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

// Create a DBConnection instance
$dbConnection = new DBConnection();

// Create a user instance
$users = new Users($dbConnection );
$response = $users->getUsers();

if (!headers_sent()) {
    echo json_encode($response);
}
