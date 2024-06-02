<?php

/**
 * Auteur : Antoine Langevin
 * Dernière modification : 2023-11-06
 */

namespace model;

use DateTime;

class Item
{
    private int $id;
    private int $businessId;
    private string $name;
    private string $description;
    private float $price;
    private int $quantity;
    private string $upc;
    private int $categoryId;
    private string $imageUrl;
    private string $details;
    private string $htmlData;
    private int $merchantId;
    private DateTime $registeredDate;

    /**
     * @param int $id
     * @param int $businessId
     * @param string $name
     * @param string $description
     * @param float $price
     * @param int $quantity
     * @param string $upc
     * @param int $categoryId
     * @param string $imageUrl
     * @param string $htmlData
     * @param int $merchantId
     * @param DateTime $registered_date
     */
    public function __construct(int    $id, int $businessId, string $name, string $description, float $price,
                                int    $quantity, string $upc, int $categoryId, string $imageUrl,
                                string $htmlData, int $merchantId, DateTime $registered_date)
    {
        $this->id = $id;
        $this->businessId = $businessId;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->upc = $upc;
        $this->categoryId = $categoryId;
        $this->imageUrl = $imageUrl;
        $this->htmlData = $htmlData;
        $this->merchantId = $merchantId;
        $this->registeredDate = $registered_date;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getHtmlData(): string
    {
        return $this->htmlData;
    }

    /**
     * @param string $htmlData
     */
    public function setHtmlData(string $htmlData): void
    {
        $this->htmlData = $htmlData;
    }

    /**
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    /**
     * @param int $merchantId
     */
    public function setMerchantId(int $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @return DateTime
     */
    public function getRegisteredDate(): DateTime
    {
        return $this->registeredDate;
    }

    /**
     * @return int
     */
    public function getBusinessId(): int
    {
        return $this->businessId;
    }

    public function setBusinessId(int $businessId): void
    {
        $this->businessId = $businessId;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @param string $details
     */
    public function setDetails(string $details): void
    {
        $this->details = $details;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getUpc(): string
    {
        return $this->upc;
    }

    /**
     * @param string $upc
     */
    public function setUpc(string $upc): void
    {
        $this->upc = $upc;
    }
}