<?php
$router->addRoute('GET', '/', 'PostController', 'index');

// Auth routes
$router->addRoute('GET', '/login', 'AuthController', 'login');
$router->addRoute('POST', '/login', 'AuthController', 'login');
$router->addRoute('GET', '/logout', 'AuthController', 'logout');
$router->addRoute('GET', '/signup', 'UserController', 'signup');

// Post routes
$router->addRoute('GET', '/posts', 'PostController', 'index');
$router->addRoute('GET', '/posts/create', 'PostController', 'create');
$router->addRoute('POST', '/posts/store', 'PostController', 'store');
$router->addRoute('GET', '/posts/edit/{id}', 'PostController', 'edit');
$router->addRoute('POST', '/posts/update/{id}', 'PostController', 'update');
$router->addRoute('GET', '/posts/delete/{id}', 'PostController', 'delete');
$router->addRoute('GET', '/admin/dashboard', 'AdminController', 'dashboard');
