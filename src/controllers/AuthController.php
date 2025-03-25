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
        if (isset($_COOKIE['rememberme'])) {
            $token = $_COOKIE['rememberme'];
            $user = $this->auth->checkToken($token);

            if ($user) {
                SessionManager::set('user_id', $user->getUserId());
                SessionManager::set('user', $user);
                SessionManager::set('role', $user->getRole());
                header("Location: /quorae");
                exit();
            }
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $rememberMe = $_POST['rememberme'] ?? null;
            $loginSuccess = $this->auth->login($email, $password);

            if ($loginSuccess) {
                if (isset($rememberMe) && $rememberMe === 'yes') {
                    $this->auth->rememberMe();
                }
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
            require_once __DIR__ . "/../views/users/login.php";
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
