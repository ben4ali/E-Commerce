<?php

namespace DAO;

require_once __DIR__ . "/../../../app/model/User.php";
require_once __DIR__ . "/../../../app/model/DAO/UserDAO.php";
require_once __DIR__ . "/../../../app/database/DBController.php";

use DateTime;
use model\DAO\UserDAO;
use model\User;
use PHPUnit\Framework\TestCase;

class UtilisateurDAOTest extends TestCase
{
    public function testCreateAndFindUserByEmail()
    {
        $userDAO = UserDAO::getInstance();

        // Create a test user
        $testUser = new User(
            null,
            'John',
            'Doe',
            'test@example.com',
            new DateTime('1990-01-01'),
            '1234567890',
            'images/test-profile.jpg',
            new DateTime(),
            'poule12345'
        );

        // Create the user
        $this->assertTrue($userDAO->create($testUser));

        // get id
        $testUser->setId($userDAO->getByEmail("test@example.com")->getId());

        // Retrieve the user by email
        $retrievedUser = $userDAO->getByEmail('test@example.com');
        $this->assertInstanceOf(User::class, $retrievedUser);

        // Clean up: Delete the test user from the database
        $this->assertTrue($userDAO->delete($retrievedUser));
    }

    public function testUpdateUser()
    {
        $userDAO = UserDAO::getInstance();

        // Create a test user
        $testUser = new User(
            null,
            'John',
            'Doe',
            'test@example.com',
            new DateTime('1990-01-01'),
            '1234567890',
            'images/test-profile.jpg',
            new DateTime(),
            'poule12345'
        );

        // Create the user
        $userDAO->create($testUser);

        // Update the user's information
        $testUser->setFirstName('UpdatedFirstName');
        $testUser->setLastName('UpdatedLastName');
        $testUser->setEmail('updated@example.com');

        // get id
        $testUser->setId($userDAO->getByEmail("test@example.com")->getId());

        // Update the user
        $this->assertTrue($userDAO->update($testUser));

        // Retrieve the updated user
        $retrievedUser = $userDAO->getByEmail('updated@example.com');
        $this->assertInstanceOf(User::class, $retrievedUser);

        // Clean up: Delete the updated user from the database
        $this->assertTrue($userDAO->delete($retrievedUser));
    }

    public function testDeleteUser()
    {
        $userDAO = UserDAO::getInstance();

        // Create a test user
        $testUser = new User(
            null,
            'John',
            'Doe',
            'test@example.com',
            new DateTime('1990-01-01'),
            '1234567890',
            'images/test-profile.jpg',
            new DateTime(),
            'poule12345'
        );

        // Create the user
        $userDAO->create($testUser);

        // Retrieve the user by email
        $retrievedUser = $userDAO->getByEmail('test@example.com');
        $this->assertInstanceOf(User::class, $retrievedUser);

        // Delete the user
        $this->assertTrue($userDAO->delete($retrievedUser));

        // Attempt to retrieve the deleted user (should return null)
        $retrievedUser = $userDAO->getByEmail('test@example.com');
        $this->assertNull($retrievedUser);
    }

    public function testVerifyPassword()
    {
        $userDAO = UserDAO::getInstance();

        // Create a test user
        $testUser = new User(
            null,
            'John',
            'Doe',
            'test@example.com',
            new DateTime('1990-01-01'),
            '1234567890',
            'images/test-profile.jpg',
            new DateTime(),
            'poule12345'
        );

        // Create the user
        $userDAO->create($testUser);

        // get id
        $testUser->setId($userDAO->getByEmail("test@example.com")->getId());

        // Verify the password (correct password)
        $this->assertTrue($userDAO->verifyPassword('test@example.com', 'poule12345'));

        // Verify the password (incorrect password)
        $this->assertFalse($userDAO->verifyPassword('test@example.com', 'wrong_password'));

        // Clean up: Delete the test user from the database
        $this->assertTrue($userDAO->delete($testUser));
    }


    public function testRetrieveHashedPassword()
    {
        $userDAO = UserDAO::getInstance();

        // Create a test user
        $testUser = new User(
            null,
            'John',
            'Doe',
            'test@example.com',
            new DateTime('1990-01-01'),
            '1234567890',
            'images/test-profile.jpg',
            new DateTime(),
            'poule12345'
        );

        // Create the user
        $userDAO->create($testUser);

        // get id
        $testUser->setId($userDAO->getByEmail("test@example.com")->getId());

        // Retrieve the hashed password
        $hashedPassword = $userDAO->retrieveHashedPassword('test@example.com');

        // Verify that the retrieved hashed password matches the expected value
        $this->assertTrue(password_verify('poule12345', $hashedPassword));

        // Clean up: Delete the test user from the database
        $this->assertTrue($userDAO->delete($testUser));
    }

}