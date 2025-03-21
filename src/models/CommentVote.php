<?php
    class CommentVote {
        private $userId;
        private $commentId;
        private $voteType;

        /**
         * @param $userId
         * @param $commentId
         * @param $voteType
         */
        public function __construct($userId, $commentId, $voteType)
        {
            $this->userId = $userId;
            $this->commentId = $commentId;
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
        public function getCommentId()
        {
            return $this->commentId;
        }

        /**
         * @param mixed $commentId
         */
        public function setCommentId($commentId): void
        {
            $this->commentId = $commentId;
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