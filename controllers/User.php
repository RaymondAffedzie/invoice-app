<?php
session_start();
require_once 'Database.php';

class User extends Database
{
    protected const TABLE = 'users';
    protected static $response = [
        'status' => null,
        'message' => null,
        'redirect' => null,
    ];

    public function __construct(DBConnection $dbConnection)
    {
        parent::__construct($dbConnection);
    }

    // Function to verify or confirm and hash password
    protected function validatePassword($password, $confirm_password)
    {
        if ($password !== $confirm_password) {
            self::$response['status'] = false;
            self::$response['message'] = 'Your password and confirm password do not match.';
            return self::$response;
        } else {
            // Hash password
            $hash = password_hash($password, PASSWORD_BCRYPT);
            self::$response['status'] = true;
            self::$response['message'] = $hash;
            return self::$response;
        }
    }

    // Add new user
    public function createUser($data)
    {
        try {
            // Check if the user's contact already exists to preven adding duplicate contact
            if ($this->recordExist(self::TABLE, 'contact', $data['contact'])) {
                self::$response['status'] = false;
                self::$response['message'] = "The mobile nubmer {$data['contact']} is being used by another user.";
                return self::$response;
            }

            // Validate password
            $validatePassword = $this->validatePassword($data['password'], $data['confirm_password']);
            if ($validatePassword['status'] === true) {
                $data['password'] = $validatePassword['message'];
                unset($data['confirm_password']); 

                // Add user using the addRecord function
                self::addRecord($data, self::TABLE);

                self::$response['status'] = true;
                self::$response['message'] = 'User registration successful';
            } else {
                self::$response = $validatePassword; // Return the validation error response
            }
        } catch (PDOException $e) {
            self::$response['status'] = 'error';
            self::$response['message'] = "Error - registration failed: " . $e->getMessage();
        }

        return self::$response;
    }

    // Get a single or one user's details
    public function getSingleUserDetails($data)
    {
        self::$response['status'] = true;
        self::$response['message'] = $this->getSingleRecordByValue(self::TABLE, 'user_id', $data);
        return self::$response;
    }

    // Update user details
    public function updateUserProfile($data)
    {
        try {
            // Check if the user's new contact is used by a different user
            $check = $this->getSingleRecordByValue(self::TABLE, 'contact', $data['contact']);
            if ($check && $check['user_id'] !== $_SESSION['user']['id']) {
                self::$response['status'] = false;
                self::$response['message'] = "The mobile nubmer {$data['contact']} is being used by another user.";
                return self::$response;
            }

            // Proceed with the update if the contact is not used any other user
            $result = self::updateRecord(self::TABLE, $data, 'user_id', $_SESSION['user']['id']);
            
            if ($result === true) {
                self::$response['status'] = true;
                self::$response['redirect'] = 'profile.php?';
                self::$response['message'] = 'Your profile has been updated.';
            } else {
                self::$response['status'] = false;
                self::$response['message'] = 'No changes made to your profile.';
            }
        } catch (PDOException $e) {
            self::$response['status'] = 'error';
            self::$response['message'] = "Error updating your profile: " . $e->getMessage();
        }

        return self::$response;
    }

    // change current user password
    public function changePassword($data)
    {
        try {
            // verify entered old and new password
            if (strcmp($data['old'], $data['new']) === 0) {
                self::$response['status'] = false;
                self::$response['message'] = "Old password and new password cannot be same.";
                return self::$response;
            }

            // verify enterd new and confirm password
            if (strcmp($data['new'], $data['confirm']) !== 0) {
                self::$response['status'] = false;
                self::$response['message'] = "New password and confirm password do not match.";
                return self::$response;
            }

            // Fetch the current user details using getSingleUserDetails
            $userDetails = $this->getSingleUserDetails($_SESSION['user']['id']);
            if ($userDetails['status'] === false) {
                self::$response['status'] = false;
                self::$response['message'] = "User not found.";
                return self::$response;
            }

            // Fetch current password
            $currentPasswordHash = $userDetails['message']['password'];

            // Verify entered password with current password
            if (!password_verify($data['old'], $currentPasswordHash)) {
                self::$response['status'] = false;
                self::$response['message'] = "Old password is incorrect.";
                return self::$response;
            }

            // Hash the new password
            $newPasswordHash = password_hash($data['new'], PASSWORD_BCRYPT);

            // Update the password using the updateRecord method
            $updateData = ['password' => $newPasswordHash];
            $result = self::updateRecord(self::TABLE, $updateData, 'user_id', $_SESSION['user']['id']);

            if ($result === true) {
                self::$response['status'] = true;
                self::$response['redirect'] = 'profile.php';
                self::$response['message'] = "Password changed successfully.";
            } else {
                self::$response['status'] = false;
                self::$response['redirect'] = 'profile.php';
                self::$response['message'] = "Failed to change the password.";
            }
        } catch (PDOException $e) {
            self::$response['status'] = 'error';
            self::$response['message'] = "Error: " . $e->getMessage();
        }

        return self::$response;
    }

    // delete user
    public function deleteUser($userId)
    {
        try {
            // error_log("Attempting to delete user with ID: $userId"); // Debugging step
            $result = self::deleteRecord(self::TABLE, 'user_id', $userId);
            if ($result === true) {
                self::$response['status'] = true;
                self::$response['redirect'] = 'users.php';
                self::$response['message'] = "User has been removed.";
            } else {
                self::$response['status'] = false;
                self::$response['message'] = "Failed to remove user.";
            }
        } catch (PDOException $e) {
            self::$response['status'] = 'error';
            self::$response['message'] = "Error: " . $e->getMessage();
        }

        return self::$response;
    }
}
