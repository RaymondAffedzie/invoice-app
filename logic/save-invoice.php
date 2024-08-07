<?php
session_start();
date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '/../error.log');
header('Content-Type: application/json');

$response = [
    'status' => false,
    'message' => 'Unknown error',
];

if (!empty($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

try {
    // Get the JSON input
    $input = file_get_contents('php://input');

    $newCartData = json_decode($input, true);
    if ($newCartData === null && json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data');
    }

    // Log the decoded data type and value
    // error_log("Decoded data type: " . gettype($newCartData));
    // error_log("Decoded data value: " . var_export($newCartData, true));

    if (!is_array($newCartData)) {
        throw new Exception('Decoded JSON is not an array');
    }

    // Initialize cart session if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Update cart data in session and calculate amounts
    $totalAmountExisting = 0;
    $totalAmountNew = 0;

    foreach ($newCartData as $newItem) {
        if (!isset($newItem['product']) || !isset($newItem['quantity']) || !isset($newItem['price'])) {
            throw new Exception('Missing product data in new item');
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$existingItem) {
            if ($existingItem['product'] === $newItem['product']) {
                $existingItem['quantity'] += $newItem['quantity'];
                $existingItem['amount'] = $existingItem['price'] * $existingItem['quantity'];
                $totalAmountExisting += $existingItem['amount'];

                $found = true;
                break;
            }
        }

        if (!$found) {
            $newItem['amount'] = $newItem['price'] * $newItem['quantity'];
            $_SESSION['cart'][] = $newItem;
            $totalAmountNew += $newItem['amount'];
        }
    }

    // Calculate total amount of the cart
    $totalAmountCart = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalAmountCart += $item['amount'];
    }

    // Add cart total to session
    $_SESSION['total_amount'] = $totalAmountCart + $_SESSION['cart'][0]['workmanship'] + $_SESSION['cart'][0]['transportation'];

    // error_log("Cart Session data value: " . var_export($_SESSION['cart'], true));
    $response = [
        'status' => true,
        'message' => 'Cart saved successfully',
    ];
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
}

if (!headers_sent()) {
    echo json_encode($response);
}
