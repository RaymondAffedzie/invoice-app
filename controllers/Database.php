<?php
require_once 'Handler.php';
class Database
{
    use Handler;
    private static $pdo;

    public function __construct(DBConnection $dbConnection)
    {
        self::$pdo = $dbConnection->connect();
        if (self::$pdo === null) {
            die("Could not connect to the database.");
        }
    }

    public static function addRecord($data, $tableName)
    {
        try {
            $table = $tableName;
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));

            $query = "INSERT INTO $table ($columns) VALUES ($values)";
            $stmt = self::$pdo->prepare($query);
            $stmt->execute($data);

            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            throw new Exception("Error Fetching Record" . $e->getMessage());
        }
    }

    // Add a record with an image
    public function addRecordWithImage($data, $imageData, $tableName)
    {
        $response = [
            'status' => null,
            'message' => null,
        ];
        try {
            // Check if the file upload is successful
            if ($imageData['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->imageUpload($imageData);

                if (!is_array($uploadResult) || !isset($uploadResult['message'])) {
                    $response['status'] = $uploadResult['status'];
                    $response['message'] = 'Failed to upload image.';
                    return $response;
                }

                // get image name
                $imageName = $uploadResult['message'];
                $data['image'] = $imageName;

                $addRecord = self::addRecord($data, $tableName);
               
                $response['status'] = $addRecord;
                return $response;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Error adding record" . $e->getMessage());
        }
    }


    /**
     * --------------------------------------------------------------------------------------------------
     * ----------------- CHECK ------RECORDS------------------------------------
     * --------------------------------------------------------------------------------------------------
     */

    //  Verify the value of record by a single field
    public function recordExist($tableName, $columnName, $value)
    {
        try {
            $query = "SELECT COUNT(*) FROM $tableName WHERE $columnName = :value";
            $stmt = self::$pdo->prepare($query);
            $stmt->execute(['value' => $value]);

            $rowCount = $stmt->fetchColumn();

            return $rowCount > 0;
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function recordExistMultipleFields($tableName, $data)
    {
        try {
            $conditions = [];
            foreach (array_keys($data) as $column) {
                $conditions[] = "$column = :$column";
            }

            $whereClause = implode(' AND ', $conditions);

            $query = "SELECT COUNT(*) FROM $tableName WHERE $whereClause";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($data);

            $rowCount = $stmt->fetchColumn();

            return $rowCount > 0;
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    /**
     * --------------------------------------------------------------------------------------------------
     * --------------- GET / FETCH ---RECORDS--------------------------------------
     * --------------------------------------------------------------------------------------------------
     */

    // Get All records
    public function getRecords($tableName)
    {
        try {
            $query = "SELECT * FROM $tableName";
            $stmt = self::$pdo->prepare($query);
            $stmt->execute();

            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $records ? $records : null;
        } catch (PDOException $e) {
            throw new Exception("Error Fetching Record" . $e->getMessage());
        }
    }

    // Get single record with a condition
    public function getSingleRecordByValue($tableName, $columnName, $value)
    {
        try {
            $query = "SELECT * FROM $tableName WHERE $columnName = :value";
            $stmt = self::$pdo->prepare($query);
            $stmt->execute(['value' => $value]);

            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            return $record ? $record : null;
        } catch (PDOException $e) {
            throw new Exception("Error Fetching Record" . $e->getMessage());
        }
    }

    // Get multiple records with a condition
    public function getRecordsByValue($tableName, $columnName, $value)
    {
        try {
            $query = "SELECT * FROM $tableName WHERE $columnName = :value";
            $stmt = self::$pdo->prepare($query);
            $stmt->execute(['value' => $value]);

            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $records ? $records : null;
        } catch (PDOException $e) {
            throw new Exception("Error Fetching Records" . $e->getMessage());
        }
    }

    // Get multiple records with less than condition
    public function getRecordsByLessThanValue($tableName, $columnName, $value)
    {
        try {
            $query = "SELECT * FROM $tableName WHERE $columnName < :value";
            $stmt = self::$pdo->prepare($query);
            $stmt->execute(['value' => $value]);

            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $records ? $records : null;
        } catch (PDOException $e) {
            throw new Exception("Error Fetching Records" . $e->getMessage());
        }
    }

    // Get multiple records with conditions
    public function getRecordsWithConditions($tableName, $conditions)
    {
        try {
            // Start building the query
            $query = "SELECT * FROM $tableName WHERE ";
            $params = [];
            $clauses = [];

            // Iterate over the conditions to build the query and parameters
            foreach ($conditions as $columnName => $value) {
                if (is_array($value)) {
                    // Handle range condition
                    $clauses[] = "$columnName BETWEEN :start_$columnName AND :end_$columnName";
                    $params[":start_$columnName"] = $value[0];
                    $params[":end_$columnName"] = $value[1];
                } else {
                    // Single value condition
                    $clauses[] = "$columnName = :$columnName";
                    $params[$columnName] = $value;
                }
            }

            // Join the clauses with 'AND' and complete the query
            $query .= implode(' AND ', $clauses);

            // Prepare and execute the query
            $stmt = self::$pdo->prepare($query);
            $stmt->execute($params);

            // Fetch and return the records
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $records ? $records : null;
        } catch (PDOException $e) {
            throw new Exception("Error Fetching Records: " . $e->getMessage());
        }
    }

    // Get multiple records with NOT conditions
    public function getRecordsWithNotConditions($tableName, $conditions)
    {
        try {
            // Start building the query
            $query = "SELECT * FROM $tableName WHERE ";
            $params = [];
            $clauses = [];

            // Iterate over the conditions to build the query and parameters
            foreach ($conditions as $columnName => $value) {
                $clauses[] = "$columnName != :$columnName";
                $params[$columnName] = $value;
            }

            // Join the clauses with 'AND' and complete the query
            $query .= implode(' AND ', $clauses);

            // Prepare and execute the query
            $stmt = self::$pdo->prepare($query);
            $stmt->execute($params);

            // Fetch and return the records
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $records ? $records : null;
        } catch (PDOException $e) {
            throw new Exception("Error Fetching Records: " . $e->getMessage());
        }
    }


    // Get single record with signle check
    public function getSignleRecordMultipleFieldCheck($tableName, $data)
    {
        try {
            $conditions = [];
            foreach (array_keys($data) as $column) {
                $conditions[] = "$column = :$column";
            }

            $whereClause = implode(' AND ', $conditions);

            $query = "SELECT * FROM $tableName WHERE $whereClause";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($data);

            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            return $record ? $record : null;
        } catch (PDOException $e) {
            error_log("Error verifying data existence: " . $e->getMessage());
            throw $e;
            return false;
        }
    }

    // Get the existing image filename for an entity from the database
    private function getExistingImageFileName($tableName, $primaryKeyColumn, $primaryKeyValue)
    {
        try {
            $query = "SELECT image FROM $tableName WHERE $primaryKeyColumn = ?";
            $stmt = self::$pdo->prepare($query);
            $stmt->execute([$primaryKeyValue]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result['image'])) {
                return $result['image'];
            }

            return false; // No image filename found in the database
        } catch (PDOException $e) {
            echo "Failed to fetch image filename: " . $e->getMessage();
            return false;
        }
    }

    /**
     * --------------------------------------------------------------------------------------------------
     * ------------ UPDATE ----RECORDS--------------------------------------
     * --------------------------------------------------------------------------------------------------
     */


    // Update a record
    public static function updateRecord($tableName, $updateData, $primaryKeyColumn, $primaryKeyValue)
    {
        try {
            $table = $tableName;

            $updateColumns = array_keys($updateData);
            $updateValues = array_values($updateData);

            $updateSets = array_map(function ($col) {
                return $col . ' = ?';
            }, $updateColumns);

            $updateSetsString = implode(', ', $updateSets);

            $updateValues[] = $primaryKeyValue;

            $query = "UPDATE $table SET $updateSetsString WHERE $primaryKeyColumn = ?";
            $stmt = self::$pdo->prepare($query);
            $stmt->execute($updateValues);

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error updating record: " . $e->getMessage();
            return false;
        }
    }

    // Update a record with image
    public function updateRecordWithImage($tableName, $primaryKeyColumn, $primaryKeyValue, $updateData, $imageData)
    {
        try {
            // Check if the image is included in the update
            $existingImageFileName = $this->getExistingImageFileName($tableName, $primaryKeyColumn, $primaryKeyValue);

            if (isset($imageData['tmp_name']) && $imageData['tmp_name']) {

                // New image uploaded, process the uploaded image
                $imageFileName = self::imageUpload($imageData);

                // Add the image filename to the update data array
                $updateData['image'] = $imageFileName['message'];

                // Delete the existing image file
                $this->deleteExistingImage($existingImageFileName);
            } else {
                // No new image uploaded, retain the existing image filename
                $updateData['image'] = $existingImageFileName;
            }

            $updateResponse = self::updateRecord($tableName, $updateData, $primaryKeyColumn, $primaryKeyValue);

            if ($updateResponse) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error updating record with image: " . $e->getMessage());
            return 'error';
        }
    }



    /**
     * --------------------------------------------------------------------------------------------------
     * ------------ DELETE-- --RECORD--------------------------------------
     * --------------------------------------------------------------------------------------------------
     */

    // Delete a record
    public static function deleteRecord($tableName, $primaryKeyColumn, $primaryKeyValue)
    {
        try {
            $query = "DELETE FROM $tableName WHERE $primaryKeyColumn = ?";
            $stmt = self::$pdo->prepare($query);
            $stmt->execute([$primaryKeyValue]);

            $rowCount = $stmt->rowCount();

            return $rowCount > 0;
        } catch (PDOException $e) {
            error_log("Error deleting record: " . $e->getMessage()); // Debugging step
            return false;
        }
    }

    // Delete a record with an image 
    public function deleteRecordWithImage($tableName, $primaryKeyColumn, $primaryKeyValue)
    {
        try {

            $existingImageFileName = $this->getExistingImageFileName($tableName, $primaryKeyColumn, $primaryKeyValue);
            $deleteRecord = self::deleteRecord($tableName, $primaryKeyColumn, $primaryKeyValue);

            if ($deleteRecord) {
                $deleteImageResult = $this->deleteExistingImage($existingImageFileName);

                if ($deleteImageResult === true) {
                    return true;
                } elseif ($deleteImageResult === 'not-found') {
                    return 'not-found';
                } elseif ($deleteImageResult === 'not-existing') {
                    return 'not-existing';
                } else {
                    return 'Failed';
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Failed to delete record with image: " . $e->getMessage();
            return false;
        }
    }
}
