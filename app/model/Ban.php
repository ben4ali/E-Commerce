<?php

namespace model;

use DateTime;

class Ban
{
    private int $id;
    private User $user;
    private User $admin;
    private DateTime $created_at;
    private string $reason;

    public function __construct(int $id, User $user, User $admin, DateTime $created_at, string $reason)
    {
        $this->id = $id;
        $this->user = $user;
        $this->admin = $admin;
        $this->created_at = $created_at;
        $this->reason = $reason;
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
     * @return User
     */
    public function getAdmin(): User
    {
        return $this->admin;
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
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }
}