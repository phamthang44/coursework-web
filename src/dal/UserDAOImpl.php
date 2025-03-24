<?php

namespace dal;

use database\Database;
use models\User;
use PDO;

class UserDAOImpl implements UserDAOI
{
    private $pdo;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getUserById($userId)
    {
        $conn = $this->pdo;
        $sql = "SELECT * FROM Users WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        return $this->extracted($stmt);
    }

    public function getUserByUsername($username)
    {
        $conn = $this->pdo;
        $sql = "SELECT * FROM Users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        return $this->extracted($stmt);
    }

    public function signUpUser($email, $password, $username)
    {
        $conn = $this->pdo;
        $sql = "INSERT INTO Users (email, password, username) VALUES (:email, :password, :username)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
    }

    public function createUser($username, $lastName, $firstName, $email, $password, $profileImage = NULL, $bio = NULL, $dob = NULL)
    {
        $conn = $this->pdo;
        $sql = "INSERT INTO Users (username, last_name, first_name, email, password, profile_image_path, bio, dob) VALUES (:username, :lastName, :firstName, :email, :password, :profileImage, :bio, :dob)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        // if value is NULL, use PDO::PARAM_NULL
        $stmt->bindValue(':profileImage', $profileImage ?? null, PDO::PARAM_NULL);
        $stmt->bindValue(':bio', $bio ?? null, PDO::PARAM_NULL);
        $stmt->bindValue(':dob', $dob ?? null, PDO::PARAM_NULL);
        $stmt->execute();
    }

    public function checkUser($email)
    {  //check account validate
        $conn = $this->pdo;
        $sql = "SELECT * FROM Users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        return $this->extracted($stmt);
    }

    public function checkRoleUser($userId)
    {
        $conn = $this->pdo;
        $sql = "SELECT * FROM Users WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $row = $stmt->fetch();
        $user = new User($row['user_id'], $row['username'], $row['last_name'], $row['first_name'], $row['email'], $row['password'], $row['profile_image_path'], $row['bio'], $row['role'], $row['account_create_date'], $row['dob'], $row['status']);
        if ($user->getRole() === 'user') return true;
        return false;
    }

    public function updateUsername($userId, $username)
    {
        $conn = $this->pdo;
        $sql = "UPDATE Users SET username = :username WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':userId', $userId);
        $result = $stmt->execute();
        return $result;
    }

    public function updatePassword($userId, $password)
    {
        $conn = $this->pdo;
        $sql = "UPDATE Users SET password = :password WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':userId', $userId);
        $result = $stmt->execute();
        return $result;
    }

    public function updateProfileImage($userId, $profileImage)
    {
        $conn = $this->pdo;
        $sql = "UPDATE Users SET profile_image_path = :profileImage WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':profileImage', $profileImage);
        $stmt->bindParam(':userId', $userId);
        $result = $stmt->execute();
        return $result;
    }

    public function updateBio($userId, $bio)
    {
        $conn = $this->pdo;
        $sql = "UPDATE Users SET bio = :bio WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':userId', $userId);
        $result = $stmt->execute();
        return $result;
    }

    public function updateEmail($userId, $email)
    {
        $conn = $this->pdo;
        $sql = "UPDATE Users SET email = :email WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':userId', $userId);
        $result = $stmt->execute();
        return $result;
    }

    public function updateLastName($userId, $lastName)
    {
        $conn = $this->pdo;
        $sql = "UPDATE Users SET last_name = :lastName WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':userId', $userId);
        $result = $stmt->execute();
        return $result;
    }

    public function updateFirstName($userId, $firstName)
    {
        $conn = $this->pdo;
        $sql = "UPDATE Users SET first_name = :firstName WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':firstName', $lastName);
        $stmt->bindParam(':userId', $userId);
        $result = $stmt->execute();
        return $result;
    }

