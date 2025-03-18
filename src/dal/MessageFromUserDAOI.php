<?php

namespace dal;

interface MessageFromUserDAOI
{
    public function insertMessage($title, $message, $userId);
    public function updateMessage($title, $message, $messageId);
    public function deleteMessage($messageId);
    public function getMessage($messageId);
    public function getAllMessages();
    public function getTotalMessageNums();
}
