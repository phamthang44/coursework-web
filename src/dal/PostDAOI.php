<?php
    namespace DAO;
    interface PostDAOI {
        //CRUD function
        //create new post
        public function getAllPosts();
        public function getPost($postId);
        public function getPostByTitle($postTitle);
        public function createPost($title, $content, $postAssetId, $userId, $moduleId);
        public function updatePost($postId, $title, $content, $postAssetId, $moduleId);
        public function updatePostTitle($postId, $title);
        public function updatePostContent($postId, $content);
        public function updatePostAsset($postId, $assetId);
        public function updatePostModule($postId, $moduleId);
        public function updateScore($postId, $voteScore);
        public function deletePost($postId);
        public function increaseVoteScore($postId, $voteScore);
        public function decreaseVoteScore($postId, $voteScore);
    }
