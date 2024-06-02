<?php

namespace model\DAO;

use database\DBController;
use DateTime;
use Exception;
use model\Business;
use model\Item;
use PDO;
use PDOException;
use PDOStatement;

require_once __DIR__ . '/../../database/DBController.php';
require_once __DIR__ . '/DAOInterface.php';

class ItemDAO implements DAOInterface
{
    private static ItemDAO $instance;

    /**
     * Récupère une liste d'objets Item en fonction d'une catégorie donnée.
     *
     * @param string $categoryEntered La catégorie des objets à récupérer.
     *
     * @return Item[]|null Un tableau d'objets Item correspondant à la catégorie ou null s'il n'y en a pas.
     * @throws Exception
     */
    function getListCategory(string $categoryEntered): ?array
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM items WHERE category_id = :category_id");
            $stmt->bindParam(':category_id', $categoryEntered);
            $stmt->execute();
            $items = $this->itemDataToItems($stmt);
            $stmt->closeCursor();
            return $items;
        } catch (PDOException|Exception $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Obtient l'instance unique de la classe ItemDAO.
     *
     * @return ItemDAO L'instance unique de la classe ItemDAO.
     */
    public static function getInstance(): ItemDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lie les paramètres d'une requête préparée aux valeurs de l'objet Item.
     *
     * @param PDOStatement $PDOStatement La requête préparée à laquelle lier les paramètres.
     * @param Item $item L'objet Item contenant les valeurs à lier.
     */
    private function bindParam(PDOStatement $PDOStatement, Item $item): void
    {
        $merchantId = $item->getMerchantId();
        $PDOStatement->bindParam(':merchant_id', $merchantId, PDO::PARAM_INT);
        $businessId = $item->getBusinessId();
        $PDOStatement->bindParam(':business_id', $businessId, PDO::PARAM_INT);
        $categoryId = $item->getCategoryId();
        $PDOStatement->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $itemName = $item->getName();
        $PDOStatement->bindParam(':item_name', $itemName);
        $description = $item->getDescription();
        $PDOStatement->bindParam(':description', $description);
        $price = $item->getPrice();
        $PDOStatement->bindParam(':price', $price);
        $stockQuantity = $item->getQuantity();
        $PDOStatement->bindParam(':stock_quantity', $stockQuantity, PDO::PARAM_INT);
        $imageUrl = $item->getImageUrl();
        $PDOStatement->bindParam(':image_url', $imageUrl);
        $html_data = $item->getHTMLData();
        $PDOStatement->bindParam(':html_data', $html_data);
        $upc = $item->getUpc();
        $PDOStatement->bindParam(':upc', $upc);
    }

    /**
     * Transforme les données d'item en objets Item.
     *
     * @param PDOStatement $PDOStatement Le jeu de résultats PDOStatement contenant les données d'item.
     *
     * @return Item[]|null Un tableau d'objets Item correspondant aux données d'item ou null s'il n'y en a pas.
     * @throws Exception
     */
    private function itemDataToItems(PDOStatement $PDOStatement): ?array
    {
        $items = null;
        foreach ($PDOStatement as $itemData) {
            $items[] = new Item(
                $itemData['id'],
                $itemData['business_id'],
                $itemData['item_name'],
                $itemData['description'],
                $itemData['price'],
                $itemData['stock_quantity'],
                $itemData['upc'],
                $itemData['category_id'],
                $itemData['image_url'],
                $itemData['html_data'],
                $itemData['merchant_id'],
                new DateTime($itemData['register_date'])
            );
        }
        return $items;
    }

    /**
     * Récupère un objet Item en fonction de son ID.
     *
     * @param int $id L'ID de l'objet Item à récupérer.
     *
     * @return Item|null L'objet Item correspondant à l'ID ou null s'il n'existe pas.
     */
    function getById(int $id): ?Item
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM items WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $itemData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if ($itemData) {
                return new Item(
                    $itemData['id'],
                    $itemData['business_id'],
                    $itemData['item_name'],
                    $itemData['description'],
                    $itemData['price'],
                    $itemData['stock_quantity'],
                    $itemData['upc'],
                    $itemData['category_id'],
                    $itemData['image_url'],
                    $itemData['html_data'],
                    $itemData['merchant_id'],
                    new DateTime($itemData['register_date'])
                );
            }
        } catch (PDOException|Exception $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Récupère un objet Item en fonction d'un objet Business.
     *
     * @param Business $business L'objet Business associé aux objets Item à récupérer.
     *
     * @return Item[]|null L'objet Item correspondant à l'objet Business ou null s'il n'existe pas.
     */
    function getByBusiness(Business $business): ?array
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM items WHERE business_id = :business_id");
            $business_id = $business->getId();
            $stmt->bindParam(':business_id', $business_id, PDO::PARAM_INT);
            $stmt->execute();
            $itemData = $stmt->fetchAll(PDO::FETCH_CLASS);
            $stmt->closeCursor();

            $items = [];
            foreach ($itemData as $data) {
                $items[] = new Item(
                    $data->id,
                    $data->business_id,
                    $data->item_name,
                    $data->description,
                    $data->price,
                    $data->stock_quantity,
                    $data->upc,
                    $data->category_id,
                    $data->image_url,
                    $data->html_data,
                    $data->merchant_id,
                    new DateTime($data->register_date)
                );
            }
            return $items;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        } catch (Exception $exception) {
            error_log("DateTime error: " . $exception->getMessage());
        }
        return null;
    }


    /**
     * Récupère un objet Item en fonction de l'ID de l'entreprise (business).
     *
     * @param int $businessId L'ID de l'entreprise (business) associée aux objets Item à récupérer.
     *
     * @return Item|null L'objet Item correspondant à l'ID de l'entreprise (business) ou null s'il n'existe pas.
     */
    function getByBusinessId(int $businessId): ?Item
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM items WHERE business_id = :business_id");
            $stmt->bindParam(':business_id', $business_id, PDO::PARAM_INT);
            $stmt->execute();
            $itemData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return new Item(
                $itemData['id'],
                $itemData['business_id'],
                $itemData['item_name'],
                $itemData['description'],
                $itemData['price'],
                $itemData['stock_quantity'],
                $itemData['upc'],
                $itemData['category_id'],
                $itemData['image_url'],
                $itemData['html_data'],
                $itemData['merchant_id'],
                new DateTime($itemData['register_date'])
            );
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Recherche des objets Item en fonction d'une requête de recherche utilisateur.
     *
     * @param string $userQuery La requête de recherche utilisateur.
     *
     * @return Item[]|null Un tableau d'objets Item correspondant à la requête de recherche ou null s'il n'y en a pas.
     */
    function search(string $userQuery): ?array
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM items WHERE item_name LIKE :userQuery OR description LIKE :userQuery");
            $userQuery = '%' . $userQuery . '%';
            $stmt->bindValue(':userQuery', $userQuery);
            $stmt->execute();
            $items = $this->itemDataToItems($stmt);
            $stmt->closeCursor();
            return $items;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }
    /**
     * Recherche une objet Item aléatoire.
     *
     *
     * @return Item|null Un item aléatoire.
     */
    function getRandomItem(): ?Item
    {
    $db = DBController::getInstance()->getDB();
    try {
        // Select a random item from the 'items' table
        $stmt = $db->prepare("SELECT * FROM items ORDER BY RAND() LIMIT 1");
        $stmt->execute();
        $itemData = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if ($itemData) {
            return new Item(
                $itemData['id'],
                $itemData['business_id'],
                $itemData['item_name'],
                $itemData['description'],
                $itemData['price'],
                $itemData['stock_quantity'],
                $itemData['upc'],
                $itemData['category_id'],
                $itemData['image_url'],
                $itemData['html_data'],
                $itemData['merchant_id'],
                new DateTime($itemData['register_date'])
            );
        }
    } catch (PDOException $exception) {
        error_log("Database error: " . $exception->getMessage());
    }
    return null;
}

    /**
     * Crée un nouvel enregistrement d'objet Item dans la base de données.
     *
     * @param Item $object L'objet Item à créer.
     *
     * @return bool True si la création a réussi, sinon false.
     */
    function create($object): bool
    {
        if (!$object instanceof Item) return false;
        $item = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("INSERT INTO items (merchant_id, business_id, category_id, item_name, description, price, stock_quantity, image_url, html_data, upc) 
                    VALUES (:merchant_id, :business_id, :category_id, :item_name, :description, :price, :stock_quantity, :image_url, :html_data, :upc)");
            $this->bindParam($stmt, $item);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            session_start();
            $_SESSION['error'] = $exception->getMessage();
        }
        return false;
    }

    /**
     * Supprime un enregistrement d'objet Item de la base de données.
     *
     * @param Item $object L'objet Item à supprimer.
     *
     * @return bool True si la suppression a réussi, sinon false.
     */
    function delete($object): bool
    {
        if (!$object instanceof Item) return false;
        $item = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("DELETE FROM items WHERE id = :id");
            $id = $item->getId();
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
     * Met à jour la quantité d'un objet Item dans la base de données.
     *
     * @param Item $item L'objet Item à mettre à jour.
     * @param int $amount La quantité à ajouter ou soustraire.
     *
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    function updateQuantity(Item $item, int $amount): bool
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("UPDATE items SET stock_quantity = :quantity WHERE id = :id");
            $id = $item->getId();
            $stmt->bindParam(':id', $id);
            $quantity = $item->getQuantity() + $amount;
            $stmt->bindParam(':quantity', $quantity);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Met à jour un enregistrement d'objet Item dans la base de données.
     *
     * @param Item $object L'objet Item à mettre à jour.
     *
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function update($object): bool
    {
        if (!$object instanceof Item) return false;

        $item = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("UPDATE items SET merchant_id = :merchant_id, business_id = :business_id, category_id = :category_id, 
                         item_name = :item_name, description = :description, price = :price, stock_quantity = :stock_quantity, image_url = :image_url, 
                         html_data = :html_data, upc = :upc WHERE id = :id");
            $this->bindParam($stmt, $item);
            $id = $item->getId();
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