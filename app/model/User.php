<?php

namespace model;

use DateTime;
use Exception;

/**
 * Auteur : Antoine Langevin
 * Dernière modification : 2023-11-06
 */
class User
{
    protected ?int $id = null;
    protected string $firstName;
    protected string $lastName;
    protected string $email;
    protected DateTime $birthDate;
    protected string $phoneNumber;
    protected string $profilePictureURL;
    protected DateTime $lastLoginDate;
    protected string $password; // hashed
    protected int $warnings;
    protected string $role = 'user';

    /**
     * @param ?int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param DateTime $birthDate
     * @param string $phoneNumber
     * @param string $profilePictureURL
     * @param DateTime $lastLoginDate
     * @param string $password ;
     * @param string $role
     */
    public function __construct(?int $id, string $firstName, string $lastName, string $email, DateTime $birthDate, string $phoneNumber, string $profilePictureURL, DateTime $lastLoginDate, string $password, string $role = 'user')
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->birthDate = $birthDate;
        $this->phoneNumber = $phoneNumber;
        $this->profilePictureURL = $profilePictureURL;
        $this->lastLoginDate = $lastLoginDate;
        $this->password = $password;
        $this->warnings = 0;
        $this->role = $role;
    }

    /**
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return DateTime
     */
    public function getLastLoginDate(): DateTime
    {
        return $this->lastLoginDate;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
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
     * Hashed
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return DateTime
     */
    public function getBirthDate(): DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param DateTime $birthDate
     */
    public function setBirthDate(DateTime $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getProfilePictureURL(): string
    {
        return $this->profilePictureURL;
    }

    /**
     * @param string $profilePictureURL
     */
    public function setProfilePictureURL(string $profilePictureURL): void
    {
        $this->profilePictureURL = $profilePictureURL;
    }

    /**
     * @return int
     */
    public function getWarnings(): int
    {
        return $this->warnings;
    }

    /**
     * @param int $warnings
     */
    public function setWarnings(int $warnings): void
    {
        $this->warnings = $warnings;
    }

    /**
     * @return void
     */
    public function resetLastLoginDate(): void
    {
        try {
            $this->lastLoginDate = new DateTime(date('Y-m-d'));
        } catch (Exception $e) {
            // there was an error parsing date() to DateTime.

        }
    }
}