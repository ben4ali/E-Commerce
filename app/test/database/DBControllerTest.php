<?php

namespace database;

require_once __DIR__ . "/../../database/DBController.php";

use PDO;
use PHPUnit\Framework\TestCase;

class DBControllerTest extends TestCase
{

    private DBController $dbController;

    public function test_GetInstance()
    {
        self::assertEquals($this->dbController, DBController::getInstance());
    }

    public function test_MySQLVersion()
    {
        self::assertEquals("5.7.11", DBController::getInstance()->getDb()->getAttribute(PDO::ATTR_SERVER_VERSION));
    }

    protected function setUp(): void
    {
        $this->dbController = DBController::getInstance();
    }
}
