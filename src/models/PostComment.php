<?php

namespace models;

class PostComment
{
    private $postCommentId;
    private $postCommentTitle;
    private $postCommentContent;
    private $postCommentVoteScore;
    private $postCommentUserId;
    private $parentCommentId;
    private $postCommentTimeStamp;
    private $postCommentUpdatedTimeStamp;
    private $postId;
    private $userId;

    /**
     * @param $postCommentId
     * @param $postCommentTitle
     * @param $postCommentContent
     * @param $postCommentVoteScore
     * @param $postCommentUserId
     * @param $parentCommentId
     * @param $postCommentTimeStamp
     * @param $postCommentUpdatedTimeStamp
     * @param $postId
     */
    public function __construct($postCommentId, $postCommentTitle, $postCommentContent, $postCommentVoteScore, $postCommentUserId, $parentCommentId, $postCommentTimeStamp, $postCommentUpdatedTimeStamp, $postId)
    {
        $this->postCommentId = $postCommentId;
        $this->postCommentTitle = $postCommentTitle;
        $this->postCommentContent = $postCommentContent;
        $this->postCommentVoteScore = $postCommentVoteScore;
        $this->postCommentUserId = $postCommentUserId;
        $this->parentCommentId = $parentCommentId;
        $this->postCommentTimeStamp = $postCommentTimeStamp;
        $this->postCommentUpdatedTimeStamp = $postCommentUpdatedTimeStamp;
        $this->postId = $postId;
    }

    /**
     * @return mixed
     */
    public function getPostCommentId()
    {
        return $this->postCommentId;
    }

    /**
     * @param mixed $postCommentId
     */
    public function setPostCommentId($postCommentId)
    {
        $this->postCommentId = $postCommentId;
    }

    /**
     * @return mixed
     */
    public function getPostCommentTitle()
    {
        return $this->postCommentTitle;
    }

    /**
     * @param mixed $postCommentTitle
     */
    public function setPostCommentTitle($postCommentTitle)
    {
        $this->postCommentTitle = $postCommentTitle;
    }

    /**
     * @return mixed
     */
    public function getPostCommentContent()
    {
        return $this->postCommentContent;
    }

    /**
     * @param mixed $postCommentContent
     */
    public function setPostCommentContent($postCommentContent)
    {
        $this->postCommentContent = $postCommentContent;
    }

    /**
     * @return mixed
     */
    public function getPostCommentVoteScore()
    {
        return $this->postCommentVoteScore;
    }

    /**
     * @param mixed $postCommentVoteScore
     */
    public function setPostCommentVoteScore($postCommentVoteScore)
    {
        $this->postCommentVoteScore = $postCommentVoteScore;
    }

    /**
     * @return mixed
     */
    public function getPostCommentUserId()
    {
        return $this->postCommentUserId;
    }

    /**
     * @param mixed $postCommentUserId
     */
    public function setPostCommentUserId($postCommentUserId)
    {
        $this->postCommentUserId = $postCommentUserId;
    }

    /**
     * @return mixed
     */
    public function getParentCommentId()
    {
        return $this->parentCommentId;
    }

    /**
     * @param mixed $parentCommentId
     */
    public function setParentCommentId($parentCommentId)
    {
        $this->parentCommentId = $parentCommentId;
    }

    /**
     * @return mixed
     */
    public function getPostCommentTimeStamp()
    {
        return $this->postCommentTimeStamp;
    }

    /**
     * @param mixed $postCommentTimeStamp
     */
    public function setPostCommentTimeStamp($postCommentTimeStamp)
    {
        $this->postCommentTimeStamp = $postCommentTimeStamp;
    }

    /**
     * @return mixed
     */
    public function getPostCommentUpdatedTimeStamp()
    {
        return $this->postCommentUpdatedTimeStamp;
    }

    /**
     * @param mixed $postCommentUpdatedTimeStamp
     */
    public function setPostCommentUpdatedTimeStamp($postCommentUpdatedTimeStamp)
    {
        $this->postCommentUpdatedTimeStamp = $postCommentUpdatedTimeStamp;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @param mixed $postId
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;
    }

    public function timeAgo($timestamp)
    {
        $time = strtotime($timestamp);
        $diff = time() - $time;

        $units = [
            31536000 => 'year',  // 60 * 60 * 24 * 365
            2592000  => 'month', // 60 * 60 * 24 * 30
            604800   => 'week',  // 60 * 60 * 24 * 7
            86400    => 'day',  // 60 * 60 * 24
            3600     => 'hour',   // 60 * 60
            60       => 'minute',
            1        => 'second'
        ];

        foreach ($units as $unit => $text) {
            if ($diff >= $unit) {
                $value = floor($diff / $unit);
                return "$value $text" . ($value > 1 ? 's' : '') . " ago";
            }
        }

        return "Just now";
    }
}
