<?php
session_start();
require_once 'Database.php';

class Invoice extends Database
{
    private const PARENT_TABLE = 'invoices';
    private const CHILD_TABLE = 'invoice_items';
    private static $response = [
        'status' => null,
        'message' => null,
        'redirect' => null,
    ];

    public function __construct(DBConnection $dbConnection)
    {
        parent::__construct($dbConnection);
    }

    // Add new invoice
    public function createInvoice($data)
    {
        try {
            // Add invoice using the addRecord function
            self::addRecord($data, self::PARENT_TABLE);

            self::$response['status'] = true;
            // error_log("Invoice details data value: " . var_export($details, true));
            self::$response['redirect'] = 'invoice-details.php?invoice='.$data['invoice_id'];
            self::$response['message'] = 'invoice placed successful';

        } catch (PDOException $e) {
            self::$response['status'] = 'error';
            self::$response['message'] = "Error - failed to place invoice: " . $e->getMessage();
        }

        return self::$response;
    }

    public function createInvoiceItem($data) {
        try {
            
            self::addRecord($data, self::CHILD_TABLE);

        } catch (PDOException $e) {
            error_log("Error: Failed to add invoice item: " . $e->getMessage());
        }
    }

    // Get user's invoices 
    public function getInvoices()
    {
        self::$response['status'] = true;
        $details = self::$response['message'] = $this->getRecordsByValue(self::PARENT_TABLE, 'user_id', $_SESSION['user']['id']);
        foreach ($details as &$invoice) {
            $user = $this->getSingleRecordByValue('users', 'user_id', $_SESSION['user']['id']);
            $invoice['user'] = $user['first_name'] . ' ' . $user['last_name'];
        }
        
        self::$response['message'] = $details;

        return self::$response;
    }
    
    // Get a single or one invoice's details
    public function getSingleInvoiceDetails($data)
    {
        $items = self::$response['message'] = $this->getRecordsByValue(self::CHILD_TABLE, 'invoice_id', $data);

        foreach($items as &$item) {
            $fetchedData = $this->getSingleRecordByValue(self::PARENT_TABLE, 'invoice_id', $data);
            $item['date'] = $fetchedData['date'];
            $item['contact'] = $fetchedData['customer_contact'];
            $item['transportation'] = $fetchedData['transportation'];
            $item['workmanship'] = $fetchedData['workmanship'];
            $item['total'] = $fetchedData['amount'];
            $item['title'] = $fetchedData['title'];
            $item['customer'] = $fetchedData['customer_first_name'] . ' ' . $fetchedData['customer_last_name'];
        }
        
        self::$response['message'] = $items;

        return self::$response;
    }
    
}
