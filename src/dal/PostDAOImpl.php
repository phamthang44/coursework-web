<?php

namespace dal;

use database\Database;
use Exception;
use PDO;
use models\Post;
use PDOException;

require_once __DIR__ . '/../models/Post.php';


class PostDAOImpl implements PostDAOI
{
    private $pdo;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    //Focus CRUD DAO

    public function getAllPosts(): array
    {
        $sql = "SELECT * FROM Posts";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        // convert each row data to object Post
        return $this->convertResultRowToPostObj($result, $posts); // return array object Post
    }


    public function getPost($postId): Post
    {
        // TODO: Implement getPost() method.
        $sql = "SELECT * FROM Posts WHERE post_id = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":postId", $postId);
        $stmt->execute();
        $row = $stmt->fetch();
        return new Post($row['post_id'], $row['title'], $row['content'], $row['vote_score'], $row['user_id'], $row['module_id'], $row['timestamp'], $row['update_timestamp']);
    }

    public function getPostByTitle($postTitle)
    {
        $searchTerm = "%{$postTitle}%";
        $sql = "SELECT * FROM Posts WHERE post_title LIKE :postTitle LIMIT 5";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":postTitle", $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If only 1 result, return object Post
        if (count($rows) === 1) {
            $row = $rows[0];
            return new Post($row['post_id'], $row['title'], $row['content'], $row['vote_score'], $row['user_id'], $row['module_id'], $row['timestamp'], $row['update_timestamp']);
        }

        // If more than 1 row then create an array posts
        $posts = [];
        return $this->convertResultRowToPostObj($rows, $posts);
    }

