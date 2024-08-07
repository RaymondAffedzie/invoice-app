<?php
require_once 'Database.php';

class Report extends Database
{
    private const TABLE = 'invoices';
    private const USER_TABLE = 'users';

    private static $response = [
        'status' => null,
        'message' => null,
        'redirect' => null,
    ];

    public function __construct(DBConnection $dbConnection)
    {
        parent::__construct($dbConnection);
    }

    // Get all reports 
    public function getReport()
    {
        self::$response['status'] = true;
        $invoices = self::$response['message'] = $this->getRecords(self::TABLE);

        foreach($invoices as &$invoice) {
            $user = $this->getSingleRecordByValue(self::USER_TABLE, 'user_id', $invoice['user_id']);
            $invoice['user'] = $user['first_name'] . " " . $user['last_name'];
            unset($invoice['user_id']);
        }
        
        self::$response['message'] = $invoices;
        
        return self::$response;
    }

    // Get a single or one report's details
    public function getSingleReportDetails($data)
    {
        self::$response['status'] = true;
        self::$response['message'] = $this->getSingleRecordByValue(self::TABLE, 'invoice_id', $data);
        return self::$response;
    }

    // Get users sales for today
    public function dailySales() {
        self::$response['status'] = true;
        self::$response['message'] = $this->getRecordsWithConditions(self::TABLE, [
            'date' => [date('Y-m-d'), date('Y-m-d')],
            'user_id' => $_SESSION['user']['id']
        ]);
        return self::$response;
    }

    // Get user's sales for this week
    public function weeklySales()
    {
        self::$response['status'] = true;
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        self::$response['message'] = $this->getRecordsWithConditions(self::TABLE, [
            'date' => [$startOfWeek, $endOfWeek],
            'user_id' => $_SESSION['user']['id']
        ]);
        return self::$response;
    }

    // Get user's sales for this month
    public function monthlySales()
    {
        self::$response['status'] = true;
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        self::$response['message'] = $this->getRecordsWithConditions(self::TABLE, [
            'date' => [$startOfMonth, $endOfMonth],
            'user_id' => $_SESSION['user']['id']
        ]);
        return self::$response;
    }

    // Get user's total sales
    public function totalSales()
    {
        self::$response['status'] = true;
        self::$response['message'] = $this->getRecordsWithConditions(self::TABLE, [
            'user_id' => $_SESSION['user']['id']
        ]);
        return self::$response;
    }

}
