<?php
session_start();
require_once '../models/DBConnection.php';
require_once '../controllers/Invoice.php';
require_once '../controllers/Code.php';

date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');
try {
    $code = new Code();
    // Create a DBConnection instance
    $dbConnection = new DBConnection();

    $invoice_id = $code->uuid_v4();
    $invoice_data = [
        'invoice_id' => $invoice_id,
        'user_id' => $_SESSION['user']['id'],
        'amount' => $_SESSION['total_amount'],
        'date' => date('Y-m-d'),
        'customer_first_name' => $_SESSION['cart'][0]['firstName'],
        'customer_last_name' => $_SESSION['cart'][0]['lastName'],
        'customer_contact' => $_SESSION['cart'][0]['contact'],
        'transportation' => $_SESSION['cart'][0]['transportation'],
        'workmanship' => $_SESSION['cart'][0]['workmanship'],
        'title' => $_SESSION['cart'][0]['title'],
    ];

    // Create an invoice instance and add invoice
    $invoice = new Invoice($dbConnection);
    $response = $invoice->createInvoice($invoice_data);

    // Insert invoice items into the invoice_items table
    foreach ($_SESSION['cart'] as $cart_item) {
        $invoice_item_data = [
            'invoice_id' => $invoice_id,
            'product_name' => $cart_item['product'],
            'quantity' => $cart_item['quantity'],
            'price' => $cart_item['price'],
            'amount' => $cart_item['price'] * $cart_item['quantity'],
        ];
        $invoice->createInvoiceItem($invoice_item_data);
    }

    unset($_SESSION['cart']);
    unset($_SESSION['total_amount']);

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
}

if (!headers_sent()) {
    echo json_encode($response);
}