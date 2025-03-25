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
        setcookie('rememberme', '', time() - 3600, '/');
        $this->deleteRememberMeToken();
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

    public function rememberMe()
    {
        $user = $this->getUser();
        $token = bin2hex(random_bytes(16));
        $this->userDAO->setRememberMeToken($user->getUserId(), $token);
        setcookie('rememberme', $token, time() + 60 * 60 * 24 * 7, '/');
    }

    public function getRememberMeCookie()
    {
        return $_COOKIE['rememberme'] ?? null;
    }

    public function checkToken($token)
    {
        $user = $this->userDAO->checkRememberMeToken($token);
        if ($user) {
            SessionManager::set('user_id', $user->getUserId());
            SessionManager::set('user', $user);
            SessionManager::set('role', $user->getRole());
            return $user;
        }
        return null;
    }

    public function deleteRememberMeToken()
    {
        $user = $this->getUser();
        $this->userDAO->deleteRememberMeToken($user->getUserId());
    }
}
