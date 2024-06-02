<?php

/**
 * Auteur : Antoine Langevin
 * Dernière modification : 2023-12-01
 */

namespace model;

require_once "User.php";

class Merchant extends User
{
    private int $merchantId;
    private User $user;
    private Business $business;

    public function __construct(int $id, User $user, Business $business)
    {
        parent::__construct(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getBirthDate(),
            $user->getPhoneNumber(),
            $user->getProfilePictureURL(),
            $user->getLastLoginDate(),
            $user->getPassword()
        );
        $this->merchantId = $id;
        $this->user = $user;
        $this->business = $business;
    }

    /**
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->merchantId;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getBusiness(): Business
    {
        return $this->business;
    }

    public function setBusiness(Business $company): void
    {
        $this->business = $company;
    }

}