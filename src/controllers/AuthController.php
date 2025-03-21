<?php
// Controller use Auth class
namespace controllers;

use dal\Auth;
use utils\SessionManager;

class AuthController extends BaseController
{
    private $auth;

    public function __construct()
    {
        parent::__construct(['/login', '/logout', '/quorae', '/signup', '/posts', '/403', '/404']);
        $this->auth = new Auth();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->auth->login($email, $password);

            if ($user) {
                if (SessionManager::get('role') === 'admin') {
                    header("Location: /admin/dashboard");
                    exit();
                }
                header("Location: /quorae"); //temporary need to check role
                exit();
            } else {
                SessionManager::set('invalid-credentials', "Wrong password or email!");
                header("Location: /login");
                exit();
            }
        } else {
            $file = dirname(__DIR__) . "/views/users/login.php";
            if (file_exists($file)) {
                include($file);
            } else {
                die("File login.php not found!");
            }
        }
    }

    public function logout()
    {
        $this->auth->logout();
        header("Location: /login");
    }

    public function checkRoleAdmin()
    {
        if ($this->auth->checkAuthentication()) {
            $user = $this->auth->getUser();
            if ($user->getRole() === 'admin') {
                return true;
            }
        }
        return false;
    }

    public function checkRoleUser()
    {
        if ($this->auth->checkAuthentication()) {
            $user = $this->auth->getUser();
            if ($user->getRole() === 'user') {
                return true;
            }
        }
        return false;
    }
    public function forbidden()
    {
        require_once __DIR__ . '/../views/errors/403.php';
    }
}
