<?php
require_once 'User.php';
class Users extends User
{

    public function __construct(DBConnection $dbConnection)
    {
        parent::__construct($dbConnection);
    }


    // Get all users 
    public function getUsers()
    {
        self::$response['status'] = true;
        // select all the users except the current user
        self::$response['message'] = $this->getRecordsWithNotConditions(self::TABLE, ['user_id' => $_SESSION['user']['id']]);

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
    public function updateUserDetails($data, $userId)
    {
        try {
            // Check if the user's new contact is used by a different user
            $check = $this->getSingleRecordByValue(self::TABLE, 'contact', $data['contact']);
            if ($check && $check['user_id'] !== $userId) {
                self::$response['status'] = false;
                self::$response['message'] = "The mobile nubmer {$data['contact']} is being used by another user.";
                return self::$response;
            }

            // Proceed with the update if the contact is not used any other user
            $result = self::updateRecord(self::TABLE, $data, 'user_id', $userId);
            
            if ($result === true) {
                self::$response['status'] = true;
                self::$response['message'] = 'User details updated successfully.';
            } else {
                self::$response['status'] = false;
                self::$response['message'] = 'No changes made to the user details.';
            }
        } catch (PDOException $e) {
            self::$response['status'] = 'error';
            self::$response['message'] = "Error updating user details: " . $e->getMessage();
        }
        
        self::$response['redirect'] = 'user-details.php?user='.$userId;
        return self::$response;
    }

    // change users password
    public function changeUserPassword($data)
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
            $userDetails = $this->getSingleUserDetails($data['user_id']);
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
            $result = self::updateRecord(self::TABLE, $updateData, 'user_id', $data['user_id']);

            if ($result === true) {
                self::$response['status'] = true;
                self::$response['message'] = "User's password changed successfully.";
            } else {
                self::$response['status'] = false;
                self::$response['message'] = "Failed to change the password.";
            }
        } catch (PDOException $e) {
            self::$response['status'] = 'error';
            self::$response['message'] = "Error: " . $e->getMessage();
        }

        self::$response['redirect'] = 'user-details.php?user='.$data['user_id'];

        return self::$response;
    }

}
