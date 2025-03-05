<?php
    namespace DAO;
    interface  PostCommentI {
        public function getComments();
        public function getComment($postCommentId);
        public function addCommentWithoutTitle($postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $postCommentTimeStamp, $postCommentUpdatedTimeStamp, $postId);
        public function addComment($postCommentTitle, $postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $postCommentTimeStamp, $postCommentUpdatedTimeStamp, $postId);
        public function updateCommentTitle($postCommentId, $postCommentTitle);
        public function updateCommentContent($postCommentContent);
        public function increaseVoteScore($postCommentId, $postCommentVoteScore);
        public function decreaseVoteScore($postCommentId, $postCommentVoteScore);
        public function deleteComment($postCommentId);
        public function getCommentByUserId($userId);
        public function getTimeStamp();
        public function updatedTimeStamp();
        public function getCommentsByPostId($postId);

    }

