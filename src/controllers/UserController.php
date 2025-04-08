<?php

namespace controllers;

ini_set('display_errors', 1);
error_reporting(E_ALL);


use dal\UserDAOImpl;
use dal\PostVoteDAO;
use utils\SessionManager;
use dal\MessageFromUserDAOImpl;
use dal\PostDAOImpl;
use dal\PostCommentDAOImpl;
use Exception;
use finfo;


class UserController extends BaseController
{
    private $userDAO;
    private $userMsgDAO;
    private $postVoteDAO;
    private $postDAO;
    private $postCommentDAO;
    public function __construct()
    {
        parent::__construct(['/posts', '/403', '/404', '/signup', '/login', '/quorae', '/api/user/top-contributor', '/api/user/moderators', '/api/post/top-contributors', '/api/user/search/{query}']);
        $this->userDAO = new UserDAOImpl();
        $this->userMsgDAO = new MessageFromUserDAOImpl();
        $this->postVoteDAO = new PostVoteDAO();
        $this->postDAO = new PostDAOImpl();
        $this->postCommentDAO = new PostCommentDAOImpl();
    }

    public function contact()
    {
        $currentUser = SessionManager::get('user');
        if ($currentUser === null) {
            SessionManager::set('error', 'You need to login to access this page');
            header("Location: /login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../views/users/contact.php';
        }
    }

    public function sendEmail()
    {
        $currentUser = SessionManager::get('user');
        if ($currentUser === null) {
            SessionManager::set('error', 'You need to login to access this page');
            header("Location: /login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim(htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
            $currentUserId = SessionManager::get('user_id');
            $message = trim(htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'));
            if (empty($title) || empty($message)) {
                SessionManager::set('error', 'Title and message must not be empty');
                header("Location: /contact");
                exit();
            }
            $result = $this->userMsgDAO->insertMessage($title, $message, $currentUserId);

            if ($result) {
                header("Location: /emailsuccess");
            } else {
                header("Location: /emailfail");
            }
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
                $password = trim($_POST['password']);
                $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
                $username = strtolower(str_replace(' ', '', trim($lastName))) . '.' . strtolower(str_replace(' ', '', trim($firstName)));

                $existingUserName = $this->userDAO->getUserByUsername($username);
                if ($existingUserName) {
                    $lastId = $this->userDAO->getLastUserID();
                    $username = $username . "." . ($lastId + 1);
                }
                $email = trim($_POST['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $localEmail = explode('@', $email)[0];
                    $domainEmail = explode('@', $email)[1];
                    if (empty($localEmail) || empty($domainEmail)) {
                        SessionManager::set('error', 'Invalid email format');
                        header("Location: /signup");
                        exit();
                    }
                    if (strlen($localEmail) > 64 || strlen($domainEmail) > 255) {
                        SessionManager::set('error', 'Invalid email format with length greater than 64 or 255 characters');
                        header("Location: /signup");
                        exit();
                    }
                    SessionManager::set('error', 'Invalid email format');
                    header("Location: /signup");
                    exit();
                }

                if ($this->checkExistedEmail($email)) {
                    throw new Exception("Email already exists !");
                }
                error_log("username: " . $username);
                $this->userDAO->createUser($username, $lastName, $firstName, $email, $hashedPassword, NULL, NULL, NULL);
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

    public function getProfileUser($firstName, $lastName, $id)
    {
        return $this->userDAO->getUserByUrl($firstName, $lastName, $id);
    }


    public function userProfile($firstName, $lastName, $id)
    {
        $postController = new PostController();
        $moduleController = new ModuleController();
        $user = $this->getProfileUser($firstName, $lastName, $id);
        $currentUser = SessionManager::get('user');
        if (!$user) {
            header("Location: /404");
            exit();
        }
        $posts = $postController->getPostsByPageByUserId($user->getUserId());
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
                if ($this->checkExistedEmail($email)) {
                    throw new Exception("Email already exists !");
                }
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

                    $maxFileSize = 10 * 1024 * 1024; // 10MB

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
                $profileLink = "/profile/" . $this->removeVietnameseAccents($firstName . "-" . $lastName . "-" . $userID);
                header("Location: " . $profileLink);
                exit();
            } catch (Exception $e) {
                SessionManager::set('error', $e->getMessage());
                $profileLink = "/profile/" . $this->removeVietnameseAccents($firstName . "-" . $lastName . "-" . $userID);
                header("Location: " . $profileLink);
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
        $this->checkPermission('admin'); // only admin can access this page

        //$users = $this->userDAO->getAllUsers();
        require_once __DIR__ . '/../views/admin/manage_users.php';
    }

    public function getTotalUserNums()
    {
        return $this->userDAO->getTotalUserNums();
    }

    public function getUserTopContributor()
    {
        header("Content-Type: application/json");
        // if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        //     header("Location: /404");
        //     exit();
        // }
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['user_ids']) || !is_array($data['user_ids'])) {
            echo json_encode(["success" => false, "message" => "Invalid request: user_ids is required and must be an array"]);
            exit();
        }
        $userIds = $data['user_ids'];

        if (empty($userIds)) {
            echo json_encode(["success" => false, "message" => "Cannot be empty"]);
            exit();
        }

        $users = [];
        foreach ($userIds as $userId) {
            $user = $this->userDAO->getUserById($userId);
            if ($user) {
                $users[] = [
                    'userId' => $user->getUserId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'avatar' => $user->getProfileImage(),
                ];
            }
        }
        if (empty($users)) {
            echo json_encode(["success" => false, "message" => "No user found"]);
            exit();
        }
        echo json_encode(["success" => true, "users" => $users]);
        exit();
    }

    public function getModerators()
    {
        header("Content-Type: application/json");
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header("Location: /404");
            exit();
        }

        $moderators = $this->userDAO->getModerators();
        $users = [];
        foreach ($moderators as $moderator) {
            $users[] = [
                'userId' => $moderator->getUserId(),
                'username' => $moderator->getUsername(),
                'email' => $moderator->getEmail(),
                'firstName' => $moderator->getFirstName(),
                'lastName' => $moderator->getLastName(),
                'avatar' => $moderator->getProfileImage(),
            ];
        }
        echo json_encode(["success" => true, "moderators" => $users]);
    }

    public function search($query)
    {
        header("Content-Type: application/json; charset=UTF-8");

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(["status" => false, "message" => "Invalid request"]);
            return;
        }
        if (empty($query)) {
            echo json_encode(["status" => false, "message" => "No search query provided"]);
            return;
        }

        $search = rawurldecode($query); //"username"
        $search = htmlspecialchars(trim($search), ENT_QUOTES, 'UTF-8');

        $usersFound = $this->getResultSearch($search);

        if (count($usersFound) === 0) {
            echo json_encode(["status" => false, "message" => "No users found"]);
            return;
        }
        $usersArray = array_map(fn($user) => $user->toArray(), $usersFound);

        echo json_encode([
            "status" => true,
            "users" => count($usersArray) === 1 ? $usersArray[0] : $usersArray
        ]);
    }

    public function getResultSearch($query)
    {
        return $this->userDAO->search($query);
    }

    private function removeVietnameseAccents($string)
    {
        $unwanted = [
            'à' => 'a',
            'á' => 'a',
            'ả' => 'a',
            'ã' => 'a',
            'ạ' => 'a',
            'ă' => 'a',
            'ằ' => 'a',
            'ắ' => 'a',
            'ẳ' => 'a',
            'ẵ' => 'a',
            'ặ' => 'a',
            'â' => 'a',
            'ầ' => 'a',
            'ấ' => 'a',
            'ẩ' => 'a',
            'ẫ' => 'a',
            'ậ' => 'a',
            'è' => 'e',
            'é' => 'e',
            'ẻ' => 'e',
            'ẽ' => 'e',
            'ẹ' => 'e',
            'ê' => 'e',
            'ề' => 'e',
            'ế' => 'e',
            'ể' => 'e',
            'ễ' => 'e',
            'ệ' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'ỉ' => 'i',
            'ĩ' => 'i',
            'ị' => 'i',
            'ò' => 'o',
            'ó' => 'o',
            'ỏ' => 'o',
            'õ' => 'o',
            'ọ' => 'o',
            'ô' => 'o',
            'ồ' => 'o',
            'ố' => 'o',
            'ổ' => 'o',
            'ỗ' => 'o',
            'ộ' => 'o',
            'ơ' => 'o',
            'ờ' => 'o',
            'ớ' => 'o',
            'ở' => 'o',
            'ỡ' => 'o',
            'ợ' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'ủ' => 'u',
            'ũ' => 'u',
            'ụ' => 'u',
            'ư' => 'u',
            'ừ' => 'u',
            'ứ' => 'u',
            'ử' => 'u',
            'ữ' => 'u',
            'ự' => 'u',
            'ỳ' => 'y',
            'ý' => 'y',
            'ỷ' => 'y',
            'ỹ' => 'y',
            'ỵ' => 'y',
            'đ' => 'd',
            'À' => 'A',
            'Á' => 'A',
            'Ả' => 'A',
            'Ã' => 'A',
            'Ạ' => 'A',
            'Ă' => 'A',
            'Ằ' => 'A',
            'Ắ' => 'A',
            'Ẳ' => 'A',
            'Ẵ' => 'A',
            'Ặ' => 'A',
            'Â' => 'A',
            'Ầ' => 'A',
            'Ấ' => 'A',
            'Ẩ' => 'A',
            'Ẫ' => 'A',
            'Ậ' => 'A',
            'È' => 'E',
            'É' => 'E',
            'Ẻ' => 'E',
            'Ẽ' => 'E',
            'Ẹ' => 'E',
            'Ê' => 'E',
            'Ề' => 'E',
            'Ế' => 'E',
            'Ể' => 'E',
            'Ễ' => 'E',
            'Ệ' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Ỉ' => 'I',
            'Ĩ' => 'I',
            'Ị' => 'I',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ỏ' => 'O',
            'Õ' => 'O',
            'Ọ' => 'O',
            'Ô' => 'O',
            'Ồ' => 'O',
            'Ố' => 'O',
            'Ổ' => 'O',
            'Ỗ' => 'O',
            'Ộ' => 'O',
            'Ơ' => 'O',
            'Ờ' => 'O',
            'Ớ' => 'O',
            'Ở' => 'O',
            'Ỡ' => 'O',
            'Ợ' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Ủ' => 'U',
            'Ũ' => 'U',
            'Ụ' => 'U',
            'Ư' => 'U',
            'Ừ' => 'U',
            'Ứ' => 'U',
            'Ử' => 'U',
            'Ữ' => 'U',
            'Ự' => 'U',
            'Ỳ' => 'Y',
            'Ý' => 'Y',
            'Ỷ' => 'Y',
            'Ỹ' => 'Y',
            'Ỵ' => 'Y',
            'Đ' => 'D'
        ];
        return strtr($string, $unwanted);
    }
}
