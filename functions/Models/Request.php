<?php

class Request
{
    private $id;
    /* @var Book */
    private $book;
    /* @var User */
    private $user;
    /* @var DTO */
    private $status;
    private $request_date;
    private $issue_date;
    private $return_date;
    private $receive_date;
    private $total_fine;
    /* @var int */
    private $is_user_read;

    /**
     * Request constructor.
     * @param $id
     * @param Book $book
     * @param User $user
     * @param DTO $status
     * @param $request_date
     * @param $issue_date
     * @param $return_date
     * @param $receive_date
     * @param $total_fine
     * @param int $is_user_read
     */
    public function __construct($id, Book $book, User $user, DTO $status, $request_date, $issue_date, $return_date, $receive_date, $total_fine, $is_user_read)
    {
        $this->id = $id;
        $this->book = $book;
        $this->user = $user;
        $this->status = $status;
        $this->request_date = $request_date;
        $this->issue_date = $issue_date;
        $this->return_date = $return_date;
        $this->receive_date = $receive_date;
        $this->total_fine = $total_fine;
        $this->is_user_read = $is_user_read;
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
     * @return Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param Book $book
     */
    public function setBook($book)
    {
        $this->book = $book;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return DTO
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param DTO $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getRequestDate()
    {
        return $this->request_date;
    }

    /**
     * @param mixed $request_date
     */
    public function setRequestDate($request_date)
    {
        $this->request_date = $request_date;
    }

    /**
     * @return mixed
     */
    public function getIssueDate()
    {
        return $this->issue_date;
    }

    /**
     * @param mixed $issue_date
     */
    public function setIssueDate($issue_date)
    {
        $this->issue_date = $issue_date;
    }

    /**
     * @return mixed
     */
    public function getReturnDate()
    {
        return $this->return_date;
    }

    /**
     * @param mixed $return_date
     */
    public function setReturnDate($return_date)
    {
        $this->return_date = $return_date;
    }

    /**
     * @return mixed
     */
    public function getReceiveDate()
    {
        return $this->receive_date;
    }

    /**
     * @param mixed $receive_date
     */
    public function setReceiveDate($receive_date)
    {
        $this->receive_date = $receive_date;
    }

    /**
     * @return mixed
     */
    public function getTotalFine()
    {
        return $this->total_fine;
    }

    /**
     * @param mixed $total_fine
     */
    public function setTotalFine($total_fine)
    {
        $this->total_fine = $total_fine;
    }

    /**
     * @return int
     */
    public function getUserRead()
    {
        return $this->is_user_read;
    }

    /**
     * @param int $is_user_read
     */
    public function setUserRead($is_user_read)
    {
        $this->is_user_read = $is_user_read;
    }


}