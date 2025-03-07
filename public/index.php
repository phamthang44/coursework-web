<?php
// public/index.php
echo "Hello from public!";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . '/../src/dal/PostDAOI.php';
require_once __DIR__ . '/../src/dal/PostDAOImpl.php'; // Bao gồm file PostDAOImpl
require_once __DIR__ . '/../src/controllers/PostController.php';

 // Include file controller
use controllers\PostController;

$controller = new PostController();
$controller->index(); // Gọi hàm index