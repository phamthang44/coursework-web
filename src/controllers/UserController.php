<?php

namespace controllers;

ini_set('display_errors', 1);
error_reporting(E_ALL);


use dal\UserDAOImpl;

use Exception;
use finfo;


class UserController
{
    private $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAOImpl();
    }

    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //$email = '';
            // $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            // $dotenv->load();
            ini_set('SMTP', 'smtp.gmail.com');
            ini_set('smtp_port', 587);
            ini_set('sendmail_from', 'phamthang5331@gmail.com');
            $subject = $_POST['title'];
            $message = $_POST['content'];
            $to = 'phamthang5331@gmail.com';
            $headers = "From: no-reply@localhost\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            if (mail($to, $subject, $message, $headers)) {
                header("Location: /success");
            } else {
                echo "❌ Error in sending email.";
            }
        } else {
            require_once __DIR__ . '/../views/users/contact.php';
        }
    }

    public function emailsuccess()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../views/success/sendmailsucess.php';
        }
    }

    public function emailfail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../views/errors/sendmailfail.php';
        }
    }

    public function signup()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $firstName = trim(htmlspecialchars($_POST['firstName'], ENT_QUOTES, 'UTF-8'));
                $lastName = trim(htmlspecialchars($_POST['lastName'], ENT_QUOTES, 'UTF-8'));
                $password = trim(htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'));
                $username = strtolower(str_replace(' ', '', trim($lastName))) . '.' . strtolower(str_replace(' ', '', trim($firstName)));

                $existingUserName = $this->userDAO->getUserByUsername($username);
                if ($existingUserName) {
                    $lastId = $this->userDAO->getLastUserID();
                    $username = $username . "." . ($lastId + 1);
                }
                $email = trim($_POST['email']);

                if ($this->checkExistedEmail($email)) {
                    throw new Exception("Email already exists");
                }

                $this->userDAO->createUser($username, $lastName, $firstName, $email, $password, NULL, NULL, NULL);
                header("Location: /login");
            } else {
                $file = dirname(__DIR__) . "/views/users/signup.php";
                if (file_exists($file)) {
                    include($file);
                } else {
                    die("File signup.php not found!");
                }
            }
        } catch (Exception $e) {
            header("Location: /signup?error=" . urlencode($e->getMessage()));
            exit();
        }
    }

    public function checkExistedEmail($email): bool
    {
        $emailExists = $this->userDAO->checkExistedEmail($email);
        if ($emailExists > 1) {
            return true;
        }
        return false;
    }

    public function getUser($userID)
    {
        return $this->userDAO->getUserById($userID);
    }

    public function userProfile($firstName, $lastName, $id)
    {
        $user = $this->getUser($id);
        require_once __DIR__ . '/../views/users/profile.php';
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
                // Log để kiểm tra trước khi update
                error_log("Final profileImagePath before updating DB: " . $profileImagePath);

                $this->userDAO->updateProfile($userID, $username, $firstName, $lastName, $email, $profileImagePath, $bio);
                error_log("updateProfile called with userId: $userID, username: $username, firstName: $firstName, lastName: $lastName, email: $email, profileImage: $profileImagePath, bio: $bio");
                //$_SESSION['success'] = "User profile has been updated successfully!";
                header("Location: /profile/" . $firstName . "-" . $lastName . "-" . $userID);
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
}
