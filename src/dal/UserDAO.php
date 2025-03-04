<?php 
    require_once 'database.php';
    require_once 'User.php';

    class UserDAO {
        private $pdo;
        public function __construct() {
            $db = new Database();
            $this->pdo = $db->getConnection();
        }
//        public function getUserById($userId) {
//            $conn = $this->pdo;
//            $sql = "SELECT * FROM Users WHERE user_id = :userId";
//            $stmt = $conn->prepare($sql);
//            $stmt->bindParam(':userId', $userId);
//            $stmt->execute();
//            $row = $stmt->fetch();
//            $user = new User($row['user_id'], $row['username'], $row['last_name'], $row['first_name'], $row['email'], $row['password'], $row['profile_image'], $row['bio'], $row['role'], $row['created_account_date'], $row['dob']);
//            return $user;
//        }
        
    }
?>