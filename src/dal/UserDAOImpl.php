<?php
    namespace DAO;
    use database\Database;
    use models\User;

    class UserDAOImpl implements UserDAOI {
        private $pdo;
        public function __construct() {
            $db = new Database();
            $this->pdo = $db->getConnection();
        }

       public function getUserById($userId) {
           $conn = $this->pdo;
           $sql = "SELECT * FROM Users WHERE user_id = :userId";
           $stmt = $conn->prepare($sql);
           $stmt->bindParam(':userId', $userId);
           return $this->extracted($stmt);
       }

        public function getUserByUsername($username) {
              $conn = $this->pdo;
              $sql = "SELECT * FROM Users WHERE username = :username";
              $stmt = $conn->prepare($sql);
              $stmt->bindParam(':username', $username);
              return $this->extracted($stmt);
        }

        
        public function createUser($username, $lastName, $firstName, $email, $password, $profileImage, $bio, $dob) {
            $conn = $this->pdo;
            $sql = "INSERT INTO Users (username, last_name, first_name, email, password, profile_image_path, bio, dob) VALUES (:username, :lastName, :firstName, :email, :password, :profileImage, :bio, :dob)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':profileImage', $profileImage);
            $stmt->bindParam(':bio', $bio);
            $stmt->bindParam(':dob', $dob);
            $stmt->execute();
        }

        public function checkUser($username, $password) {  //check account validate
            $conn = $this->pdo;
            $sql = "SELECT * FROM Users WHERE username = :username AND password = :password";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            return $this->extracted($stmt);
        }

        public function checkRoleUser($userId) {
            $conn = $this->pdo;
            $sql = "SELECT * FROM Users WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $row = $stmt->fetch();
            $user = new User($row['user_id'], $row['username'], $row['last_name'], $row['first_name'], $row['email'], $row['password'], $row['profile_image_path'], $row['bio'], $row['role'], $row['account_create_date'], $row['dob']);
            if($user->getRole() === 'user') return true;
            return false;
        }

        public function updateUsername($userId, $username) {
            $conn = $this->pdo;
            $sql = "UPDATE Users SET username = :username WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            return $result;
        }

        public function updatePassword($userId, $password) {
            $conn = $this->pdo;
            $sql = "UPDATE Users SET password = :password WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            return $result;
        }

        public function updateProfileImage($userId, $profileImage) {
            $conn = $this->pdo;
            $sql = "UPDATE Users SET profile_image_path = :profileImage WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':profileImage', $profileImage);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            return $result;
        }

        public function updateBio($userId, $bio) {
            $conn = $this->pdo;
            $sql = "UPDATE Users SET bio = :bio WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':bio', $bio);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            return $result;
        }

        public function updateEmail($userId, $email) {
            $conn = $this->pdo;
            $sql = "UPDATE Users SET email = :email WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            return $result;
        }

        public function updateLastName($userId, $lastName) {
            $conn = $this->pdo;
            $sql = "UPDATE Users SET last_name = :lastName WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            return $result;
        }

        public function updateFirstName($userId, $firstName) {
            $conn = $this->pdo;
            $sql = "UPDATE Users SET first_name = :firstName WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':firstName', $lastName);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            return $result;
        }

        public function updateDateOfBirth($userId, $dateOfBirth) {
            $conn = $this->pdo;
            $sql = "UPDATE Users SET dob = :dateOfBirth WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':dateOfBirth', $dateOfBirth);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            return $result;
        }
        //2025-03-04 03:39:56 format Current_Timestamp of MySQL and need all format datetime look like this



        //need to consider again this function because need to see in controller to more detail
        public function updateRole($userId, $role) {
            if($role === 'admin') {
                $conn = $this->pdo;
                $sql = "UPDATE Users SET role = :role WHERE user_id = :userId";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':role', $role);
                $stmt->bindParam(':userId', $userId);
                $result = $stmt->execute();
                return $result;
            }
            return false;
        }

        public function deleteUser($userId) {
            $conn = $this->pdo;
            $sql = "DELETE FROM Users WHERE user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $result = $stmt->execute();
            return $result;
        }

        public function getUserByEmail($email)
        {
            // TODO: Implement getUserByEmail() method.
            $conn = $this->pdo;
            $sql = "SELECT * FROM Users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $row = $stmt->fetch();
            $user = new User($row['user_id'], $row['username'], $row['last_name'], $row['first_name'], $row['email'], $row['password'], $row['profile_image_path'], $row['bio'], $row['role'], $row['account_create_date'], $row['dob']);
            return $user;
        }

        /**
         * @param \PDOStatement $stmt
         * @return User
         */
        public function extracted(\PDOStatement $stmt)
        {
            $stmt->execute();
            $row = $stmt->fetch();
            $user = new User($row['user_id'], $row['username'], $row['last_name'], $row['first_name'], $row['email'], $row['password'], $row['profile_image_path'], $row['bio'], $row['role'], $row['account_create_date'], $row['dob']);
            return $user;
        }



    }
