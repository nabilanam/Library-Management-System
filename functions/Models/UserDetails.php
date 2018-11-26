<?php

class UserDetails
{
    private $id;
    private $gender;
    private $first_name;
    private $last_name;
    private $mobile_no;
    private $present_address;
    private $permanent_address;

    /**
     * UserDetails constructor.
     * @param string $id
     * @param DTO $gender
     * @param string $first_name
     * @param string $last_name
     * @param string $mobile_no
     * @param string $present_address
     * @param string $permanent_address
     */
    public function __construct($id, $gender, $first_name, $last_name, $mobile_no, $present_address, $permanent_address)
    {
        $this->id = $id;
        $this->gender = $gender;
        $this->mobile_no = $mobile_no;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->present_address = $present_address;
        $this->permanent_address = $permanent_address;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DTO
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param DTO $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getMobileNo()
    {
        return $this->mobile_no;
    }

    /**
     * @param string $mobile_no
     */
    public function setMobileNo($mobile_no)
    {
        $this->mobile_no = $mobile_no;
    }

    /**
     * @return string
     */
    public function getPresentAddress()
    {
        return $this->present_address;
    }

    /**
     * @param string $present_address
     */
    public function setPresentAddress($present_address)
    {
        $this->present_address = $present_address;
    }

    /**
     * @return string
     */
    public function getPermanentAddress()
    {
        return $this->permanent_address;
    }

    /**
     * @param string $permanent_address
     */
    public function setPermanentAddress($permanent_address)
    {
        $this->permanent_address = $permanent_address;
    }

}