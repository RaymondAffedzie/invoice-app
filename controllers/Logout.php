<?php
session_start();
class Logout
{
    private static $response = [
        'status' => null,
        'message' => null,
        'redirect' => null,
    ];

    // Add new login
    public function createLogout()
    {
        try {

            $_SESSION = [];

            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }

            session_destroy();

            self::$response['status'] = true;
            self::$response['message'] = 'Logout successful';
            self::$response['redirect'] = 'login.php';

        } catch (Exception $e) {

            self::$response['status'] = false;
            self::$response['message'] = "Error Processing Request: " . $e->getMessage();
        }

        return self::$response;
    }
}
