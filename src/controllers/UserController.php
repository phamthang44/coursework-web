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
}
