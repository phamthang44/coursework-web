<?php
    namespace database;
    use \PDO;
    use \PDOException;
    class Database {
        private $host = "localhost";
        private $db_name = "student_stack_overflow";
        private $username = "root";
        private $password = "phamthang20042005";
        private $conn;
        
        public function getConnection() {
            try {
                $this->conn = new PDO("mysql:host=".$this->host.";port=3307;dbname=".$this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                return null;
            }
        }
    }
?>