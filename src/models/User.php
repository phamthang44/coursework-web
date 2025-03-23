<?php

namespace models;

class User
{
    private $userId;
    private $username;
    private $lastName;
    private $firstName;
    private $email;
    private $password;
    private $profileImage;
    private $bio;
    private $role;
    private $createdAccountDate;
    private $dob;
    private $status;

    /**
     * @param $userId
     * @param $username
     * @param $lastName
     * @param $firstName
     * @param $email
     * @param $password
     * @param $profileImage
     * @param $bio
     * @param $role
     * @param $createdAccountDate
     * @param $dob
     */
    public function __construct($userId, $username, $lastName, $firstName, $email, $password, $profileImage, $bio, $role, $createdAccountDate, $dob, $status)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->email = $email;
        $this->password = $password;
        $this->profileImage = $profileImage;
        $this->bio = $bio;
        $this->role = $role;
        $this->createdAccountDate = $createdAccountDate;
        $this->dob = $dob;
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getProfileImage()
    {
        return $this->profileImage;
    }

    /**
     * @param mixed $profileImage
     */
    public function setProfileImage($profileImage)
    {
        $this->profileImage = $profileImage;
    }

    /**
     * @return mixed
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param mixed $bio
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getCreatedAccountDate()
    {
        return $this->createdAccountDate;
    }

    /**
     * @param mixed $createdAccountDate
     */
    public function setCreatedAccountDate($createdAccountDate)
    {
        $this->createdAccountDate = $createdAccountDate;
    }

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param mixed $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }
}
