<?php
require_once 'Database.php';

class Login extends Database
{
    private const PARENT_TABLE = 'users';
    private const PARENT_COLUMN = 'contact';
    private static $response = [
        'status' => null,
        'message' => null,
        'redirect' => null,
    ];

    public function __construct(DBConnection $dbConnection)
    {
        parent::__construct($dbConnection);
    }

    // Add new login
    public function createLogin($data)
    {
        try {

            $user = self::getSingleRecordByValue(self::PARENT_TABLE, self::PARENT_COLUMN, $data['contact']);
            
            //error_log("user info: " . var_export($user, true));
            
            if ($user['contact'] === $data['contact']) {

                if (password_verify($data['password'], $user['password'])) {

                    session_start();

                    $_SESSION['user']['contact'] = $user['contact'];
                    $_SESSION['user']['id'] = $user['user_id'];
                    $_SESSION['user']['role'] = $user['role'];
                    $_SESSION['user']['name'] = $user['first_name'] . ' ' . $user['last_name'];

                    self::$response['status'] = true;
                    self::$response['redirect'] = 'index.php';
                    self::$response['message'] = 'Login successful';

                } else {

                    self::$response['status'] = false;
                    self::$response['message'] = 'Wrong password';
                }
            } else {

                self::$response['status'] = false;
                self::$response['message'] = 'User not found';
            }
        } catch (PDOException $e) {
            self::$response['status'] = 'error';
            self::$response['message'] = "Error - failed login: " . $e->getMessage();
        }

        return self::$response;
    }
}
