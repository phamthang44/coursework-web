<?php

namespace DAO;

use database\Database;
use DateTime;
use Exception;
use InvalidArgumentException;
use PDO;
use PDOException;
use DAO\PostCommentI;

class PostCommentDAOImpl implements PostCommentI
{
    private $pdo;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getComments()
    {
        // TODO: Implement getComments() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("SELECT * FROM PostComments");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getComment($postCommentId)
    {
        // TODO: Implement getComment() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("SELECT * FROM PostComments WHERE post_comment_id = :postCommentId");
        $stmt->bindParam(":postCommentId", $postCommentId);
        $stmt->execute();
        return $stmt->fetch(); //if not return false else true
    }

    public function addCommentWithoutTitle($postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $postCommentTimeStamp, $postCommentUpdatedTimeStamp, $postId)
    {
        $conn = $this->pdo;
        $stmt = $conn->prepare("INSERT INTO PostComments(content, vote_score, user_id, parent_comment_id, current_timestamp, update_timestamp, post_id) VALUES (:postCommentContent, :postCommentVoteScore, :postCommentUserId, :parentCommentId, :postCommentTimeStamp, :postCommentUpdatedTimeStamp, :postId)");

        $stmt->bindParam(":postCommentContent", $postCommentContent);
        $stmt->bindParam(":postCommentVoteScore", $postCommentVoteScore, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentUserId", $postCommentUserId, PDO::PARAM_INT);
        $stmt->bindParam(":parentCommentId", $parentCommentId, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentTimeStamp", $postCommentTimeStamp);
        $stmt->bindParam(":postCommentUpdatedTimeStamp", $postCommentUpdatedTimeStamp);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);

        try {
            $result = $stmt->execute();
            if ($result) {
                return $conn->lastInsertId(); // return id just insert to
            } else {
                throw new Exception("Failed to insert comment: " . print_r($stmt->errorInfo(), true));
            }
        } catch (PDOException $e) {
            error_log("PDO Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function addComment($postCommentTitle, $postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $postCommentTimeStamp, $postCommentUpdatedTimeStamp, $postId)
    {
        // TODO: Implement addComment() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("INSERT INTO PostComments(title, content, vote_score, user_id, parent_comment_id, current_timestamp, update_timestamp, post_id) VALUES (:postCommentTitle, :postCommentContent, :postCommentVoteScore, :postCommentUserId, :parentCommentId, :postCommentTimeStamp, :postCommentUpdatedTimeStamp, :postId)");
        $stmt->bindParam(":postCommentTitle", $postCommentTitle);
        $stmt->bindParam(":postCommentContent", $postCommentContent);
        $stmt->bindParam(":postCommentVoteScore", $postCommentVoteScore, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentUserId", $postCommentUserId, PDO::PARAM_INT);
        $stmt->bindParam(":parentCommentId", $parentCommentId, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentTimeStamp", $postCommentTimeStamp);
        $stmt->bindParam(":postCommentUpdatedTimeStamp", $postCommentUpdatedTimeStamp);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);

        try {
            $result = $stmt->execute();
            if ($result) {
                return $conn->lastInsertId(); // return id just insert to
            } else {
                throw new Exception("Failed to insert comment: " . print_r($stmt->errorInfo(), true));
            }
        } catch (PDOException $e) {
            error_log("PDO Error: " . $e->getMessage());
            throw $e;
        }

    }

    public function updateCommentTitle($postCommentId, $postCommentTitle): bool
    {
        // TODO: Implement updateCommentTitle() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("UPDATE PostComments SET title = :postCommentTitle WHERE post_comment_id = :postCommentId");
        $stmt->bindParam(":postCommentId", $postCommentId, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentTitle", $postCommentTitle, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateCommentContent($postCommentId, $postCommentContent)
    {
        // TODO: Implement updateCommentContent() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("UPDATE PostComments SET content = :postCommentContent WHERE post_comment_id = :postCommentId");
        $stmt->bindParam(":postCommentId", $postCommentId, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentContent", $postCommentContent, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function increaseVoteScore($postCommentId, $postCommentVoteScore)
    {
        // TODO: Implement increaseVoteScore() method.
        if(is_nan($postCommentVoteScore)) {
            return false;
        }
        $postCommentVoteScore += 1;
        $conn = $this->pdo;
        $stmt = $conn->prepare("UPDATE PostComments SET vote_score = :postCommentVoteScore WHERE post_comment_id = :postCommentId");
        $stmt->bindParam(":postCommentId", $postCommentId, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentVoteScore", $postCommentVoteScore, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function decreaseVoteScore($postCommentId, $postCommentVoteScore)
    {
        // TODO: Implement decreaseVoteScore() method.
    }

    public function deleteComment($postCommentId)
    {
        // TODO: Implement deleteComment() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("DELETE FROM PostComments WHERE post_comment_id = :postCommentId");
        $stmt->bindParam(":postCommentId", $postCommentId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getCommentByUserId($userId)
    {
        // TODO: Implement getCommentByUserId() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("SELECT * FROM PostComments WHERE user_id = :userId");
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result;
        }
        return null;
    }



    public function getCommentsByPostId($postId)
    {
        // TODO: Implement getCommentsByPostId() method.
    }

    public function updateComment($comment)
    {
        // TODO: Implement updateComment() method.
    }
    public function updateScore($postCommentId, $commentScore)
    {
        // TODO: Implement updateScore() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("UPDATE PostComments SET score = :commentScore WHERE post_comment_id = :postCommentId");
        $stmt->bindParam(":commentScore", $commentScore, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentId", $postId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
