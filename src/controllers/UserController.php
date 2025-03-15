<?php

namespace controllers;

ini_set('display_errors', 1);
error_reporting(E_ALL);


use dal\UserDAOImpl;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use Dotenv\Dotenv;



class UserController
{
    private $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAOImpl();
    }

    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //$email = '';
            // $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            // $dotenv->load();
            ini_set('SMTP', 'smtp.gmail.com');
            ini_set('smtp_port', 587);
            ini_set('sendmail_from', 'phamthang5331@gmail.com');
            $subject = $_POST['title'];
            $message = $_POST['content'];
            $to = 'phamthang5331@gmail.com';
            $headers = "From: no-reply@localhost\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            if (mail($to, $subject, $message, $headers)) {
                header("Location: /success");
            } else {
                echo "❌ Error in sending email.";
            }
        } else {
            require_once __DIR__ . '/../views/users/contact.php';
        }
    }

    public function emailsuccess()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../views/success/sendmailsucess.php';
        }
    }

    public function emailfail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once __DIR__ . '/../views/errors/sendmailfail.php';
        }
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
                header("Location: /login");
            } else {
                $file = dirname(__DIR__) . "/views/users/signup.php";
                if (file_exists($file)) {
                    include($file);
                } else {
                    die("File signup.php not found!");
                }
            }
        } catch (Exception $e) {
            header("Location: /signup?error=" . urlencode($e->getMessage()));
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
