<?php

class TexturePack {

    /** @var string $author */
    private $author;

    /** @var string $packName */
    private $packName;

    /** @var string $image */
    private $image;

    /** @var string $link */
    private $link;

    /** @var string $creator */
    private $creator;

    private $id;

    public function __construct(string $packName, string $author, string $creator, string $link, string $image, int $id)
    {
        $this->packName = $packName;
        $this->author = $author;
        $this->image = $image;
        $this->link = $link;
        $this->creator = $creator;
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getAuthor(): String
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->packName;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getCreator(): string
    {
        return $this->creator;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}