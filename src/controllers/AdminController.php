<?php

namespace controllers;

use dal\UserDAOImpl;
use Exception;
use finfo;
use utils\SessionManager;
use dal\PostDAOImpl;
use dal\PostVoteDAO;
use dal\PostCommentDAOImpl;

class AdminController extends BaseController
{
    private $userDAO;
    private $moduleController;
    private $postVoteDAO;
    private $postDAO;
    private $postCommentDAO;
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
        $this->postCommentDAO = new PostCommentDAOImpl();
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
        $postController = new PostController();
        $moduleController = new ModuleController();
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
        $comments = [];
        foreach ($posts as $post) {
            $comments[$post->getPostId()] = $this->postCommentDAO->getCommentsByPostId($post->getPostId());
        }

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
        $currentPage = $_POST['page'] ?? 1;
        $offset = ($currentPage - 1) * 10;
        $users = $this->userDAO->getUserPerPage(10, $offset);
        require_once __DIR__ . '/../views/admin/user-management.php';
    }

    public function moduleManagement()
    {
        require_once __DIR__ . '/../views/admin/module-management.php';
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

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header("Location: /admin/module-management");
            SessionManager::set('message', "Invalid request method");
            exit();
        }

        try {
            $result = $this->moduleController->deleteModule($moduleId);
            if ($result) {
                header("Location: /admin/module-management");
                //SessionManager::set('message', "Module deleted successfully");
                exit();
            } else {
                throw new Exception("Failed to delete module");
            }
        } catch (\Throwable $th) {
            header("Location: /admin/module-management");
            SessionManager::set('message', $th->getMessage());
            exit();
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
                // header("Location: /admin/module-management");
            } else {
                throw new Exception("Failed to create module");
            }
        } catch (\Throwable $th) {
            echo json_encode(["status" => false, "message" => $th->getMessage()]);
        }
    }

    public function banuser($userId)
    {
        $currentUser = SessionManager::get('user');
        if (!$currentUser || $currentUser->getRole() !== 'admin') {
            header("Location: /403");
            exit();
        }

        try {

            $result = $this->userDAO->updateUserStatus($userId);

            if ($result) {
                header("Location: /admin/user-management");
                exit();
            } else {
                throw new Exception("Failed to update user status");
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            SessionManager::set('message', $message);
            header("Location: /admin/user-management");
            exit();
        }
    }

    public function unbanuser($userId)
    {
        $currentUser = SessionManager::get('user');
        if (!$currentUser || $currentUser->getRole() !== 'admin') {
            header("Location: /403");
            exit();
        }

        try {

            $result = $this->userDAO->updateUserStatusUnbanned($userId);

            if ($result) {
                header("Location: /admin/user-management");
                exit();
            } else {
                throw new Exception("Failed to update user status");
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            SessionManager::set('message', $message);
            header("Location: /admin/user-management");
            exit();
        }
    }

    public function updateRole($userId)
    {
        $currentUser = SessionManager::get('user');
        if (!$currentUser || $currentUser->getRole() !== 'admin') {
            header("Location: /403");
            exit();
        }

        try {
            $oldRoleOfUser = $this->userDAO->getUserById($userId)->getRole();
            $role = $oldRoleOfUser === 'admin' ? 'user' : 'admin';
            $result = $this->userDAO->updateUserRole($userId, $role);

            if ($result) {
                SessionManager::set('message', "User role updated successfully");
                header("Location: /admin/user-management");
                exit();
            } else {
                throw new Exception("Failed to update user role");
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            SessionManager::set('message', $message);
            header("Location: /admin/user-management");
            exit();
        }
    }
}
