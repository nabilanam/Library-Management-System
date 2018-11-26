<?php

class Book
{
    private $id;
    private $shelf;
    private $publisher;
    private $source;
    private $condition;
    private $category;

    private $isbn;
    private $title;
    private $subtitle;

    private $edition;
    private $edition_year;
    private $publication_year;

    private $total_pages;
    private $total_copies;
    private $available_copies;

    private $price;
    private $note;

    private $cover_path;
    private $ebook_path;


    /////////////////////////////////////// Getters & Setters ////////////////////////////////////////////////

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DTO
     */
    public function getShelf()
    {
        return $this->shelf;
    }

    /**
     * @param DTO $shelf
     */
    public function setShelf($shelf)
    {
        $this->shelf = $shelf;
    }

    /**
     * @return DTO
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param DTO $publisher
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @return DTO
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param DTO $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return DTO
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param DTO $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return DTO
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param DTO $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return string
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * @param string $edition
     */
    public function setEdition($edition)
    {
        $this->edition = $edition;
    }

    /**
     * @return int
     */
    public function getEditionYear()
    {
        return $this->edition_year;
    }

    /**
     * @param int $edition_year
     */
    public function setEditionYear($edition_year)
    {
        $this->edition_year = $edition_year;
    }

    /**
     * @return string
     */
    public function getPublicationYear()
    {
        return $this->publication_year;
    }

    /**
     * @param string $publication_year
     */
    public function setPublicationYear($publication_year)
    {
        $this->publication_year = $publication_year;
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->total_pages;
    }

    /**
     * @param int $total_pages
     */
    public function setTotalPages($total_pages)
    {
        $this->total_pages = $total_pages;
    }

    /**
     * @return int
     */
    public function getTotalCopies()
    {
        return $this->total_copies;
    }

    /**
     * @param int $total_copies
     */
    public function setTotalCopies($total_copies)
    {
        $this->total_copies = $total_copies;
    }

    /**
     * @return int
     */
    public function getAvailableCopies()
    {
        return $this->available_copies;
    }

    /**
     * @param int $available_copies
     */
    public function setAvailableCopies($available_copies)
    {
        $this->available_copies = $available_copies;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getCoverPath()
    {
        return $this->cover_path;
    }

    /**
     * @param string $cover_path
     */
    public function setCoverPath($cover_path)
    {
        $this->cover_path = $cover_path;
    }

    /**
     * @return string
     */
    public function getEbookPath()
    {
        return $this->ebook_path;
    }

    /**
     * @param string $ebook_path
     */
    public function setEbookPath($ebook_path)
    {
        $this->ebook_path = $ebook_path;
    }

}