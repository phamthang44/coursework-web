<?php
// $router->addRoute('GET', '/', 'PostController', 'index');

// // Auth routes
// $router->addRoute('GET', '/login', 'AuthController', 'login');
// $router->addRoute('POST', '/login', 'AuthController', 'login');
// $router->addRoute('GET', '/logout', 'AuthController', 'logout');
// $router->addRoute('GET', '/signup', 'UserController', 'signup');

// // Post routes
// $router->addRoute('GET', '/posts', 'PostController', 'index');
// $router->addRoute('GET', '/posts/create', 'PostController', 'create');
// $router->addRoute('POST', '/posts/store', 'PostController', 'store');
// $router->addRoute('GET', '/posts/edit/{id}', 'PostController', 'edit');
// $router->addRoute('POST', '/posts/update/{id}', 'PostController', 'update');
// $router->addRoute('GET', '/posts/delete/{id}', 'PostController', 'delete');
// $router->addRoute('GET', '/admin/dashboard', 'AdminController', 'dashboard');
// $router->addRoute('GET', '/contact', 'UserController', 'contact');
// $router->addRoute('POST', '/contact', 'UserController', 'contact');
namespace routes;

use utils\Router;

class WebRoutes
{
    public static function register(Router $router)
    {
        // Post routes
        $router->addRoute('GET', '/quorae', 'PostController', 'index');

        // Auth routes
        $router->addRoute('GET', '/login', 'AuthController', 'login');
        $router->addRoute('POST', '/login', 'AuthController', 'login');
        $router->addRoute('GET', '/logout', 'AuthController', 'logout');
        $router->addRoute('GET', '/signup', 'UserController', 'signup');
        $router->addRoute('POST', '/signup', 'UserController', 'signup');
        $router->addRoute('GET', '/403', 'AuthController', 'forbidden');


        // Post routes
        $router->addRoute('GET', '/posts', 'PostController', 'index');
        $router->addRoute('GET', '/posts/create', 'PostController', 'create');
        $router->addRoute('POST', '/posts/store', 'PostController', 'store');
        $router->addRoute('GET', '/posts/edit/{id}', 'PostController', 'edit');
        $router->addRoute('POST', '/posts/update/{id}', 'PostController', 'update');
        $router->addRoute('GET', '/posts/delete/{id}', 'PostController', 'delete');
        $router->addRoute('GET', '/404', 'PostController', 'notfound');
        // Contact routes
        $router->addRoute('GET', '/contact', 'UserController', 'contact');
        $router->addRoute('POST', '/sendemail', 'UserController', 'sendEmail');
        $router->addRoute('GET', '/emailsuccess', 'UserController', 'emailsuccess');
        $router->addRoute('GET', '/emailfail', 'UserController', 'emailfail');
        $router->addRoute('GET', '/404', 'UserController', 'notfound');
        // User routes
        $router->addRoute('GET', '/profile/{firstname-lastname-id}', 'UserController', 'userProfile');
        $router->addRoute('POST', '/users/update/{id}', 'UserController', 'update');
        $router->addRoute('POST', '/users/update-avatar/{id}', 'UserController', 'updateAvatar');
        $router->addRoute('POST', '/users/update-bio/{id}', 'UserController', 'updateBio');

        // Admin routes
        $router->addRoute('GET', '/admin/dashboard', 'AdminController', 'dashboard');
        $router->addRoute('GET', '/admin/profile/{firstname-lastname-id}', 'AdminController', 'userProfile');
        $router->addRoute('GET', '/admin/user-management', 'AdminController', 'userManagement');
        $router->addRoute('GET', '/admin/module-management', 'AdminController', 'moduleManagement');
        $router->addRoute('POST', '/admin/modules/update/{moduleId}', 'AdminController', 'updateModule');
        $router->addRoute('POST', '/admin/modules/create', 'AdminController', 'createModule');
        $router->addRoute('GET', '/admin/modules/delete/{moduleId}', 'AdminController', 'deleteModule');
    }
}
