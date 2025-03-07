<?php

namespace controllers;

use DAO\PostDAOImpl;

class PostController
{
    private $postDAO;
    function __construct()
    {
        $this->postDAO = new PostDAOImpl();
    }

    public function index()
    {
        $posts = $this->postDAO->getAllPosts();
        require_once __DIR__ . '/../views/posts/post.php';
    }
}
