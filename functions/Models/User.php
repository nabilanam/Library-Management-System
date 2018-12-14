<?php

class User
{
    private $id;
    /* @var UserType */
    private $user_type;
    /* @var UserDetails */
    private $user_details;
    private $email;
    private $passwordHash;
    private $validation_code;
    private $activated;
    private $activation_dtime;

    /**
     * User constructor.
     * @param $id
     * @param UserType $user_type
     * @param UserDetails $user_details
     * @param $email
     * @param $password_hash
     * @param $validation_code
     * @param $activated
     */
    public function __construct($id, UserType $user_type, UserDetails $user_details, $email, $password_hash, $validation_code, $activated, $activation_dtime=null)
    {
        $this->id = $id;
        $this->user_type = $user_type;
        $this->user_details = $user_details;
        $this->email = $email;
        $this->passwordHash = $password_hash;
        $this->validation_code = $validation_code;
        $this->activated = $activated;
        $this->activation_dtime = $activation_dtime;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return UserType
     */
    public function getUserType()
    {
        return $this->user_type;
    }

    /**
     * @param UserType $user_type
     */
    public function setUserType($user_type)
    {
        $this->user_type = $user_type;
    }

    /**
     * @return UserDetails
     */
    public function getUserDetails()
    {
        return $this->user_details;
    }

    /**
     * @param UserDetails $user_details
     */
    public function setUserDetails($user_details)
    {
        $this->user_details = $user_details;
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
    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @param mixed $hash
     */
    public function setPasswordHash($hash)
    {
        $this->passwordHash = $hash;
    }

    /**
     * @return mixed
     */
    public function getValidationCode()
    {
        return $this->validation_code;
    }

    /**
     * @param mixed $validation_code
     */
    public function setValidationCode($validation_code)
    {
        $this->validation_code = $validation_code;
    }

    /**
     * @return mixed
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @param mixed $activated
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
    }

    /**
     * @return mixed
     */
    public function getActivationDatetime()
    {
        return $this->activation_dtime;
    }

    /**
     * @param mixed $activation_dtime
     */
    public function setActivationDatetime($activation_dtime)
    {
        $this->activation_dtime = $activation_dtime;
    }
}