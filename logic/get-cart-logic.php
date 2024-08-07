<?php
session_start();

date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

$response = [
    'status' => true,
    'message' => $_SESSION['cart'],
    'total' => $_SESSION['total_amount'],
];

if (!headers_sent()) {
    echo json_encode($response);
}
