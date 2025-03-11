<?php
// Controller sử dụng lớp Auth
namespace controllers;

use dal\Auth;

class AuthController
{
    private $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($this->auth->login($username, $password)) {
                $user = $this->auth->login($username, $password);
                //$_SESSION['role'] = $user->getRole();
                if ($user->getRole() === 'admin') {
                    header("Location: /dashboard ");
                }
                header("Location: /index.php"); //temporary need to check role
            } else {
                echo "Invalid credentials";
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
}
