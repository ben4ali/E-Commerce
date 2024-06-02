<?php

namespace model\DAO;

use database\DBController;
use model\Category;
use PDO;
use PDOException;

require_once __DIR__ . '/../Category.php';
require_once __DIR__ . '/../../database/DBController.php';
require_once 'DAOInterface.php';

class CategoryDAO implements DAOInterface
{

    private static CategoryDAO $instance;

    /**
     * Retourne l'instance CategoryDAO.
     *
     * @return CategoryDAO L'instance unique de la classe CategoryDAO.
     */
    public static function getInstance(): CategoryDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retrieves a Category object based on its ID.
     *
     * @param int $id The ID of the category to retrieve.
     * @return Category|null The Category object if found, null otherwise.
     */
    public function getById(int $id): ?Category
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stmt->closeCursor();
                return new Category(
                    $row['id'],
                    $row['cat_name']
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Retrieves a Category object based on its name.
     *
     * @param string $name The name of the category to retrieve.
     * @return Category|null The Category object if found, null otherwise.
     */
    public function getByName(string $name): ?Category
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM categories WHERE cat_name = :cat_name");
            $stmt->bindParam(':cat_name', $name, PDO::PARAM_STR);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stmt->closeCursor();
                return new Category(
                    $row['id'],
                    $row['cat_name']
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Creates a new category record in the database.
     *
     * @param Category $object The Category object to create.
     * @return bool True if the creation was successful, false otherwise.
     */
    public function create($object): bool
    {
        if (!$object instanceof Category) return false;

        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("INSERT INTO categories (cat_name) VALUES (:cat_name)");
            $cat_name = $object->getName();
            $stmt->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Updates a category record in the database.
     *
     * @param Category $object The Category object to update.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update($object): bool
    {
        if (!$object instanceof Category) return false;

        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("UPDATE categories SET cat_name = :cat_name WHERE id = :id");
            $cat_name = $object->getName();
            $id = $object->getId();
            $stmt->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Deletes a category record from the database.
     *
     * @param Category $object The Category object to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete($object): bool
    {
        if (!$object instanceof Category) return false;

        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("DELETE FROM categories WHERE id = :id");
            $id = $object->getId();
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }
}
