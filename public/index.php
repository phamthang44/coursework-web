<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
// public/index.php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . '/../src/dal/PostDAOI.php';
require_once __DIR__ . '/../src/dal/PostDAOImpl.php'; // Bao gồm file PostDAOImpl
require_once __DIR__ . '/../src/dal/ModuleDAOI.php';
require_once __DIR__ . '/../src/dal/ModuleDAOImpl.php'; // Bao gồm file ModuleDAOImpl
require_once __DIR__ . '/../src/models/Post.php';
require_once __DIR__ . '/../src/models/Module.php';
require_once __DIR__ . '/../src/controllers/PostController.php';
require_once __DIR__ . '/../src/dal/PostAssetDAOI.php';
require_once __DIR__ . '/../src/dal/PostAssetDAOImpl.php'; // Bao gồm file PostAssetDAOImpl
require_once __DIR__ . '/../src/models/PostAsset.php';
// Include file controller
use controllers\PostController;

$controller = new PostController();

//$controller->index(); // Gọi hàm index


$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'delete':
        $controller->delete($_GET['postId']);
        break;
    case 'update':
        $controller->update($_GET['postId']);
        break;
    case 'edit':
        $controller->edit($_GET['postId']);
        break;
    default:
        $controller->index();
}
