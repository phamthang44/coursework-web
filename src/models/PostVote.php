<?php
    class PostVote {
        private $userId;
        private $postId;
        private $voteType;

        /**
         * @param $userId
         * @param $postId
         * @param $voteType
         */
        public function __construct($userId, $postId, $voteType)
        {
            $this->userId = $userId;
            $this->postId = $postId;
            $this->voteType = $voteType;
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
        public function setUserId($userId): void
        {
            $this->userId = $userId;
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
        public function setPostId($postId): void
        {
            $this->postId = $postId;
        }

        /**
         * @return mixed
         */
        public function getVoteType()
        {
            return $this->voteType;
        }

        /**
         * @param mixed $voteType
         */
        public function setVoteType($voteType): void
        {
            $this->voteType = $voteType;
        }

    }