<?php

namespace model;

class Business
{
    private int $id;
    private string $name;
    private string $email;
    private string $type;
    private string $websiteURL;
    private string $ne;
    private string $description;
    private int $ownerId;
    private int $addressId;

    /*
     * name, creationDate & NE ne peuvent pas être changé sans parler avec un administrateur.
     */

    /**
     * @param int $id
     * @param int $ownerId
     * @param int $addressId
     * @param string $name
     * @param string $email
     * @param string $url
     * @param string $type
     * @param string $ne
     * @param string $description
     */
    public function __construct(int $id, int $ownerId, int $addressId, string $name, string $email, string $url, string $type, string $ne, string $description)
    {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->name = $name;
        $this->email = $email;
        $this->type = $type;
        $this->websiteURL = $url;
        $this->ne = $ne;
        $this->description = $description;
        $this->addressId = $addressId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * @return int
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
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
     * @return string
     */
    public function getNe(): string
    {
        return $this->ne;
    }

    /**
     * @return string
     */
    public function getWebsiteURL(): string
    {
        return $this->websiteURL;
    }

    /**
     * @param string $websiteURL
     */
    public function setWebsiteURL(string $websiteURL): void
    {
        $this->websiteURL = $websiteURL;
    }
}