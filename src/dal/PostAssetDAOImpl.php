<?php

namespace dal;

use database\Database;
use Exception;
use models\PostAsset;
use PDO;
use PDOException;

class PostAssetDAOImpl implements PostAssetDAOI
{
    private $pdo;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    /**
     * @throws Exception
     */
    public function getById($post_asset_id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM postassets WHERE post_asset_id = :id");
            $stmt->bindParam(':id', $post_asset_id);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return new PostAsset(
                    $result['post_asset_id'],
                    $result['media_key'],
                    $result['post_id']
                );
            }
            return null;
        } catch (PDOException $e) {
            //
            throw new Exception("Error getting post asset: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getAssetsByPostId($post_id): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM postassets WHERE post_id = :post_id");
            $stmt->bindParam(':post_id', $post_id);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $postAssets = [];

            foreach ($results as $result) {
                $postAssets[] = new PostAsset(
                    $result['post_asset_id'],
                    $result['media_key'],
                    $result['post_id']
                );
            }
            return $postAssets;
        } catch (PDOException $e) {
            throw new Exception("Error getting post assets by post id: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getByPostId($post_id)
    {
        try {
            $conn = $this->pdo;
            $sql = "SELECT * FROM postassets WHERE post_id = :post_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new PostAsset(
                    $row['post_asset_id'],
                    $row['media_key'],
                    $row['post_id']
                );
            }
        } catch (PDOException $e) {
            throw new Exception("Error getting post assets by post id: " . $e->getMessage());
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function create($media_key, $post_id)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO postassets (media_key, post_id) VALUES (:media_key, :post_id)");
            $stmt->bindParam(':media_key', $media_key);
            $stmt->bindParam(':post_id', $post_id);

            $result = $stmt->execute();
            if ($result) {
                $post_asset_id = $this->pdo->lastInsertId();
                return new PostAsset($post_asset_id, $media_key, $post_id);
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error creating post asset: " . $e->getMessage());
        }
    }

    public function delete($post_asset_id): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM postassets WHERE post_asset_id = :id");
            $stmt->bindParam(':id', $post_asset_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error deleting post asset: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function update($post_id, $media_key): bool
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE postassets SET media_key = :media_key WHERE post_id = :id");
            $stmt->bindParam(':media_key', $media_key);
            $stmt->bindParam(':id', $post_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating post asset: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getLastPostAssetId()
    {
        // TODO: Implement getNextPostAssetId() method.
        try {
            $stmt = $this->pdo->prepare("SELECT post_asset_id FROM postassets ORDER BY post_asset_id DESC LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result['post_asset_id'];
            }
        } catch (PDOException $e) {
            throw new Exception("Error getting post asset id: " . $e->getMessage());
        }
        return null;
    }
    public function getByPostIds(array $postIds): array
    {
        if (empty($postIds)) {
            //error_log("getByPostIds: postIds array is empty");
            return [];
        }

        try {
            // Create placeholder cho IN clause
            $placeholders = implode(',', array_fill(0, count($postIds), '?'));
            $stmt = $this->pdo->prepare("SELECT * FROM postassets WHERE post_id IN ($placeholders)");

            // Bind post_id
            foreach ($postIds as $index => $postId) {
                $stmt->bindValue($index + 1, $postId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //error_log("getByPostIds: fetched results - " . print_r($results, true));

            $postAssets = [];
            foreach ($results as $result) {
                $postAssets[$result['post_id']][] = new PostAsset(
                    $result['post_asset_id'],
                    $result['media_key'],
                    $result['post_id']
                );
            }
            //error_log("getByPostIds: postAssets - " . print_r($postAssets, true));
            return $postAssets;
        } catch (PDOException $e) {
            //error_log("Error getting post assets by post ids: " . $e->getMessage());
            throw new Exception("Error getting post assets by post ids: " . $e->getMessage());
        }
    }
}
