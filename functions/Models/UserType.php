<?php

class UserType
{
    private $id;
    private $name;
    private $book_limit;
    private $day_limit;
    private $fine_per_day;

    /**
     * UserType constructor.
     * @param $id
     * @param $name
     * @param $book_limit
     * @param $day_limit
     * @param $fine_per_day
     */
    public function __construct($id, $name, $book_limit, $day_limit, $fine_per_day)
    {
        $this->id = $id;
        $this->name = $name;
        $this->book_limit = $book_limit;
        $this->day_limit = $day_limit;
        $this->fine_per_day = $fine_per_day;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBookLimit()
    {
        return $this->book_limit;
    }

    /**
     * @param mixed $book_limit
     */
    public function setBookLimit($book_limit)
    {
        $this->book_limit = $book_limit;
    }

    /**
     * @return mixed
     */
    public function getDayLimit()
    {
        return $this->day_limit;
    }

    /**
     * @param mixed $day_limit
     */
    public function setDayLimit($day_limit)
    {
        $this->day_limit = $day_limit;
    }

    /**
     * @return mixed
     */
    public function getFinePerDay()
    {
        return $this->fine_per_day;
    }

    /**
     * @param mixed $fine_per_day
     */
    public function setFinePerDay($fine_per_day)
    {
        $this->fine_per_day = $fine_per_day;
    }

}