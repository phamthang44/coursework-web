<?php

namespace dal;

use database\Database;
use Exception;
use PDO;
use PDOException;
use models\PostComment;

class PostCommentDAOImpl implements PostCommentI
{
    private $pdo;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getNumberComments($postId)
    {
        $sql = "SELECT COUNT(*) FROM PostComments WHERE post_id = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getComments()
    {
        // TODO: Implement getComments() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("SELECT * FROM PostComments");
        $stmt->execute();
        return $this->convertPostCommentToObj($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getCommentsPer10($postId, $offset)
    {
        // TODO: Implement getComments() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("SELECT * FROM PostComments WHERE post_id = :postId ORDER BY parent_comment_id ASC, post_comment_id ASC LIMIT 10 OFFSET :offset");
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $this->convertPostCommentToObj($stmt->fetchAll(PDO::FETCH_ASSOC));
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

    public function addCommentWithoutTitle($postCommentContent, $postCommentVoteScore, $postCommentUserId, $commentCreateDate, $postId)
    {
        $conn = $this->pdo;
        $stmt = $conn->prepare("INSERT INTO PostComments(content, vote_score, user_id, create_date, post_id) VALUES (:postCommentContent, :postCommentVoteScore, :postCommentUserId, :commentCreateDate, :postId)");

        $stmt->bindParam(":postCommentContent", $postCommentContent);
        $stmt->bindParam(":postCommentVoteScore", $postCommentVoteScore, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentUserId", $postCommentUserId, PDO::PARAM_INT);
        $stmt->bindParam(":commentCreateDate", $commentCreateDate);
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

    public function addComment($postCommentTitle, $postCommentContent, $postCommentVoteScore, $postCommentUserId, $commentCreateDate, $commentUpdateDate, $postId)
    {
        // TODO: Implement addComment() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("INSERT INTO PostComments(title, content, vote_score, user_id, parent_comment_id, create_date, update_date, post_id) VALUES (:postCommentTitle, :postCommentContent, :postCommentVoteScore, :postCommentUserId, :parentCommentId, :commentCreateDate, :commentUpdateDate, :postId)");
        $stmt->bindParam(":postCommentTitle", $postCommentTitle);
        $stmt->bindParam(":postCommentContent", $postCommentContent);
        $stmt->bindParam(":postCommentVoteScore", $postCommentVoteScore, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentUserId", $postCommentUserId, PDO::PARAM_INT);
        $stmt->bindParam(":commentCreateDate", $commentCreateDate);
        $stmt->bindParam(":commentUpdateDate", $commentUpdateDate);
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

    public function insertReplyAComment($postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $commentCreateDate, $postId)
    {
        $sql = "INSERT INTO PostComments(content, vote_score, user_id, parent_comment_id, create_date, post_id) VALUES (:postCommentContent, :postCommentVoteScore, :postCommentUserId, :parentCommentId, :commentCreateDate, :postId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":postCommentContent", $postCommentContent);
        $stmt->bindParam(":postCommentVoteScore", $postCommentVoteScore, PDO::PARAM_INT);
        $stmt->bindParam(":postCommentUserId", $postCommentUserId, PDO::PARAM_INT);
        $stmt->bindParam(":parentCommentId", $parentCommentId, PDO::PARAM_INT);
        $stmt->bindParam(":commentCreateDate", $commentCreateDate);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        try {
            $result = $stmt->execute();
            if ($result) {
                return $this->pdo->lastInsertId();
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
        if (is_nan($postCommentVoteScore)) {
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

    public function getCommentsByPostIdOrder($postId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM PostComments WHERE post_id = :postId ORDER BY parent_comment_id ASC, post_comment_id ASC");
        $stmt->execute(['postId' => $postId]);
        $comments = $this->convertPostCommentToObj($stmt->fetchAll(PDO::FETCH_ASSOC));
        return $comments;
    }

    public function getCommentsByPostId($postId)
    {
        // TODO: Implement getCommentsByPostId() method.
        $conn = $this->pdo;
        $stmt = $conn->prepare("SELECT * FROM PostComments WHERE post_id = :postId");
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $this->convertPostCommentToObj($stmt->fetchAll(PDO::FETCH_ASSOC));
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

    private function convertPostCommentToObj($rows)
    {
        $results = [];
        foreach ($rows as $row) {
            $postComment = new PostComment($row['post_comment_id'], $row['title'], $row['content'], $row['vote_score'], $row['user_id'], $row['parent_comment_id'], $row['create_date'], $row['update_date'], $row['post_id'], $row['user_id']);

            $results[] = $postComment;
        }
        return $results;
    }
}
