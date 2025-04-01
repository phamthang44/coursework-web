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

    public function getVoteData($postId, $userId)
    {
        // Take total vote of post
        $sql = "SELECT COALESCE(SUM(vote_type), 0) AS vote_score FROM PostVotes WHERE post_id = :post_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':post_id' => $postId]);
        $voteScore = $stmt->fetchColumn();

        // Take status of vote of current user
        $sql = "SELECT vote_type FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId, ':post_id' => $postId]);
        $userVote = $stmt->fetchColumn() ?? 0; // If null then return 0 (not vote yet)

        return ['voteScore' => $voteScore, 'userVote' => $userVote];
    }

    public function vote($userId, $postId, $voteType)
    {
        // Take the current status vote of user
        $oldVote = $this->getUserVoteStatus($userId, $postId);
        if ($voteType === 0 || $oldVote === $voteType) {
            // If voteType = 0 or vote like previous one then remove vote
            $sql = "DELETE FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
            $params = [':user_id' => $userId, ':post_id' => $postId];
        } elseif ($oldVote === false && $voteType !== 0) {
            // If have not voted before, add a new vote.
            $sql = "INSERT INTO PostVotes (user_id, post_id, vote_type) VALUES (:user_id, :post_id, :vote_type)";
            $params = [':user_id' => $userId, ':post_id' => $postId, ':vote_type' => $voteType];
        } elseif ($oldVote !== false && $oldVote !== $voteType && $voteType !== 0) {
            // If voted before but with a different vote type, update
            $sql = "UPDATE PostVotes SET vote_type = :vote_type WHERE user_id = :user_id AND post_id = :post_id";
            $params = [':user_id' => $userId, ':post_id' => $postId, ':vote_type' => $voteType];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $newVoteScore = $this->getVoteScore($postId);
        return ['status' => true, 'voteScore' => $newVoteScore];
    }


    public function getUserVoteStatus($user_id, $post_id)
    {
        $sql = "SELECT vote_type FROM PostVotes WHERE user_id = :user_id AND post_id = :post_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->execute();
        return $stmt->fetchColumn(); //Return -1, 1 or false
    }

    public function getVoteScoresByPostIds($postIds)
    {
        if (empty($postIds)) {
            throw new InvalidArgumentException("Post IDs array cannot be empty.");
        }

        $placeholders = implode(',', array_fill(0, count($postIds), '?'));
        $sql = "SELECT post_id, COALESCE(SUM(CASE WHEN vote_type = 1 THEN 1 WHEN vote_type = -1 THEN -1 ELSE 0 END), 0) AS vote_score 
                FROM PostVotes 
                WHERE post_id IN ($placeholders) 
                GROUP BY post_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($postIds);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // Return associative array with post_id as key and vote_score as value
    }
}
