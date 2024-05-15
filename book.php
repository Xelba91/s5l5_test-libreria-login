<?php
class Book {
    private $id;
    private $title;
    private $author;
    private $imageUrl;

    public function __construct($id, $title, $author, $imageUrl) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->imageUrl = $imageUrl;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setAuthor($author) {
        $this->author = $author;
    }

    public function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
    }
}
?>