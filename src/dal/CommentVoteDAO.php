<?php
namespace dal;
use database\Database;

class CommentVoteDAO {
    private $pdo;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }
}