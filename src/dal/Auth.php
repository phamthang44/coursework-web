<?php

namespace dal;

use dal\UserDAOImpl;

class Auth
{
    private $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAOImpl();
    }

    public function login($email, $password)
    {

        $user = $this->userDAO->checkUser($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user->getUserId();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['role'] = $user->getRole();
            // //need ....
            return $user;
        }
        return null;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }

    public function checkAuthentication()
    {
        return isset($_SESSION['user_id']);
    }

    public function getUser()
    {
        return isset($_SESSION['user_id']) ? $_SESSION['username'] : null;
    }
}
