<?php

use utils\Router;
use controllers\PostController;
use controllers\AuthController;
use controllers\UserController;

// Controllers
$postController = new PostController();
$authController = new AuthController();
$userController = new UserController();

// Post Routes
Router::get('/posts', [$postController, 'index']);  // List all posts
Router::get('/posts/create', [$postController, 'create']);  // Show create form
Router::post('/posts', [$postController, 'store']);  // Store new post
Router::get('/posts/{id}/edit', [$postController, 'edit']);  // Show edit form
Router::post('/posts/{id}/update', [$postController, 'update']);  // Update post
Router::post('/posts/{id}/delete', [$postController, 'delete']);  // Delete post

// Auth Routes
Router::get('/auth/login', [$authController, 'login']);  // Show login form
Router::post('/auth/login', [$authController, 'login']);  // Process login
Router::post('/auth/logout', [$authController, 'logout']);  // Logout
Router::get('/auth/signup', [$authController, 'signup']);  // Show signup form
Router::post('/auth/signup', [$authController, 'signup']);  // Process signup

// User Routes
Router::get('/users', [$userController, 'index']);  // List users
Router::get('/users/{id}/edit', [$userController, 'edit']);  // Show edit form
Router::post('/users/{id}/update', [$userController, 'update']);  // Update user
Router::post('/users/{id}/delete', [$userController, 'delete']);  // Delete user
