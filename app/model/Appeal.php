<?php

namespace model;

use DateTime;

class Appeal
{
    private int $id;
    private User $user;
    private Ban $ban;
    private DateTime $created_at;
    private string $comment;

    /**
     * @param int $id
     * @param User $user
     * @param Ban $ban
     * @param DateTime $created_at
     * @param string $comment
     */
    public function __construct(int $id, User $user, Ban $ban, DateTime $created_at, string $comment)
    {
        $this->id = $id;
        $this->user = $user;
        $this->ban = $ban;
        $this->created_at = $created_at;
        $this->comment = $comment;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Ban
     */
    public function getBan(): Ban
    {
        return $this->ban;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }
}