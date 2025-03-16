<?php

namespace controllers;

use dal\UserDAOImpl;
use Exception;
use finfo;

class AdminController
{
    private $userDAO;
    public function __construct()
    {
        $this->userDAO = new UserDAOImpl();
    }
    public function dashboard()
    {
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function userProfile($firstName, $lastName, $userID)
    {
        $user = $this->userDAO->getUserById($userID);
        require_once __DIR__ . '/../views/admin/profile.php';
    }

    public function update($userID)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                error_log("Store method called");
                $user = $this->userDAO->getUserById($userID);
                if (!$user) {
                    throw new Exception("User not found");
                }
                $currentFirstName = $user->getFirstName();
                $currentLastName = $user->getLastName();
                $currentEmail = $user->getEmail();
                // Validate input
                $firstName = !empty($_POST['firstName'])
                    ? trim(htmlspecialchars($_POST['firstName'], ENT_QUOTES, 'UTF-8'))
                    : $currentFirstName;

                $lastName = !empty($_POST['lastName'])
                    ? trim(htmlspecialchars($_POST['lastName'], ENT_QUOTES, 'UTF-8'))
                    : $currentLastName;

                $email = !empty($_POST['email'])
                    ? trim($_POST['email'])
                    : $currentEmail;
                $bio = trim(htmlspecialchars($_POST['bio'] ?? '', ENT_QUOTES, 'UTF-8'));
                $username = $this->convertUsername($firstName, $lastName);
                $profileImagePath = $user->getProfileImage();

                if (isset($_POST['dob']) && !empty($_POST['dob'])) {
                    $dob = $_POST['dob'];

                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
                        die("Incorrect format YYYY-MM-DD.");
                    }

                    list($year, $month, $day) = explode('-', $dob);
                    if (!checkdate((int)$month, (int)$day, (int)$year)) {
                        die("Invalid date.");
                    }
                }
                // process upload file
                if (!empty($_FILES['image']['name'])) {
                    $uploadDir = __DIR__ . '/../../uploads/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $maxFileSize = 100 * 1024 * 1024; // 100MB

                    // Check error when uploading file
                    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                        throw new Exception("File upload error: " . $_FILES['image']['error']);
                    }

                    // Check real MIME type
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($_FILES['image']['tmp_name']);
                    $extensions = [
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/gif'  => 'gif'
                    ];

                    $extension = $extensions[$mime] ?? null;
                    if (!$extension) {
                        throw new Exception("Only JPG, PNG, GIF files are allowed");
                    }

                    // check the size of file image
                    if ($_FILES['image']['size'] > $maxFileSize) {
                        throw new Exception("File size must not exceed 100MB");
                    }

                    // make the unique filename
                    do {
                        $profileImageKey = bin2hex(random_bytes(16));
                        $fileName = $profileImageKey . '.' . $extension;
                        $filePath = $uploadDir . $fileName;
                    } while (file_exists($filePath));

                    // move file to uploads
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        throw new Exception("Failed to upload image");
                    }
                    $profileImagePath = 'uploads/' . $fileName;
                }

                error_log("Final profileImagePath before updating DB: " . $profileImagePath);

                $this->userDAO->updateProfile($userID, $username, $firstName, $lastName, $email, $profileImagePath, $bio, $dob);
                error_log("updateProfile called with userId: $userID, username: $username, firstName: $firstName, lastName: $lastName, email: $email, profileImage: $profileImagePath, bio: $bio");
                //$_SESSION['success'] = "User profile has been updated successfully!";
                header("Location: /admin/profile/" . $firstName . "-" . $lastName . "-" . $userID);
                exit();
            } catch (Exception $e) {
                error_log("Error in store method: " . $e->getMessage());
                $_SESSION['error'] = $e->getMessage();
                header("Location: /404");
                exit();
            }
        }
    }
    private function convertUsername($firstName, $lastName)
    {
        $username = strtolower(str_replace(' ', '', trim($lastName))) . '.' . strtolower(str_replace(' ', '', trim($firstName)));
        $username = $username . '.' . ($this->userDAO->getLastUserID() + 1);
        return $username;
    }

    public function updateAvatar($userId)
    {
        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "message" => "Invalid request"]);
            exit();
        }

        try {
            $user = $this->userDAO->getUserById($userId);
            if (!$user) {
                throw new Exception("User not found");
            }

            $profileImagePath = $user->getProfileImage();

            if (!empty($_FILES['image']['name'])) {
                $uploadDir = __DIR__ . '/../../uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $maxFileSize = 100 * 1024 * 1024; // 100MB

                if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception("File upload error: " . $_FILES['image']['error']);
                }

                // Check MIME type
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($_FILES['image']['tmp_name']);
                $extensions = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/gif'  => 'gif'
                ];

                $extension = $extensions[$mime] ?? null;
                if (!$extension) {
                    throw new Exception("Only JPG, PNG, GIF files are allowed");
                }

                if ($_FILES['image']['size'] > $maxFileSize) {
                    throw new Exception("File size must not exceed 100MB");
                }

                // Unique filename
                do {
                    $profileImageKey = bin2hex(random_bytes(16));
                    $fileName = $profileImageKey . '.' . $extension;
                    $filePath = $uploadDir . $fileName;
                } while (file_exists($filePath));

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                    throw new Exception("Failed to upload image");
                }

                $profileImagePath = 'uploads/' . $fileName;
                $this->userDAO->updateProfileImage($userId, $profileImagePath);
            }

            echo json_encode(["success" => true, "newAvatarPath" => $profileImagePath]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }
}
