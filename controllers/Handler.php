<?php
require_once 'Code.php';
trait  Handler
{
    private static  $response = [
        'status' => null,
        'message' => null,
    ];

    // function to handle image upload
    public static function imageUpload($image)
    {
        $maxFileSize = 10 * 1024 * 1024; // 10MB (adjust according to your requirement)
        $allowedExtensions = ['png', 'jpeg', 'jpg'];

        // Check the file size
        if ($image['size'] > $maxFileSize) {
            self::$response['status'] = 'error';
            self::$response['message'] = 'Upload image size less than 10MB';
            return self::$response;
        }

        // Check the file extension
        $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            self::$response['status'] = 'error';
            self::$response['message'] = 'Upload image with PNG or JPG or JPEG format';
            return self::$response;
        }

        // Define the target directory to store the uploaded images
        $targetDir = "../uploads/";

        // Create the target directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Generate a unique filename for the uploaded image
        $unique = new Code();
        $filename = $unique->codeDigits() . '-' . $image['name'];

        // Construct the full path for the image file
        $targetPath = $targetDir . $filename;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($image['tmp_name'], $targetPath)) {
            // File upload successful, return the image filename
            self::$response['status'] = true;
            self::$response['message'] = $filename;
            return self::$response;
        } else {
            // Failed to move the uploaded file
            self::$response['status'] = 'error';
            self::$response['message'] = 'Error - Failed to upload file';
            return self::$response;
        }
    }

    // function to handle image upload
    protected function handleFileUpload($file)
    {
        $maxFileSize = 100 * 1024 * 1024; // 100MB (adjust according to your requirement)
        $allowedExtensions = ['pdf', 'docx', 'doc', 'xls', 'xlsx', 'pptx', 'ppt', 'mkv', 'mp4', 'mp3'];

        // Check the file size
        if ($file['size'] > $maxFileSize) {
            return 'Large size';
        }

        // Check the file extension
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            return 'Invalid extension';
        }

        // Define the target directory to store the uploaded images
        $targetDir = "../uploads/";

        // Create the target directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Generate a unique filename for the uploaded file
        $filename = uniqid() . '_' . $file['name'];

        // Construct the full path for the image file
        $targetPath = $targetDir . $filename;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // File upload successful, return the image filename only
            return ['filename' => $filename];
        } else {
            // Failed to move the uploaded file
            return 'Failed upload';
        }
    }

    //  Delete an existing image file 
    protected function deleteExistingImage($imageFileName)
    {
        try {
            // The image filename to delete
            $existingImageFileName = $imageFileName;

            if ($existingImageFileName) {
                $uploadDir = "../uploads/";
                $existingImagePath = $uploadDir . $existingImageFileName;
                if (file_exists($existingImagePath)) {
                    if (unlink($existingImagePath)) {
                        // File deleted successfully
                        return true;
                    } else {
                        // Failed to delete the file
                        return 'Failed';
                    }
                } else {
                    // File not found, it might have been deleted or moved manually
                    return 'not-found';
                }
            } else {
                // No existing image found
                return 'not-existing';
            }
        } catch (PDOException $e) {
            echo "Error deleting existing image: " . $e->getMessage();
            return false;
        }
    }

    // helper function for formating date
    private static function daySuffix($day)
    {
        if ($day >= 11 && $day <= 13) {
            return 'th';
        }
        switch ($day % 10) {
            case 1:
                return 'st';
            case 2:
                return 'nd';
            case 3:
                return 'rd';
            default:
                return 'th';
        }
    }

    // Function to formate date to gregorian date e.g. (2nd April, 2022)
    public function formatDate($dateTime)
    {
        $date = new DateTime($dateTime);
        $day = $date->format('j');
        $month = $date->format('F');
        $year = $date->format('Y');
        $time = $date->format('h:i A');

        return "{$day}" . self::daySuffix($day) . " {$month}, {$year}. {$time}";
    }
}
