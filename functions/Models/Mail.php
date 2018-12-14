<?php

class Mail
{
    private $id;
    private $address;
    private $subject;
    private $message;
    private $dtime;

    /**
     * Mail constructor.
     * @param $address
     * @param $subject
     * @param $message
     */
    public function __construct($id ,$address, $subject, $message, $dtime=null)
    {
        $this->address = $address;
        $this->subject = $subject;
        $this->message = $message;
        if (empty($dtime)){
            $this->dtime = getNow();
        }else{
            $this->dtime = $dtime;
        }
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
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getDtime()
    {
        return $this->dtime;
    }

    /**
     * @param mixed $dtime
     */
    public function setDtime($dtime)
    {
        $this->dtime = $dtime;
    }
}