    public function updateDateOfBirth($userId, $dateOfBirth)
    {
        $conn = $this->pdo;
        $sql = "UPDATE Users SET dob = :dateOfBirth WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dateOfBirth', $dateOfBirth);
        $stmt->bindParam(':userId', $userId);
        $result = $stmt->execute();
        return $result;
    }
    //2025-03-04 03:39:56 format Current_Timestamp of MySQL and need all format datetime look like this
    public function updateProfile($userId, $username, $firstName, $lastName, $email, $profileImage, $bio, $dob)
    {
        $conn = $this->pdo;
        $sql = "UPDATE Users SET username = :username, first_name = :firstName, last_name = :lastName, email = :email, profile_image_path = :profileImage, bio = :bio, dob = :dob WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':profileImage', $profileImage);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':dob', $dob);
        if (!$stmt->execute()) {
            // Log or display the error
            $errorInfo = $stmt->errorInfo();
            throw new \Exception("Database error: " . $errorInfo[2]);
        }

        return true;
    }


    //need to consider again this function because need to see in controller to more detail
    public function updateRole($userId, $role)
    {
        if ($role === 'admin') {
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

    public function deleteUser($userId)
    {
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

        // $row = $stmt->fetch();
        // $user = new User($row['user_id'], $row['username'], $row['last_name'], $row['first_name'], $row['email'], $row['password'], $row['profile_image_path'], $row['bio'], $row['role'], $row['account_create_date'], $row['dob']);
        return $this->extracted($stmt);
    }

    public function checkExistedEmail($email)
    {
        $conn = $this->pdo;
        $sql = "SELECT COUNT(*) FROM Users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExists =  $stmt->fetchColumn();
        return $emailExists;
    }

    public function getLastUserID()
    {
        $conn = $this->pdo;
        $sql = "SELECT MAX(user_id) AS last_id FROM Users";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['user_id'] ?? 0;
    }

    /**
     * @param \PDOStatement $stmt
     * @return User
     */
    public function extracted(\PDOStatement $stmt)
    {
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false; // 
        }

        // 
        return new User(
            $row['user_id'] ?? null,
            $row['username'] ?? null,
            $row['last_name'] ?? null,
            $row['first_name'] ?? null,
            $row['email'] ?? null,
            $row['password'] ?? null,
            $row['profile_image_path'] ?? null,
            $row['bio'] ?? null,
            $row['role'] ?? null,
            $row['account_create_date'] ?? null,
            $row['dob'] ?? null,
            $row['status'] ?? null
        );
    }

    public function getTotalUserNums()
    {
        $conn = $this->pdo;
        $sql = "SELECT COUNT(*) FROM Users";
        $stmt = $conn->query($sql);
        $totalUsers = $stmt->fetchColumn();
        return $totalUsers;
    }

    public function getUserByUrl($firstName, $lastName, $userId)
    {
        $sql = "SELECT * FROM Users WHERE first_name = :firstName AND last_name = :lastName AND user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':userId', $userId);

        return $this->extracted($stmt);
    }

    private function convertRowsToUsers($rows)
    {
        $users = [];
        foreach ($rows as $row) {
            $user = new User(
                $row['user_id'],
                $row['username'],
                $row['last_name'],
                $row['first_name'],
                $row['email'],
                $row['password'],
                $row['profile_image_path'],
                $row['bio'],
                $row['role'],
                $row['account_create_date'],
                $row['dob'],
                $row['status']
            );
            $users[] = $user;
        }
        return $users;
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM Users";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->convertRowsToUsers($rows);
    }

    public function getUserPerPage($limit, $offset)
    {
        $sql = "SELECT * FROM Users LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->convertRowsToUsers($rows);
    }

    public function updateUserStatus($userId)
    {
        $sql = "UPDATE Users SET status = 'banned' WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }

    public function updateUserStatusUnbanned($userId)
    {
        $sql = "UPDATE Users SET status = 'active' WHERE user_id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }

    public function getModerators()
    {
        $sql = "SELECT * FROM Users WHERE role = 'admin'";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->convertRowsToUsers($rows);
    }
}
