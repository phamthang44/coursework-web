<?php

namespace controllers;

ini_set('display_errors', 1);
error_reporting(E_ALL);


use dal\UserDAOImpl;
use utils\SessionManager;
use Exception;
use finfo;


class UserController extends BaseController
{
    private $userDAO;

    public function __construct()
    {
        parent::__construct(['/posts', '/403', '/404', '/signup', '/login']);
        $this->userDAO = new UserDAOImpl();
    }

    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            SessionManager::set('error', $e->getMessage());
            header("Location: /signup");
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
        $currentUser = SessionManager::get('user');
        if (!$user) {
            header("Location: /404");
            exit();
        }

        $isOwner = $currentUser && method_exists($currentUser, 'getUserId') && $currentUser->getUserId() === $user->getUserId();
        require_once __DIR__ . '/../views/users/profile.php';
    }

    public function update($userID)
    {
        $currentUserId = SessionManager::get('user_id');

        if ($currentUserId != $userID && SessionManager::get('role') !== 'admin') {
            header("Location: /403");
            exit();
        }
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
                $this->userDAO->updateProfile($userID, $username, $firstName, $lastName, $email, $profileImagePath, $bio, $dob);

                SessionManager::set('success', 'User profile has been updated successfully!');
                header("Location: /profile/" . $firstName . "-" . $lastName . "-" . $userID);
                exit();
            } catch (Exception $e) {
                SessionManager::set('error', $e->getMessage());
                header("Location: /profile/" . $firstName . "-" . $lastName . "-" . $userID);
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
        $currentUserId = SessionManager::get('user_id');

        if ($currentUserId != $userId && SessionManager::get('role') !== 'admin') {
            header("Location: /403");
            exit();
        }

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
    public function updateBio($userId)
    {
        $currentUserId = SessionManager::get('user_id');

        if ($currentUserId != $userId && SessionManager::get('role') !== 'admin') {
            header("Location: /403");
            exit();
        }
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
            $bio = trim(htmlspecialchars($_POST['bio-update'] ?? '', ENT_QUOTES, 'UTF-8'));
            if (empty($bio)) {
                throw new Exception("Bio is empty");
            }
            $this->userDAO->updateBio($userId, $bio);
            $user->setBio($bio);
            $newBio = $user->getBio();
            echo json_encode(["success" => true, "newBio" => $newBio]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
    }

    private function checkPermission($requiredRole)
    {
        $userRole = SessionManager::get('role');

        if ($userRole !== $requiredRole) {
            header("Location: /403");
            exit();
        }
    }
    public function manageUsers()
    {
        $this->checkPermission('admin'); // Chỉ admin mới truy cập được

        //$users = $this->userDAO->getAllUsers();
        require_once __DIR__ . '/../views/admin/manage_users.php';
    }

    public function getTotalUserNums()
    {
        return $this->userDAO->getTotalUserNums();
    }
}
