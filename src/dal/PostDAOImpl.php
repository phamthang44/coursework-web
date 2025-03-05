<?php
    namespace DAO;
    use database\Database;
    use PDO;
    use DAO\PostDAOI;

    class PostDAOImpl implements PostDAOI {
        private $pdo;
        public function __construct() {
            $db = new Database();
            $this->pdo = $db->getConnection();
        }

        //Focus CRUD DAO

        public function getAllPosts()
        {
            // TODO: Implement getAllPosts() method.
            $conn = $this->pdo;
            $sql = "SELECT * FROM Posts";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public function getPost($postId)
        {
            // TODO: Implement getPost() method.
            $conn = $this->pdo;
            $sql = "SELECT * FROM Posts WHERE post_id = :postId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":postId", $postId);
            $stmt->execute();
            return $stmt->fetch();
        }

        public function getPostByTitle($postTitle) {
            $searchTerm = "%{$postTitle}%";
            $sql = "SELECT * FROM Posts WHERE post_title LIKE :postTitle LIMIT 5";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":postTitle", $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // If only 1 result, return object Post
            if (count($rows) === 1) {
                $row = $rows[0];
                $post = new Post($row['title'], $row['content']);
                $post->setId($row['post_id']);
                return $post;
            }

            // If more than 1 row then create an array posts
            $posts = [];
            foreach ($rows as $row) {
                $post = new Post($row['title'], $row['content']);
                $post->setId($row['post_id']);
                $posts[] = $post;
            }
            return $posts;
        }

        public function createPost($title, $content, $postAssetId, $userId, $moduleId)
        {
            // TODO: Implement createPost() method.
            $conn = $this->pdo;
            $sql = "INSERT INTO Posts (post_title, post_content, post_asset_id, user_id, module_id) VALUES (:title, :content, :postAssetId, :userId, :moduleId)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->bindParam(":content", $content, PDO::PARAM_STR);
            $stmt->bindParam(":postAssetId", $postAssetId, PDO::PARAM_INT);
            $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
            $stmt->bindParam(":moduleId", $moduleId, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function updatePost($postId, $title, $content, $postAssetId, $moduleId, $updatedTimestamp)
        {
            // TODO: Implement updatePost() method.
            $conn = $this->pdo;
            $sql = "";
        }

        public function updatePostTitle($postId, $title, $updatedTimestamp)
        {
            // TODO: Implement updatePostTitle() method.
            $conn = $this->pdo;
            $sql = "UPDATE Posts SET title = :title, update_timestamp = :updatedTimestamp WHERE post_id = :postId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->bindParam(":updatedTimestamp", $updatedTimestamp, PDO::PARAM_INT);
            $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function updatePostContent($postId, $content, $updatedTimestamp)
        {
            // TODO: Implement updatePostContent() method.
            $conn = $this->pdo;
            $sql = "UPDATE Posts SET content = :content, update_timestamp = :updatedTimestamp WHERE post_id = :postId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":content", $content, PDO::PARAM_STR);
            $stmt->bindParam(":updatedTimestamp", $updatedTimestamp, PDO::PARAM_INT);
            $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function updatePostAsset($postId, $assetId, $updatedTimestamp)
        {
            // TODO: Implement updatePostAsset() method.
            $conn = $this->pdo;
            $sql = "UPDATE Posts SET post_assets_id = :asset_id, update_timestamp = :updatedTimestamp WHERE post_id = :postId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":assetId", $assetId, PDO::PARAM_INT);
            $stmt->bindParam(":updatedTimestamp", $updatedTimestamp, PDO::PARAM_INT);
            $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function updatePostModule($postId, $moduleId, $updatedTimestamp)
        {
            // TODO: Implement updatePostModule() method.
            $conn = $this->pdo;
            $sql = "UPDATE Posts SET module_id = :module_id, update_timestamp = :updatedTimestamp WHERE post_id = :postId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":moduleId", $moduleId, PDO::PARAM_INT);
            $stmt->bindParam(":updatedTimestamp", $updatedTimestamp, PDO::PARAM_INT);
            $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function updateScore($postId, $voteScore)
        {
            // TODO: Implement updateScore() method.
            $conn = $this->pdo;
            $sql = "UPDATE Posts SET vote_score = :voteScore WHERE post_id = :postId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":voteScore", $voteScore, PDO::PARAM_INT);
            $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function deletePost($postId)
        {
            // TODO: Implement deletePost() method.
            $conn = $this->pdo;
            $sql = "DELETE FROM Posts WHERE post_id = :postId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function increaseVoteScore($postId, $voteScore)
        {
            // TODO: Implement increaseVoteScore() method.

        }

        public function decreaseVoteScore($postId, $voteScore)
        {
            // TODO: Implement decreaseVoteScore() method.
        }
    }
