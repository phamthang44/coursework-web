<?php

namespace models;

use JsonSerializable;

class Post implements JsonSerializable
{
    private $postId;
    private $title;
    private $content;
    private $voteScore;
    private $userId;
    private $moduleId;
    private $timestamp;
    private $updatedTimestamp;


    /**
     * @param $postId
     * @param $title
     * @param $content
     * @param $voteScore
     * @param $userId
     * @param $moduleId
     * @param $timestamp
     * @param $updatedTimestamp
     * @param $postAssetId
     */
    public function __construct($postId, $title, $content, $voteScore, $userId, $moduleId, $timestamp, $updatedTimestamp)
    {
        $this->postId = $postId;
        $this->title = $title;
        $this->content = $content;
        $this->voteScore = $voteScore;
        $this->userId = $userId;
        $this->moduleId = $moduleId;
        $this->timestamp = $timestamp;
        $this->updatedTimestamp = $updatedTimestamp;
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

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getVoteScore()
    {
        return $this->voteScore;
    }

    /**
     * @param mixed $voteScore
     */
    public function setVoteScore($voteScore)
    {
        $this->voteScore = $voteScore;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * @param mixed $moduleId
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getUpdatedTimestamp()
    {
        return $this->updatedTimestamp;
    }

    /**
     * @param mixed $updatedTimestamp
     */
    public function setUpdatedTimestamp($updatedTimestamp)
    {
        $this->updatedTimestamp = $updatedTimestamp;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