    public function createPost($title, $content, $userId, $moduleId)
    {
        // TODO: Implement createPost() method.
        $sql = "INSERT INTO Posts (title, content, user_id, module_id) VALUES (:title, :content, :userId, :moduleId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":moduleId", $moduleId, PDO::PARAM_INT);

        try {
            $result = $stmt->execute();
            if ($result) {
                return $this->pdo->lastInsertId(); // return id just insert to
            } else {
                throw new Exception("Failed to insert comment: " . print_r($stmt->errorInfo(), true));
            }
        } catch (PDOException $e) {
            error_log("PDO Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function updatePost($postId, $title, $content, $moduleId)
    {
        // TODO: Implement updatePost() method.
        $sql = "UPDATE Posts SET title = :title, content = :content, module_id = :moduleId WHERE post_id = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":moduleId", $moduleId, PDO::PARAM_INT);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updatePostTitle($postId, $title): bool
    {
        // TODO: Implement updatePostTitle() method.
        $sql = "UPDATE Posts SET title = :title WHERE post_id = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updatePostContent($postId, $content): bool
    {
        // TODO: Implement updatePostContent() method.
        $sql = "UPDATE Posts SET content = :content WHERE post_id = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updatePostAsset($postId, $assetId): bool
    {
        // TODO: Implement updatePostAsset() method.
        $sql = "UPDATE Posts SET post_assets_id = :asset_id WHERE post_id = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":assetId", $assetId, PDO::PARAM_INT);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updatePostModule($postId, $moduleId): bool
    {
        // TODO: Implement updatePostModule() method.
        $sql = "UPDATE Posts SET module_id = :module_id WHERE post_id = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":moduleId", $moduleId, PDO::PARAM_INT);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateScore($postId, $voteScore): bool
    {
        // TODO: Implement updateScore() method.
        $sql = "UPDATE Posts SET vote_score = :voteScore WHERE post_id = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":voteScore", $voteScore, PDO::PARAM_INT);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deletePost($postId): bool
    {
        // TODO: Implement deletePost() method.
        $sql = "DELETE FROM Posts WHERE post_id = :postId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function increaseVoteScore($postId)
    {
        // TODO: Implement increaseVoteScore() method.
        // $sql = "SELECT vote_score FROM Posts WHERE post_id = :postId";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        // $stmt->execute();
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // $currentVoteScore = $row['vote_score'];
        // $newVoteScore = $currentVoteScore + 1;

        // $sql = "UPDATE Posts SET vote_score = :voteScore WHERE post_id = :postId";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->bindParam(":voteScore", $newVoteScore, PDO::PARAM_INT);
        // $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        // $stmt->execute();
    }

    public function decreaseVoteScore($postId)
    {
        // TODO: Implement decreaseVoteScore() method.
        // $sql = "SELECT vote_score FROM Posts WHERE post_id = :postId";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        // $stmt->execute();
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // $currentVoteScore = $row['vote_score'];
        // $newVoteScore = $currentVoteScore - 1;

        // $sql = "UPDATE Posts SET vote_score = :voteScore WHERE post_id = :postId";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->bindParam(":voteScore", $newVoteScore, PDO::PARAM_INT);
        // $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        // $stmt->execute();
    }

    /**
     * @param array $result
     * @param array $posts
     * @return array
     */
    public function convertResultRowToPostObj(array $result, array $posts): array
    {
        foreach ($result as $row) {
            $post = new Post($row['post_id'], $row['title'], $row['content'], $row['vote_score'], $row['user_id'], $row['module_id'], $row['timestamp'], $row['update_timestamp']);
            $posts[] = $post;
        }

        return $posts;
    }

    public function getLastPostId()
    {
        $sql = "SELECT MAX(post_id) AS last_id FROM Posts";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['last_id'];
    }

    public function getPostByIdAndUserId($postId, $userId)
    {
        $sql = "SELECT * FROM Posts WHERE post_id = :postId AND user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Post($row['post_id'], $row['title'], $row['content'], $row['vote_score'], $row['user_id'], $row['module_id'], $row['timestamp'], $row['update_timestamp']);
    }

    public function getPostByPage($limit, $offset)
    {
        $sql = "SELECT * FROM Posts ORDER BY post_id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $posts = $this->convertResultRowToPostObj($rows, []);
        //sort($posts);
        $countStmt = $this->pdo->query("SELECT COUNT(*) FROM posts");
        $totalPosts = $countStmt->fetchColumn();

        return [
            'posts' => $posts,
            'totalPosts' => $totalPosts
        ];
    }

    public function getPostByPageByUserId($limit, $offset, $userId)
    {
        $sql = "SELECT * FROM Posts  WHERE user_id = :userId ORDER BY post_id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $posts = $this->convertResultRowToPostObj($rows, []);
        return $posts;
    }

    public function getPostsByUserId($userId)
    {
        $sql = "SELECT * FROM Posts WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        return $this->convertResultRowToPostObj($rows, $posts);
    }

    public function searchPosts($search)
    {
        // if (empty(trim($search))) {
        //     return [];
        // }

        // $searchTerm = "%" . trim($search) . "%";
        // $sql = "SELECT * FROM Posts WHERE LOWER(title) LIKE LOWER(:search) OR LOWER(content) LIKE LOWER(:search) LIMIT 5";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->bindParam(":search", $searchTerm, PDO::PARAM_STR);
        // $stmt->execute();
        if (empty(trim($search))) {
            return []; // Nếu input rỗng thì trả về mảng trống
        }

        $keywords = explode(" ", trim($search)); // Tách từ khóa thành mảng
        $sqlConditions = [];
        $params = [];

        foreach ($keywords as $index => $keyword) {
            $paramKey = ":search{$index}";
            $sqlConditions[] = "(LOWER(title) LIKE LOWER($paramKey) OR LOWER(content) LIKE LOWER($paramKey))";
            $params[$paramKey] = "%$keyword%";
        }

        $sql = "SELECT * FROM Posts WHERE " . implode(" OR ", $sqlConditions) . " LIMIT 5";
        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $posts = [];
        return $this->convertResultRowToPostObj($rows, $posts);
    }

    public function getAmountOfPostByUser($userId)
    {
        $sql = "SELECT COUNT(*) FROM Posts WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
