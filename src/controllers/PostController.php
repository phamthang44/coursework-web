<?php

namespace controllers;

use dal\ModuleDAOImpl;
use dal\PostAssetDAOImpl;
use dal\PostDAOImpl;
use utils\SessionManager;
use Exception;
use finfo;
use Random\Engine\Secure;

class PostController extends BaseController
{
    private $postDAO;
    private $moduleDAO;
    private $postAssetDAO;
    private $userController;
    //private $postAssetController;
    function __construct()
    {
        parent::__construct(['/posts', '/quorae', '/login', '/404', '/403', '/signup']);
        $this->postDAO = new PostDAOImpl();
        $this->moduleDAO = new ModuleDAOImpl();
        $this->postAssetDAO = new PostAssetDAOImpl();
        $this->userController = new UserController();
        //$this->postAssetController = new PostAssetController();
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        $postsPerPage = 10;
        $modules = $this->moduleDAO->getAllModules();

        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $offset = ($currentPage - 1) * $postsPerPage;
        $data = $this->postDAO->getPostByPage($postsPerPage, $offset);

        $posts = $data['posts'];
        $totalPosts = $data['totalPosts'];

        $totalPages = ceil($totalPosts / $postsPerPage);
        //all postIds
        $postIds = array_map(function ($post) {
            return $post->getPostId();
        }, $posts);

        //take all assets for post_id
        $assetsByPostId = $this->postAssetDAO->getByPostIds($postIds);

        $postsData = [];

        foreach ($posts as $post) {
            $postId = $post->getPostId();
            $postsData[] = [
                'post' => $post,
                'assets' => $assetsByPostId[$postId] ?? []
            ];
        }

        require_once __DIR__ . '/../views/posts/post.php';
    }

    //CRUD first
    public function create()
    {
        $modules = $this->moduleDAO->getAllModules();
        require_once __DIR__ . '/../views/posts/createpost.php';
    }

    public function viewPost($postId)
    {
        try {
            $postDAO = new PostDAOImpl();
            $postAssetService = new PostAssetDAOImpl();

            // Take the information of post
            $post = $postDAO->getPost($postId);
            if (!$post) {
                SessionManager::set('error', "Post not found");
                header("Location: /posts");
                exit();
            }

            // Get the list asset
            $assets = $postAssetService->getByPostId($postId);

            // Move the data into view
            require_once __DIR__ . '/../views/posts/viewpost.php';
        } catch (Exception $e) {
            SessionManager::set('error', $e->getMessage());
            header("Location: /posts");
            exit();
        }
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate input
                $title = trim(htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8'));
                $content = trim(htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8'));

                if (empty($content)) {
                    throw new Exception("Title and content are required");
                }

                // Create post and take id
                $userId = $_POST['user_id'];
                $moduleId = htmlspecialchars($_POST['module'] ?? '', ENT_QUOTES, 'UTF-8');
                if (empty($moduleId)) {
                    throw new Exception("Module is required");
                }
                $postId = $this->postDAO->createPost($title, $content, $userId, $moduleId);
                $imagePath = null;
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
                        $media_key = bin2hex(random_bytes(16));
                        $fileName = $media_key . '.' . $extension;
                        $filePath = $uploadDir . $fileName;
                    } while (file_exists($filePath));
                    // move file to uploads
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        throw new Exception("Failed to upload image");
                    }
                    $imagePath = 'uploads/' . $fileName;
                    $this->postAssetDAO->create($imagePath, $postId);
                }

