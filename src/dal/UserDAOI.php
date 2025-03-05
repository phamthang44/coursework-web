<?php

namespace DAO;

interface UserDAOI {
    public function getUserById($userId);
    public function getUserByEmail($email);
    public function getUserByUsername($username);
    public function createUser($username, $lastName, $firstName, $email, $password, $profileImage, $bio, $dob);
    public function checkUser($username, $password);
    public function checkRoleUser($userId);
    public function updateUsername($userId, $username);
    public function updatePassword($userId, $password);
    public function updateProfileImage($userId, $profileImage);
    public function updateBio($userId, $bio);
    public function updateEmail($userId, $email);
    public function updateLastName($userId, $lastName);
    public function updateFirstName($userId, $firstName);
    public function updateDateOfBirth($userId, $dateOfBirth);
    public function updateRole($userId, $role);
    public function deleteUser($userId);
}
