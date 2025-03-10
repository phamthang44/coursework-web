<?php

namespace controllers;

ini_set('display_errors', 1);
error_reporting(E_ALL);

use DAO\ModuleDAOImpl;
use DAO\PostAssetDAOImpl;
use DAO\PostDAOImpl;
use Exception;


class PostController
{
    private $postDAO;
    private $moduleDAO;
    private $postAssetDAO;
    function __construct()
    {
        $this->postDAO = new PostDAOImpl();
        $this->moduleDAO = new ModuleDAOImpl();
        $this->postAssetDAO = new PostAssetDAOImpl();
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

        require_once __DIR__ . '/../views/posts/post.php';
    }

    //CRUD first
    public function create()
    {
        //http://localhost/index.php?action=create

        // Nếu not POST request, show thị form
        $posts = $this->postDAO->getAllPosts();
        $modules = $this->moduleDAO->getAllModules();
        require_once __DIR__ . '/../views/posts/createpost.php';
    }

    public function viewPost($postId)
    {
        try {
            $postDAO = new PostDAOImpl();
            $postAssetService = new PostAssetDAOImpl();

            // Lấy thông tin post
            $post = $postDAO->getPost($postId);
            if (!$post) {
                $_SESSION['error'] = "Post not found";
                header("Location: /posts");
                exit();
            }

            // Lấy danh sách asset
            $assets = $postAssetService->getByPostId($postId);

            // Truyền dữ liệu sang view
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
                $title = htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8');
                $content = htmlspecialchars($_POST['content'] ?? '', ENT_QUOTES, 'UTF-8');

                if (empty($content)) {
                    throw new Exception("Title and content are required");
                }

                error_log("Title and content validated successfully");

                // Create post and take id
                $userId = 1; // Hard-coded tạm thời
                $moduleId = 1; // Hard-coded tạm thời
                $postId = $this->postDAO->createPost($title, $content, $userId, $moduleId);
                error_log("Post created successfully");

                $imagePath = null;
                // process upload file
                if (!empty($_FILES['image']['name'])) {
                    $uploadDir = __DIR__ . '/../../uploads/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $maxFileSize = 100 * 1024 * 1024; // 100mb

                    // Validate file
                    if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                        throw new Exception("Only JPG, PNG, GIF files are allowed");
                    }

                    if ($_FILES['image']['size'] > $maxFileSize) {
                        throw new Exception("File size must not exceed 2MB");
                    }

                    // Create media_key unique
                    $media_key = bin2hex(random_bytes(16));
                    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $fileName = $media_key . '.' . $extension;
                    $filePath = $uploadDir . $fileName;

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        throw new Exception("Failed to upload image");
                    }

                    $imagePath = 'uploads/' . $fileName;
                    $this->postAssetDAO->create($imagePath, $postId);
                    error_log("Image uploaded successfully: " . $imagePath);
                }
                $_SESSION['success'] = "The post has been created successfully!";
                header("Location: /index.php?action=index");
                exit();
            } catch (Exception $e) {
                error_log("Error in store method: " . $e->getMessage());
                $_SESSION['error'] = $e->getMessage();
                header("Location: /index.php?action=index");
                exit();
            }
        }
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
