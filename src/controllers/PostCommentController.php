<?php

namespace controllers;

use dal\PostCommentDAOImpl;
use dal\PostDAOImpl;
use utils\SessionManager;
use dal\PostCommentVoteDAO;

class PostCommentController
{
    private $postCommentDAO;
    private $postDAO;
    private $commentVoteDAO;
    public function __construct()
    {
        $this->postCommentDAO = new PostCommentDAOImpl();
        $this->postDAO = new PostDAOImpl();
        $this->commentVoteDAO = new PostCommentVoteDAO();
    }

    public function getNumberComments($postId)
    {
        return $this->postCommentDAO->getNumberComments($postId);
    }

    public function replyComment()
    {
        $postCommentContent = trim(htmlspecialchars($_POST['postCommentContent'], ENT_QUOTES, 'UTF-8'));
        if (empty($postCommentContent)) {
            SessionManager::set('error', 'Comment cannot be empty');
            return;
        }
        $postCommentVoteScore = 0;
        $postCommentUserId = SessionManager::get('user_id');
        $parentCommentId = $_POST['parentCommentId'];
        $postCommentTimeStamp = date('Y-m-d H:i:s');
        $postId = $_POST['postId'];
        $this->postCommentDAO->insertReplyAComment($postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $postCommentTimeStamp, $postId);
        header("Location: /post/view/$postId");
    }

    public function comment()
    {
        $postCommentContent = trim(htmlspecialchars($_POST['postCommentContent'], ENT_QUOTES, 'UTF-8'));
        if (empty($postCommentContent)) {
            SessionManager::set('error', 'Comment cannot be empty');
            return;
        }
        $postCommentVoteScore = 0;
        $postCommentUserId = SessionManager::get('user_id');
        $postCommentTimeStamp = date('Y-m-d H:i:s');
        $postId = $_POST['postId'];
        $this->postCommentDAO->addCommentWithoutTitle($postCommentContent, $postCommentVoteScore, $postCommentUserId, $postCommentTimeStamp, $postId);
        header("Location: /post/view/$postId");
    }

    public function getComments($postId)
    {
        return $this->postCommentDAO->getCommentsByPostIdOrder($postId);
    }

    public function vote()
    {
        header("Content-Type: application/json; charset=UTF-8");
        if (!SessionManager::get('user')) {
            echo json_encode(["status" => false, "message" => "You must be logged in to vote!"]);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["status" => false, "message" => "Invalid request"]);
            exit();
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['commentId'])) {
            echo json_encode(["status" => false, "message" => "Invalid request data"]);
            exit();
        }

        $userId = SessionManager::get("user_id");
        $commentId = $data['commentId'];
        $voteType = $data['voteType']; // 1: Like, 0: Remove Vote
        $data = $this->commentVoteDAO->vote($userId, $commentId, $voteType);

        echo json_encode([
            "status" => true,
            "voteScore" => $data['voteScore'],
            "userLiked" => $data['userLiked']
        ]);
    }

    public function getVoteData($commentId, $userId)
    {
        return $this->commentVoteDAO->getVoteData($commentId, $userId);
    }

    public function deletecomment($commentId)
    {
        $this->postCommentDAO->deleteComment($commentId);
        $currentUrl = $_SERVER['HTTP_REFERER'] ?? '/quorae';
        header("Location: $currentUrl");
    }

    public function updateComment($commentId)
    {
        $postCommentContent = trim(htmlspecialchars($_POST['postCommentContent'], ENT_QUOTES, 'UTF-8'));
        if (empty($postCommentContent)) {
            SessionManager::set('error', 'Comment cannot be empty');
            return;
        }
        $this->postCommentDAO->updateCommentContent($commentId, $postCommentContent);
        $currentUrl = $_SERVER['HTTP_REFERER'] ?? '/quorae';
        header("Location: $currentUrl");
    }
}
