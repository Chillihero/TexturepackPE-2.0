<?php

class User {

    /** @var string $name */
    private $name;

    /** @var int $createTime */
    private $createTime;

    /** @var string $password */
    private $password;

    /** @var string $mailAddress */
    private $mailAddress;

    /** @var Rank $rank */
    private $rank;

    /**
     * User constructor.
     * @param string $name
     * @param string $password
     * @param string $mailAddress
     * @param int $createTime
     * @param Rank $rank
     */
    public function __construct(string $name, string $password, string $mailAddress, int $createTime, Rank $rank)
    {
        $this->name = $name;
        $this->createTime = $createTime;
        $this->password = $password;
        $this->mailAddress = $mailAddress;
        $this->rank = $rank;
        return;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Returns player name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns hashed password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Returns Create Time in INT -> time()
     *
     * @return int
     */
    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    /**
     * Returns E-Mail Address of Player as string
     *
     * @return string
     */
    public function getMailAddress(): string
    {
        return $this->mailAddress;
    }

    /**
     * Returns current Rank of User. See @link Rank  for help
     * @return Rank
     */
    public function getRank(): Rank
    {
        return $this->rank;
    }
}