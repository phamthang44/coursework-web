<?php

namespace dal;

use dal\UserDAOImpl;
use utils\SessionManager;

class Auth
{
    private $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAOImpl();
    }

    public function login($email, $password)
    {

        $user = $this->userDAO->checkUser($email);
        if ($user && password_verify($password, $user->getPassword())) {
            SessionManager::set('user_id', $user->getUserId());
            SessionManager::set('user', $user);
            SessionManager::set('role', $user->getRole());
            // //need ....
            return true;
        }
        return false;
    }

    public function logout()
    {
        SessionManager::destroy();
    }

    public function checkAuthentication()
    {
        return SessionManager::get('user_id') !== null;
    }

    public function getUser()
    {
        return SessionManager::get('user');
    }

    public function getRole()
    {
        return SessionManager::get('role');
    }
}