                $referer = $_SERVER["HTTP_REFERER"] ?? "/posts";
                SessionManager::set("success", "The post has been created successfully!");
                if (strpos($referer, "profile") !== false) {
                    header("Location: " . $referer);
                } else {
                    header("Location: /posts");
                }
                exit();
            } catch (Exception $e) {
                SessionManager::set('error', $e->getMessage());
                header("Location: /posts");
                exit();
            }
        }
    }
    public function edit($postId)
    {
        $postUserId = $this->getPostUserId($postId);
        $isOwner = $this->currentUser->getUserId() === $postUserId;
        $isAdmin =  $this->currentUser->getRole() === 'admin';
        $currentRoute = $_SERVER['REQUEST_URI'];
        if (!$isOwner && !$isAdmin && $currentRoute === '/posts/edit/' . $postId) {
            SessionManager::set("error", "You are not authorized to edit this post");
            header("Location: /posts");
            exit();
        }
        try {
            $post = $this->postDAO->getPost($postId);
            if (!$post) {
                SessionManager::set("error", "Post not found");
                header("Location: /posts");
                exit();
            }

            $modules = $this->moduleDAO->getAllModules();
            require_once __DIR__ . '/../views/posts/updatepost.php';
        } catch (Exception $e) {
            SessionManager::set("Error in edit method: ", $e->getMessage());
            header("Location: /404");
            exit();
        }
    }

    public function update($postId)
    {
        $postUserId = $this->getPostUserId($postId);
        $isOwner = $this->currentUser->getUserId() === $postUserId;
        $isAdmin = $this->currentUser->getRole() === 'admin';
        $currentRoute = $_SERVER['REQUEST_URI'];
        if (!$isOwner && !$isAdmin && $currentRoute !== '/posts') {
            SessionManager::set("error", "You are not authorized to update this post");
            header("Location: /posts");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                error_log("Store method called");
                // Validate input

                $newTitle = trim(htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8'));
                $newContent = trim(htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8'));
                $newModule = htmlspecialchars($_POST['module'] ?? '', ENT_QUOTES, 'UTF-8');
                if (empty($newModule)) {
                    throw new Exception("Module is required");
                }

                if (empty($newContent)) {
                    throw new Exception("Title and content are required");
                }

                error_log("Title and content validated successfully");

                //take the old post
                $post = $this->postDAO->getPost($postId);

                $oldTitle = $post->getTitle();
                $oldContent = $post->getContent();
                $oldModule = $post->getModuleId();

                if ($newTitle === $oldTitle && $newContent === $oldContent && $newModule === $oldModule) {
                    throw new Exception("No changes were made");
                }

                $this->postDAO->updatePost($postId, $newTitle, $newContent, $newModule);

                if (!$post) {
                    throw new Exception("Post not found");
                }

                error_log("Post created successfully");
                $imageObj = $this->postAssetDAO->getByPostId($postId);
                if (!$imageObj) {
                    $imagePath = null;
                } else {
                    $imagePath = $imageObj->getMediaKey();
                }

                if (!empty($_FILES['image']['name']) && $imagePath === null) {
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
                        $media_key = bin2hex(random_bytes(16));
                        $fileName = $media_key . '.' . $extension;
                        $filePath = $uploadDir . $fileName;
                    } while (file_exists($filePath));

                    // move file to uploads
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        throw new Exception("Failed to upload image");
                    }

                    $imagePath = 'uploads/' . $fileName;
                    $this->postAssetDAO->create($imagePath, $postId);
                    error_log("Image uploaded successfully: " . $imagePath);
                } else {
                    $this->postAssetDAO->update($post->getPostId(), $imagePath);
                }
                SessionManager::set('success', "Post updated successfully");
                header("Location: /posts");
                exit();
            } catch (Exception $e) {
                error_log("Error in update method: " . $e->getMessage());
                SessionManager::set('error', $e->getMessage());
                header("Location: /500.php");
                exit();
            }
        }
    }

    public function delete($postId)
    {
        $postUserId = $this->getPostUserId($postId);
        $isOwner = $this->currentUser->getUserId() === $postUserId;
        $isAdmin = $this->currentUser->getRole() === 'admin';
        $currentRoute = $_SERVER['REQUEST_URI'];
        if (!$isOwner && !$isAdmin && $currentRoute !== '/posts') {
            SessionManager::set("error", "You are not authorized to delete this post");
            header("Location: /posts");
            exit();
        }
        try {
            $post = $this->postDAO->getPost($postId);
            if (!$post) {
                SessionManager::set("error", "Post not found");
                header("Location: /posts");
                exit();
            }

            $this->postDAO->deletePost($postId);
            SessionManager::set("success", "Post deleted successfully");
            header("Location: /posts");
            exit();
        } catch (Exception $e) {
            error_log("Error in delete method: " . $e->getMessage());
            SessionManager::set("error", $e->getMessage());
            header("Location: /posts");
            exit();
        }
    }

    public function getPostById($postId)
    {
        return $this->postDAO->getPost($postId);
    }

    public function getPostsByUserId($userId)
    {
        return $this->postDAO->getPostsByUserId($userId);
    }

    public function getPostByIdAndUserId($postId, $userId)
    {
        return $this->postDAO->getPostByIdAndUserId($postId, $userId);
    }

    public function getPostUserId($postId)
    {
        $post = $this->postDAO->getPost($postId);
        return $post->getUserId();
    }

    public function getUserName($userId)
    {
        $user = $this->userController->getUser($userId);
        return $user->getUsername();
    }

    public function getModuleName($moduleId)
    {
        $module = $this->moduleDAO->getModule($moduleId);
        return $module->getModuleName();
    }

    public function getPostImage($postId)
    {
        return $this->postAssetDAO->getByPostId($postId);
    }

    public function getUser($userId)
    {
        return $this->userController->getUser($userId);
    }

    public function getTotalPostsOfUser($userId): int
    {
        $totalPostsOfUser = $this->postDAO->getPostsByUserId($userId);
        return count($totalPostsOfUser);
    }

    public function getCurrentPage()
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        return $currentPage;
    }

    public function getPostsByPageByUserId($userId)
    {
        $postsPerPage = 3;
        $currentPage = $this->getCurrentPage();
        $offset = ($currentPage - 1) * $postsPerPage;
        return $this->postDAO->getPostByPageByUserId($postsPerPage, $offset, $userId);
    }

    public function getTotalPages($userId)
    {
        $postsPerPage = 3;
        $totalPostsUsers = $this->getTotalPostsOfUser($userId);
        $totalPagesPostsOfUser = ceil($totalPostsUsers / $postsPerPage);
        return $totalPagesPostsOfUser;
    }

    public function getTotalPagesAllPosts()
    {
        $postsPerPage = 10;
        $totalPosts = $this->postDAO->getAllPosts();
        $totalPages = ceil(count($totalPosts) / $postsPerPage);
        return $totalPages;
    }

    public function getPostsByPage($postsPerPage, $offset)
    {
        return $this->postDAO->getPostByPage($postsPerPage, $offset);
    }
    /*
        protected function render($view, $data = [])
    {
        extract($data);
        require_once __DIR__ . "/../views/posts/$view.php";
    }
        public function index()
    {
        $this->render('post', [
            'posts' => $this->postDAO->getAllPosts(),
            'modules' => $this->moduleDAO->getAllModules()
        ]);
    }

    public function create()
    {
        $this->render('createpost', [
            'posts' => $this->postDAO->getAllPosts(),
            'modules' => $this->moduleDAO->getAllModules()
        ]);
    }

    //this way look closely to framework Laravel

     */
}
