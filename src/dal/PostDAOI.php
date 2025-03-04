<?php
    interface PostDAOI {
        //CRUD function
        //create new post
        public function getAllPosts();
        public function getPost($postId);
        public function getPostByTitle($postTitle);
        public function createPost($title, $content, $postAssetId, $userId, $moduleId);
        public function updatePost($postId, $title, $content, $postAssetId, $moduleId, $updatedTimestamp);
        public function updatePostTitle($postId, $title, $updatedTimestamp);
        public function updatePostContent($postId, $content, $updatedTimestamp);
        public function updatePostAsset($postId, $assetId, $updatedTimestamp);
        public function updatePostModule($postId, $moduleId, $updatedTimestamp);
        public function updateScore($postId, $voteScore);
        public function deletePost($postId);
    }
?>