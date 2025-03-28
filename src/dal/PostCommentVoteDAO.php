<?php

namespace dal;

use database\Database;
use PDO;
use InvalidArgumentException;

class PostCommentVoteDAO
{
    private $pdo;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getVoteScore($comment_id)
    {
        $sql = "SELECT 
            COALESCE(SUM(CASE WHEN vote_type = 1 THEN 1 WHEN vote_type = -1 THEN -1 ELSE 0 END), 0) 
            AS vote_score 
            FROM commentvotes 
            WHERE comment_id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':comment_id', $comment_id);
        $stmt->execute();
        return (int) $stmt->fetchColumn(); // return vote_score value
    }

    public function getVoteData($commentId, $userId)
    {
        // Take total like
        $sql = "SELECT COUNT(*) AS vote_score FROM commentvotes WHERE comment_id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':comment_id' => $commentId]);
        $voteScore = $stmt->fetchColumn();

        // Check if user already liked
        $sql = "SELECT COUNT(*) FROM commentvotes WHERE user_id = :user_id AND comment_id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':comment_id' => $commentId]);
        $userLiked = $stmt->fetchColumn() > 0; // Return true if liked, false if not

        return ['voteScore' => $voteScore, 'userLiked' => $userLiked];
    }


    public function vote($userId, $commentId, $voteType)
    {
        // Check if user has voted yet
        $sql = "SELECT vote_type FROM commentvotes WHERE user_id = :user_id AND comment_id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':comment_id' => $commentId]);
        $existingVote = $stmt->fetchColumn();

        if ($existingVote !== false) {
            if ($voteType == 0) {
                // If user has voted and wants to unvote => Delete from DB
                $sql = "DELETE FROM commentvotes WHERE user_id = :user_id AND comment_id = :comment_id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':user_id' => $userId, ':comment_id' => $commentId]);
            }
        } else {
            if ($voteType == 1) {
                // If user has not voted and wants to like => Add new
                $sql = "INSERT INTO commentvotes (user_id, comment_id, vote_type) VALUES (:user_id, :comment_id, :vote_type)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':user_id' => $userId, ':comment_id' => $commentId, ':vote_type' => $voteType]);
            }
        }

        // Get the latest vote count
        return $this->getVoteData($commentId, $userId);
    }




    public function getUserVoteStatus($user_id, $comment_id)
    {
        $sql = "SELECT vote_type FROM commentvotes WHERE user_id = :user_id AND comment_id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':comment_id', $comment_id);
        $stmt->execute();
        return $stmt->fetchColumn(); //Return -1, 1 or false
    }
}
