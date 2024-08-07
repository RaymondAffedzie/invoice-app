<?php
session_start();
require_once '../models/DBConnection.php';
require_once '../controllers/Report.php';

date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

try {
    $from = filter_input(INPUT_GET, 'from', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $to = filter_input(INPUT_GET, 'to', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $today = date("Y-m-d");

    $errors = [];
    if (empty($from)) {
        $errors[] = "'FROM' date is required.";
    }
    if (empty($to)) {
        $errors[] = "'TO' date is required.";
    }

    if ($from > $today || $to > $today) {
        $errors[] = "Future dates are not allowed.";
    }

    if ($from > $to) {
        $errors[] = "'FROM' date cannot be later than 'TO' date.";
    }

    // Check if there are any validation errors
    if (!empty($errors)) {
        $response = [
            'status' => 'error',
            'message' => $errors[0],
        ];
    } else {

        // Create a DBConnection instance
        $dbConnection = new DBConnection();

        $report_data = [
            'date' => [$from, $to],
        ];

        // Create a report instance
        $report = new Report($dbConnection);
        $response = $report->getReport($report_data);
        
    }
} catch (Exception $e) {
    throw new Exception("Error: ". $e->getMessage());
}

if (!headers_sent()) {
    echo json_encode($response);
}
