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
        $sql = "SELECT COALESCE(SUM(vote_type), 0) AS vote_score
            FROM PostVotes
            WHERE post_id = :post_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
        return $stmt->fetchColumn(); // return vote_score value
    }

    public function vote($userId, $postId, $voteType = null): bool
    {
        $sql = "SELECT vote_type FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':post_id', $postId);
        $stmt->execute();
        $existingVote = $stmt->fetchColumn();
        error_log("Existing VoteType: " . json_encode($existingVote));
        error_log("New VoteType: " . json_encode($voteType));

        if (is_null($voteType)) {
            //Unvote: only send postId, remove vote if exists
            if ($existingVote !== false) {
                $sql = "DELETE FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
            } else {
                return true; //no vote return true
            }
        } else {
            if (!in_array($voteType, [-1, 1])) {
                throw new InvalidArgumentException("Invalid vote type: must be -1 or 1");
            }
            if ($existingVote === false) {
                $sql = "INSERT INTO PostVotes (user_id, post_id, vote_type) VALUES (:user_id, :post_id, :vote_type)";
            } elseif ($existingVote === $voteType) {
                $sql = "DELETE FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
            } else {
                //if already vote -> update
                $sql = "UPDATE PostVotes SET vote_type = :vote_type WHERE user_id = :user_id AND post_id = :post_id";
            }
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':post_id', $postId);
        if (!is_null($voteType)) {
            $stmt->bindParam(':vote_type', $voteType);
        }
        return $stmt->execute();
    }

    public function unvote($user_id, $post_id): bool
    {
        $sql = "DELETE FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);
    }

    public function getUserVoteStatus($user_id, $post_id)
    {
        $sql = "SELECT vote_type FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':post_id' => $post_id]);
        return $stmt->fetchColumn(); //Return -1, 1 or false
    }
}
