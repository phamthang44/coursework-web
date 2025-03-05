<?php
    namespace DAO;

    use database\Database;
    use DateTime;
    use Exception;
    use InvalidArgumentException;
    use PDO;
    use PDOException;

    class PostCommentDAOImpl implements PostCommentI {
        private $pdo;
        public function __construct() {
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
            return $stmt->fetch();

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
        }

        public function updateCommentTitle($postCommentId, $postCommentTitle)
        {
            // TODO: Implement updateCommentTitle() method.
        }

        public function updateCommentContent($postCommentContent)
        {
            // TODO: Implement updateCommentContent() method.
        }

        public function increaseVoteScore($postCommentId, $postCommentVoteScore)
        {
            // TODO: Implement increaseVoteScore() method.
        }

        public function decreaseVoteScore($postCommentId, $postCommentVoteScore)
        {
            // TODO: Implement decreaseVoteScore() method.
        }

        public function deleteComment($postCommentId)
        {
            // TODO: Implement deleteComment() method.
        }

        public function getCommentByUserId($userId)
        {
            // TODO: Implement getCommentByUserId() method.
        }

        public function getTimeStamp()
        {
            // TODO: Implement getTimeStamp() method.
        }

        public function updatedTimeStamp()
        {
            // TODO: Implement updatedTimeStamp() method.
        }

        public function getCommentsByPostId($postId)
        {
            // TODO: Implement getCommentsByPostId() method.
        }
    }