<?php

namespace model;

use DateTime;

class Review
{
    private int $id;
    private int $userId;
    private int $productId;
    private string $comment;
    private int $numberStars;
    private DateTime $createdAt;

    public function __construct(
        int      $id,
        int      $userId,
        int      $productId,
        string   $comment,
        int      $numberStars,
        DateTime $createdAt
    )
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->comment = $comment;
        $this->numberStars = $numberStars;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getNumberStars(): int
    {
        return $this->numberStars;
    }

    public function setNumberStars(int $numberStars): void
    {
        $this->numberStars = $numberStars;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
