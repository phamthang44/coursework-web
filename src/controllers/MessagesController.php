<?php

namespace controllers;

use dal\MessageFromUserDAOImpl;
use utils\SessionManager;

class MessagesController
{
    private $messageFromUserDAO;
    public function __construct()
    {
        $this->messageFromUserDAO = new MessageFromUserDAOImpl();
    }

    public function showMessage()
    {
        $currentUser = SessionManager::get('user');
        if (!$currentUser) {
            header('Location: /login');
        }

        $messagesFromUsers = $this->getMessages();
        require_once __DIR__ . '/../views/admin/message.php';
    }

    public function getMessages()
    {
        return $this->messageFromUserDAO->getAllMessages();
    }
}
