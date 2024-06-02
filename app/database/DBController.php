<?php

namespace database;

use PDO;
use PDOException;

class DBController
{
    private static self $instance;
    private string $configPath = __DIR__ . '/../' . 'config.json';
    private PDO $db;

    private function __construct()
    {
        if (file_exists($this->configPath)) {
            $configContent = file_get_contents($this->configPath);
            $config = json_decode($configContent, true);
            try {
                $this->db = new PDO(
                    "mysql:host=" . $config['db_info']['address'] . ";dbname=" . $config['db_info']['database'],
                    $config['db_info']['username'],
                    $config['db_info']['password']
                );
            } catch (PDOException $exception) {
                echo $exception->getMessage();
            }
        } else {
            die("Config file not found!");
        }
    }

    public static function getInstance(): DBController
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDB(): PDO
    {
        return $this->db;
    }

    public function sendQuery(string $query, array $fields, array $values): array
    {
        $db = $this->getDB();
        $result = [];

        try {
            $stmt = $db->prepare($query);

            // Bind parameters
            foreach ($fields as $index => $field) {
                $stmt->bindParam($field, $values[$index]);
            }

            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }

            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            // Optionally, handle the exception as needed
        }

        return $result;
    }
}