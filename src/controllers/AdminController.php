<?php

namespace controllers;

use dal\UserDAOImpl;
use Exception;
use finfo;
use utils\SessionManager;
use dal\PostDAOImpl;
use dal\PostVoteDAO;

class AdminController extends BaseController
{
    private $userDAO;
    private $moduleController;
    private $postVoteDAO;
    private $postDAO;
    public function __construct()
    {
        parent::__construct(['/posts']);
        $this->userDAO = new UserDAOImpl();
        $this->moduleController = new ModuleController();
        if ($this->currentUser && $this->currentUser->getRole() !== 'admin') {
            header("Location: /403");
            exit();
        }
        $this->postVoteDAO = new PostVoteDAO();
        $this->postDAO = new PostDAOImpl();
    }
    public function dashboard()
    {
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function getUser($userID)
    {
        return $this->userDAO->getUserById($userID);
    }

    public function userProfile($firstName, $lastName, $userID)
    {
        $user = $this->getUser($userID);
        $currentUser = SessionManager::get('user');
        if (!$user) {
            header("Location: /404");
            exit();
        }
        $posts = $this->postDAO->getPostsByUserId($user->getUserId());
        $voteScores = [];
        foreach ($posts as $post) {
            $voteScores[$post->getPostId()] = $this->postVoteDAO->getVoteScore($post->getPostId());
        }
        $postVoteDAO = $this->postVoteDAO;
        $isOwner = $currentUser && method_exists($currentUser, 'getUserId') && $currentUser->getUserId() === $user->getUserId();
        require_once __DIR__ . '/../views/admin/profile.php';
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

    public function  userManagement()
    {
        require_once __DIR__ . '/../views/admin/user_management.php';
    }

    public function moduleManagement()
    {
        require_once __DIR__ . '/../views/admin/module_management.php';
    }

    public function updateModule($moduleId)
    {
        $currentUser = SessionManager::get('user');
        if (!$currentUser || $currentUser->getRole() !== 'admin') {
            header("Location: /403");
            exit();
        }

        header("Content-Type: application/json");


        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "message" => "Invalid request"]);
            exit();
        }

        try {
            $moduleName = trim(htmlspecialchars($_POST['moduleName'] ?? '', ENT_QUOTES, 'UTF-8'));
            $moduleDescription = trim(htmlspecialchars($_POST['moduleDescription'] ?? '', ENT_QUOTES, 'UTF-8'));

            if (empty($moduleName) || empty($moduleDescription) || empty($moduleId)) {
                throw new Exception("Module name, description and ID are required");
            }
            $result = $this->moduleController->updateModule($moduleName, $moduleDescription, $moduleId);

            if ($result) {
                $module = $this->moduleController->getModuleById($moduleId);
                error_log(print_r($module, true));
                echo json_encode([
                    "status" => true,
                    "module" => $module->toArray()
                ]);
            } else {
                throw new Exception("Failed to update module");
            }
        } catch (\Throwable $th) {
            echo json_encode(["status" => false, "message" => $th->getMessage()]);
        }
    }

    public function deleteModule($moduleId)
    {
        $currentUser = SessionManager::get('user');
        if (!$currentUser || $currentUser->getRole() !== 'admin') {
            header("Location: /403");
            exit();
        }

        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "message" => "Invalid request"]);
            exit();
        }

        try {
            $result = $this->moduleController->deleteModule($moduleId);
            if ($result) {
                echo json_encode(["status" => true]);
            } else {
                throw new Exception("Failed to delete module");
            }
        } catch (\Throwable $th) {
            echo json_encode(["status" => false, "message" => $th->getMessage()]);
        }
    }

    public function createModule()
    {
        $currentUser = SessionManager::get('user');
        if (!$currentUser || $currentUser->getRole() !== 'admin') {
            header("Location: /403");
            exit();
        }

        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["success" => false, "message" => "Invalid request"]);
            exit();
        }

        try {
            $moduleName = trim(htmlspecialchars($_POST['moduleName'] ?? '', ENT_QUOTES, 'UTF-8'));
            $moduleDescription = trim(htmlspecialchars($_POST['moduleDescription'] ?? '', ENT_QUOTES, 'UTF-8'));

            if (empty($moduleName) || empty($moduleDescription)) {
                throw new Exception("Module name and description are required");
            }

            $result = $this->moduleController->createModule($moduleName, $moduleDescription);

            if ($result['success']) {
                $module = $this->moduleController->getModuleById($result['moduleId']);
                echo json_encode([
                    "status" => true,
                    "module" => $module->toArray()
                ]);
            } else {
                throw new Exception("Failed to create module");
            }
        } catch (\Throwable $th) {
            echo json_encode(["status" => false, "message" => $th->getMessage()]);
        }
    }
}
