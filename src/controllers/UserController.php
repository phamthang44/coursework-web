<?php

namespace controllers;

ini_set('display_errors', 1);
error_reporting(E_ALL);


use dal\UserDAOImpl;

use Exception;
use finfo;

class UserController
{
    private $userDAO;
    public function __construct()
    {
        $this->userDAO = new UserDAOImpl();
    }

    public function signup()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $firstName = trim(htmlspecialchars($_POST['firstName'], ENT_QUOTES, 'UTF-8'));
                $lastName = trim(htmlspecialchars($_POST['lastName'], ENT_QUOTES, 'UTF-8'));
                $password = trim(htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'));
                $username = strtolower(str_replace(' ', '', trim($lastName))) . '.' . strtolower(str_replace(' ', '', trim($firstName)));

                $existingUserName = $this->userDAO->getUserByUsername($username);
                if ($existingUserName) {
                    $lastId = $this->userDAO->getLastUserID();
                    $username = $username . "." . ($lastId + 1);
                }
                $email = trim($_POST['email']);

                if ($this->checkExistedEmail($email)) {
                    throw new Exception("Email already exists");
                }

                $this->userDAO->createUser($username, $lastName, $firstName, $email, $password, NULL, NULL, NULL);
                header("Location: /index.php?action=login");
            } else {
                $file = dirname(__DIR__) . "/views/users/signup.php";
                if (file_exists($file)) {
                    include($file);
                } else {
                    die("File signup.php not found!");
                }
            }
        } catch (Exception $e) {
            header("Location: /index.php?action=signup.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }

    public function checkExistedEmail($email): bool
    {
        $emailExists = $this->userDAO->checkExistedEmail($email);
        if ($emailExists > 1) {
            return true;
        }
        return false;
    }

    public function getUser($userID)
    {
        return $this->userDAO->getUserById($userID);
    }
}
