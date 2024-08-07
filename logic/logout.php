<?php
require_once '../controllers/Logout.php';
date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

try {

   $input = file_get_contents('php://input');
   $data = json_decode($input, true);

   if (!isset($data['data']) || $data['data'] !== 'logout') {
       throw new Exception("Invalid request data");
   }

   $logout = new Logout();
   $response = $logout->createLogout();

} catch (Exception $e) {
    throw new Exception("Error: ".$e->getMessage());
}

if (!headers_sent()) {
    echo json_encode($response);
}
