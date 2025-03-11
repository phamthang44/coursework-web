<?php

namespace dal;

interface  PostCommentI
{
    public function getComments();
    public function getComment($commentId);
    public function addComment($postCommentTitle, $postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $postCommentTimeStamp, $postCommentUpdatedTimeStamp, $postId);
    public function addCommentWithoutTitle($postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $postCommentTimeStamp, $postCommentUpdatedTimeStamp, $postId);
    public function updateComment($comment);
    public function deleteComment($commentId);
    public function updateScore($postCommentId, $commentScore);
}
