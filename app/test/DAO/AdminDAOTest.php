<?php

use model\DAO\AdminDAO;
use model\Item;
use model\User;
use PHPUnit\Framework\TestCase;

class AdminDAOTest extends TestCase
{
    protected $adminDAO;

    public function testFindUserByName()
    {
        $expectedUser = new User(
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
        $this->adminDAO->createUser($expectedUser);
        // Assuming a user with name 'John Doe' exists in the database
        $retrievedUser = $this->adminDAO->getByFirstName('John');

        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals($expectedUser, $retrievedUser);
        $this->adminDAO->deleteUser($expectedUser);
    }

    public function testFindUserById()
    {
        $expectedUser = new User(
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

        $this->adminDAO->createUser($expectedUser);

        // Assuming a user with id '1' exists in the database
        $retrievedUser = $this->adminDAO->getUserById(1);

        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals($expectedUser, $retrievedUser);
        $this->adminDAO->deleteUser($expectedUser);
    }

    public function testFindAllUsers()
    {
        $expectedUsers = [new User(
            null,
            'John',
            'Doe',
            'test@example.com',
            new DateTime('1990-01-01'),
            '1234567890',
            'images/test-profile.jpg',
            new DateTime(),
            'poule12345'
        ), new User(
            null,
            'Ad',
            'Min',
            'test2@example.com',
            new DateTime('1990-04-05'),
            '0987654321',
            'images/test2-profile.jpg',
            new DateTime(),
            'cochcon12345'
        )
        ];
        foreach ($expectedUsers as $expectedUser) {
            $this->adminDAO->createUser($expectedUser);
        }
        $retrievedUsers = $this->adminDAO->getAllUsers();

        $this->assertIsArray($retrievedUsers);
        $this->assertEquals($expectedUsers, $retrievedUsers);
        foreach ($expectedUsers as $expectedUser) {
            $this->adminDAO->deleteUser($expectedUser);
        }
    }

    public function testFindItemByName()
    {
        $expectedItem = new Item(
            1,
            null,
            "Smartphone",
            "Latest model",
            599.99,
            50,
            "1234567890",
            "https://image-link1.com"
        );

        $this->adminDAO->createItem($expectedItem);
        $retrievedItem = $this->adminDAO->getItemByName();
        $this->assertEquals($expectedItem, $retrievedItem);
        $this->adminDAO->deleteItem($expectedItem);
    }

    public function testFindItemById()
    {
        $expectedItem = new Item(
            1,
            null,
            "Smartphone",
            "Latest model",
            599.99,
            50,
            "1234567890",
            "https://image-link1.com"
        );

        $this->adminDAO->createItem($expectedItem);
        $retrievedItem = $this->adminDAO->getItemById();
        $this->assertEquals($expectedItem, $retrievedItem);
        $this->adminDAO->deleteItem($expectedItem);
    }

    public function testGetRandomItem()
    {
        $expectedItem = new Item(
            1,
            null,
            "Smartphone",
            "Latest model",
            599.99,
            50,
            "1234567890",
            "https://image-link1.com"
        );

        $this->adminDAO->createItem($expectedItem);
        $retrievedItem = $this->adminDAO->getRandomItem();
        $this->assertInstanceOf(Item::class, $retrievedItem);
        $this->adminDAO->deleteItem($expectedItem);
    }

    public function testFindAllItems()
    {
        $expectedItems = [new Item(
            1,
            null,
            "Smartphone",
            "Latest model",
            599.99,
            50,
            "1234567890",
            "https://image-link1.com"
        ), new Item(
            2,
            null,
            "Tablet",
            "Oldest model",
            159.99,
            100,
            "129510259",
            "https://image-link2.com"
        )
        ];
        foreach ($expectedItems as $expectedItem) {
            $this->adminDAO->createItem($expectedItem);
        }
        $retrievedItems = $this->adminDAO->getAllItems();

        $this->assertIsArray($retrievedItems);
        $this->assertEquals($expectedItems, $retrievedItems);
        foreach ($expectedItems as $expectedItem) {
            $this->adminDAO->deleteItem($expectedItem);
        }

    }

    public function testDeleteUser()
    {
        $user = new User(
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

        $this->adminDAO->createUser($user);
        $result = $this->adminDAO->deleteUser($user);
        $this->assertTrue($result);
    }

    public function testDeleteItem()
    {
        $item = new Item(
            1,
            null,
            "Smartphone",
            "Latest model",
            599.99,
            50,
            "1234567890",
            "https://image-link1.com"
        );

        $this->adminDAO->createItem($item);
        $result = $this->adminDAO->deleteItem($item);
        $this->assertTrue($result);
    }

    protected function setUp(): void
    {
        $this->adminDAO = AdminDAO::getInstance();
        // You may want to set up a test database connection here
    }
}
