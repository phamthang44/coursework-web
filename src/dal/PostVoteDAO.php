<?php

namespace dal;

use database\Database;
use PDO;
use InvalidArgumentException;

class PostVoteDAO
{
    private $pdo;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getVoteScore($post_id)
    {
        $sql = "SELECT 
            COALESCE(SUM(CASE WHEN vote_type = 1 THEN 1 WHEN vote_type = -1 THEN -1 ELSE 0 END), 0) 
            AS vote_score 
            FROM PostVotes 
            WHERE post_id = :post_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
        return (int) $stmt->fetchColumn(); // return vote_score value
    }
    //solution mới dùng Redis + MySQL cần tính toán lại 
    // public function vote($userId, $postId, $voteType = null): bool
    // {
    //     $sql = "SELECT vote_type FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->bindParam(':user_id', $userId);
    //     $stmt->bindParam(':post_id', $postId);
    //     $stmt->execute();
    //     $existingVote = $stmt->fetchColumn();

    //     if (is_null($voteType)) {
    //         if ($existingVote !== false) {
    //             $sql = "UPDATE PostVotes SET vote_type = NULL WHERE user_id = :user_id AND post_id = :post_id";
    //         } else {
    //             return true;
    //         }
    //     } else {
    //         if (!in_array($voteType, [-1, 1])) {
    //             throw new InvalidArgumentException("Invalid vote type: must be -1 or 1");
    //         }
    //         if ($existingVote === false) {
    //             $sql = "INSERT INTO PostVotes (user_id, post_id, vote_type) VALUES (:user_id, :post_id, :vote_type)";
    //         } elseif ($existingVote === $voteType) {
    //             $sql = "UPDATE PostVotes SET vote_type = NULL WHERE user_id = :user_id AND post_id = :post_id";
    //         } elseif ($existingVote === 1 && $voteType === -1) {
    //             $sql = "UPDATE PostVotes SET vote_type = -1 WHERE user_id = :user_id AND post_id = :post_id";
    //         } elseif ($existingVote === -1 && $voteType === 1) {
    //             $sql = "UPDATE PostVotes SET vote_type = 1 WHERE user_id = :user_id AND post_id = :post_id";
    //         } else {
    //             $sql = "UPDATE PostVotes SET vote_type = :vote_type WHERE user_id = :user_id AND post_id = :post_id";
    //         }
    //     }
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->bindParam(':user_id', $userId);
    //     $stmt->bindParam(':post_id', $postId);
    //     if (!is_null($voteType) && ($existingVote === false || ($existingVote === -1 && $voteType === 1))) {
    //         $stmt->bindParam(':vote_type', $voteType);
    //     }

    //     return $stmt->execute();
    // }

    // public function unvote($user_id, $post_id): bool
    // {
    //     $sql = "DELETE FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
    //     $stmt = $this->pdo->prepare($sql);
    //     return $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);
    // }

    public function getUserVoteStatus($user_id, $post_id)
    {
        $sql = "SELECT vote_type FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);
        return $stmt->fetchColumn(); //Return -1, 1 or false
    }
}
