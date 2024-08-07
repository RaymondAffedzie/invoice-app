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
        // Create a DBConnection instance
        $dbConnection = new DBConnection();

        // Create a report instance
        $report = new Report($dbConnection);
        $response = $report->getReport();
        
    
} catch (Exception $e) {
    throw new Exception("Error: ". $e->getMessage());
}

if (!headers_sent()) {
    echo json_encode($response);
}
