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
        $router->addRoute('POST', '/api/vote/post', 'PostController', 'vote');
        $router->addRoute('GET', '/post/view/{id}', 'PostController', 'viewPost');
        $router->addRoute('GET', '/api/post/top-contributors', 'PostController', 'topContributors');
        // Contact routes
        $router->addRoute('GET', '/contact', 'UserController', 'contact');
        $router->addRoute('POST', '/sendemail', 'UserController', 'sendEmail');
        $router->addRoute('GET', '/emailsuccess', 'UserController', 'emailsuccess');
        $router->addRoute('GET', '/emailfail', 'UserController', 'emailfail');

        // User routes
        $router->addRoute('GET', '/profile/{firstname-lastname-id}', 'UserController', 'userProfile');
        $router->addRoute('POST', '/users/update/{id}', 'UserController', 'update');
        $router->addRoute('POST', '/users/update-avatar/{id}', 'UserController', 'updateAvatar');
        $router->addRoute('POST', '/users/update-bio/{id}', 'UserController', 'updateBio');
        $router->addroute('POST', '/api/user/top-contributor', 'UserController', 'getUserTopContributor');
        $router->addRoute('GET', '/api/user/moderators', 'UserController', 'getModerators');
        $router->addRoute('GET', '/forgotpassword', 'AuthController', 'forgotPassword');
        $router->addRoute('GET', '/api/user/search/{query}', 'UserController', 'search');

        // Admin routes
        $router->addRoute('GET', '/admin/dashboard', 'AdminController', 'dashboard');
        $router->addRoute('GET', '/admin/profile/{firstname-lastname-id}', 'AdminController', 'userProfile');
        $router->addRoute('GET', '/admin/user-management', 'AdminController', 'userManagement');
        $router->addRoute('GET', '/admin/module-management', 'AdminController', 'moduleManagement');
        $router->addRoute('POST', '/admin/modules/update/{moduleId}', 'AdminController', 'updateModule');
        $router->addRoute('POST', '/admin/modules/create', 'AdminController', 'createModule');
        $router->addRoute('GET', '/admin/modules/delete/{moduleId}', 'AdminController', 'deleteModule');
        $router->addRoute('GET', '/admin/banuser/{id}', 'AdminController', 'banuser');
        $router->addRoute('GET', '/admin/unbanuser/{id}', 'AdminController', 'unbanuser');
        $router->addRoute('GET', '/admin/update-role/{id}', 'AdminController', 'updateRole');
        // Search route
        $router->addRoute('GET', '/search/{query}', 'PostController', 'search');

        // Comment routes
        $router->addRoute('POST', '/comment', 'PostCommentController', 'comment');
        $router->addRoute('POST', '/reply', 'PostCommentController', 'replyComment');
        $router->addRoute('POST', '/api/vote/comment', 'PostCommentController', 'vote');
        $router->addRoute('GET', '/comment/delete/{id}', 'PostCommentController', 'deleteComment');
        $router->addRoute('POST', '/comment/update/{id}', 'PostCommentController', 'updateComment');

        //message routes
        $router->addRoute('GET', '/messages', 'MessagesController', 'showMessage');
    }
}
