<?php

namespace dal;

interface PostDAOI
{
    //CRUD function
    //create new post
    public function getAllPosts();
    public function getPost($postId);
    public function getPostByTitle($postTitle);
    public function createPost($title, $content, $userId, $moduleId);
    public function updatePost($postId, $title, $content, $moduleId);
    public function updatePostTitle($postId, $title);
    public function updatePostContent($postId, $content);
    public function updatePostAsset($postId, $assetId);
    public function updatePostModule($postId, $moduleId);
    public function updateScore($postId, $voteScore);
    public function deletePost($postId);
    public function increaseVoteScore($postId);
    public function decreaseVoteScore($postId);
    public function getLastPostId();
    public function getPostsByUserId($userId);
    public function searchPosts($search);
}
