<?php

namespace controllers;

use dal\ModuleDAOImpl;
use dal\PostAssetDAOImpl;
use dal\PostDAOImpl;
use Exception;
use finfo;

class PostController
{
    private $postDAO;
    private $moduleDAO;
    private $postAssetDAO;
    private $userController;
    function __construct()
    {
        $this->postDAO = new PostDAOImpl();
        $this->moduleDAO = new ModuleDAOImpl();
        $this->postAssetDAO = new PostAssetDAOImpl();
        $this->userController = new UserController();
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        //all posts object as an array
        $posts = $this->postDAO->getAllPosts();
        $modules = $this->moduleDAO->getAllModules();

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
        // $post = $postsData[0]['post'];
        // $assets = $postsData[0]['assets'];
        // echo "Welcome to the homepage!";

        require_once __DIR__ . '/../views/posts/post.php';
    }

    //CRUD first
    public function create()
    {
        //http://localhost/index.php?action=create

        // If not POST request, show form
        $posts = $this->postDAO->getAllPosts();
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
                $_SESSION['error'] = "Post not found";
                header("Location: /posts");
                exit();
            }

            // Get the list asset
            $assets = $postAssetService->getByPostId($postId);

            // Move the data into view
            require_once __DIR__ . '/../views/posts/viewpost.php';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: /posts");
            exit();
        }
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                error_log("Store method called");

                // Validate input
                $title = trim(htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8'));
                $content = trim(htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8'));

                if (empty($content)) {
                    throw new Exception("Title and content are required");
                }

                error_log("Title and content validated successfully");

                // Create post and take id
                $userId = $_POST['user_id']; // Hard-coded tạm thời
                $moduleId = htmlspecialchars($_POST['module'] ?? '', ENT_QUOTES, 'UTF-8');
                if (empty($moduleId)) {
                    throw new Exception("Module is required");
                }
                $postId = $this->postDAO->createPost($title, $content, $userId, $moduleId);
                error_log("Post created successfully");

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
                    error_log("Image uploaded successfully: " . $imagePath);
                }

                $_SESSION['success'] = "The post has been created successfully!";
                header("Location: /posts");
                exit();
            } catch (Exception $e) {
                error_log("Error in store method: " . $e->getMessage());
                $_SESSION['error'] = $e->getMessage();
                header("Location: /posts");
                exit();
            }
        }
    }
    public function edit($postId)
    {
        try {
            $post = $this->postDAO->getPost($postId);
            if (!$post) {
                $_SESSION['error'] = "Post not found";
                header("Location: /posts");
                exit();
            }

            $modules = $this->moduleDAO->getAllModules();
            require_once __DIR__ . '/../views/posts/updatepost.php';
        } catch (Exception $e) {
            error_log("Error in edit method: " . $e->getMessage());
            header("Location: /posts");
            exit();
        }
    }

    public function update($postId)
    {
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

                if ($newModule !== $oldModule && $newTitle === $oldTitle && $newContent === $oldContent) {
                    $this->postDAO->updatePostModule($postId, $newModule);
                } else if ($newModule === $oldModule && $newTitle !== $oldTitle && $newContent === $oldContent) {
                    $this->postDAO->updatePostTitle($postId, $newTitle);
                } else if ($newModule === $oldModule && $newTitle === $oldTitle && $newContent !== $oldContent) {
                    $this->postDAO->updatePostContent($postId, $newContent);
                } else {
                    $this->postDAO->updatePost($postId, $newTitle, $newContent, $newModule);
                }
                if (!$post) {
                    throw new Exception("Post not found");
                }


                error_log("Post created successfully");

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
                    $this->postAssetDAO->update($post->getPostId(), $imagePath);
                    error_log("Image uploaded successfully: " . $imagePath);
                }

                $_SESSION['success'] = "The post has been created successfully!";
                header("Location: /posts");
                exit();
            } catch (Exception $e) {
                error_log("Error in store method: " . $e->getMessage());
                $_SESSION['error'] = $e->getMessage();
                header("Location: /posts");
                exit();
            }
        }
    }

    public function delete($postId)
    {
        try {
            $post = $this->postDAO->getPost($postId);
            if (!$post) {
                $_SESSION['error'] = "Post not found";
                header("Location: /posts");
                exit();
            }

            $this->postDAO->deletePost($postId);
            $_SESSION['success'] = "Post has been deleted successfully!";
            header("Location: /posts");
            exit();
        } catch (Exception $e) {
            error_log("Error in delete method: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header("Location: /posts");
            exit();
        }
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
