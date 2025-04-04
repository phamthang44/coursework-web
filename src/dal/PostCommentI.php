<?php

namespace dal;

interface  PostCommentI
{
    public function getComments();
    public function getComment($commentId);
    public function addComment($postCommentTitle, $postCommentContent, $postCommentVoteScore, $postCommentUserId, $commentCreateDate, $commentUpdateDate, $postId);
    public function addCommentWithoutTitle($postCommentContent, $postCommentVoteScore, $postCommentUserId, $postCommentTimeStamp, $postId);
    public function updateComment($comment);
    public function deleteComment($commentId);
    public function updateScore($postCommentId, $commentScore);
    public function getNumberComments($postId);
    public function insertReplyAComment($postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $postCommentTimeStamp, $postId);
}
