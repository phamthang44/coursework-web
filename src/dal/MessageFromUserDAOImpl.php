<?php

namespace dal;

use dal\MessageFromUserDAOI;
use database\Database;
use PDO;
use models\MessageFromUser;

class MessageFromUserDAOImpl implements MessageFromUserDAOI
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function insertMessage($title, $message, $userId)
    {
        $sql = "INSERT INTO messagefromuser (title, content, user_id) VALUES (:title, :message, :userId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }

    public function updateMessage($title, $message, $messageId)
    {
        $sql = "UPDATE messagefromuser SET title = :title, message = :message WHERE message_from_user_id = :messageId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':messageId', $messageId);
        return $stmt->execute();
    }

    public function deleteMessage($messageId)
    {
        $sql = "DELETE FROM messagefromuser WHERE message_from_user_id = :messageId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':messageId', $messageId);
        return $stmt->execute();
    }

    public function getMessage($messageId)
    {
        $sql = "SELECT * FROM messagefromuser WHERE message_from_user_id = :messageId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':messageId', $messageId);
        $stmt->execute();
        $row = $stmt->fetch();
        return new MessageFromUser($row['message_from_user_id'], $row['title'], $row['content'], $row['user_id'], $row['timestamp']);
    }

    public function getAllMessages()
    {
        $sql = "SELECT * FROM messagefromuser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $messages = [];
        return $this->convertResultRowToMessageObj($result, $messages);
    }

    public function getMessagesFromAdmin()
    {
        $sql = "SELECT * FROM messagefromuser WHERE user_id = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $messages = [];
        return $this->convertResultRowToMessageObj($result, $messages);
    }

    public function getTotalMessageNums()
    {
        // TODO: Implement getTotalMessageNums() method.
        $sql = "SELECT COUNT(*) FROM messagefromuser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    private function convertResultRowToMessageObj(array $result, array $messages): array
    {
        foreach ($result as $row) {
            $messages[] = new MessageFromUser(
                $row['message_from_user_id'],
                $row['title'],
                $row['content'],
                $row['user_id'],
                $row['timestamp']
            );
        }
        return $messages;
    }
}
