<?php

class MailSettings
{
    private $host;
    private $username;
    private $password;
    private $port;

    /**
     * MailSettings constructor.
     * @param $host
     * @param $username
     * @param $password
     * @param $port
     */
    public function __construct($host, $username, $password, $port)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

}