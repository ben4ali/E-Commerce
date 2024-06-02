<?php

namespace model\DAO;

use database\DBController;
use DateTime;
use Exception;
use model\Item;
use model\Order;
use PDO;
use PDOException;
use PDOStatement;

require_once __DIR__ . '/../Order.php';
require_once __DIR__ . '/../Item.php';
require_once __DIR__ . '/../../database/DBController.php';
require_once 'DAOInterface.php';

class OrderDAO implements DAOInterface
{
    private static OrderDAO $instance;

    /**
     * Retourne l'instance OrderDAO.
     *
     * @return OrderDAO L'instance unique de la classe OrderDAO.
     */
    public static function getInstance(): OrderDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getOrdersCountLast60Days(): array
    {
        $db = DBController::getInstance()->getDB();
        $startDate = date('Y-m-d', strtotime('-59 days')); // Calculate the start date
        $endDate = date('Y-m-d'); // Current date is the end date

        // Construct and execute the SQL query
        $sql = "SELECT DATE(created_at) AS order_date, COUNT(*) AS order_count 
                FROM orders 
                WHERE created_at BETWEEN ? AND ?
                GROUP BY order_date 
                ORDER BY order_date";

        $stmt = $db->prepare($sql);
        $stmt->execute([$startDate, $endDate]);

        // Initialize an associative array to store the order counts
        $orderCounts = array();

        // Fetch the results and populate the array
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orderDate = $row['order_date'];
            $orderCount = $row['order_count'];
            $orderCounts[$orderDate] = $orderCount;
        }

        return $orderCounts;
    }

    public function getByBusinessId(int $businessId): array {
        $db = DBController::getInstance()->getDB();
        $orders = [];
        try {
            $stmt = $db->prepare("
                SELECT o.id AS order_id, o.user_id, o.delivery_address_id,
                    o.transaction_id, o.created_at, o.order_status, o.special_instruction
                FROM orders AS o
                JOIN order_items AS oi ON o.id = oi.order_id
                JOIN items AS i ON oi.item_id = i.id
                WHERE i.business_id = :businessId;
            ");
            $stmt->bindParam(':businessId', $businessId, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $orders[] = new Order(
                    $row['order_id'],
                    $row['user_id'],
                    $row['delivery_address_id'],
                    $row['transaction_id'],
                    $row['created_at'],
                    $row['order_status'],
                    $row['special_instruction']
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return $orders;
    }

    /**
     * Récupère un tableau d'objets Order en fonction de l'ID de l'utilisateur.
     *
     * @param int $userId L'ID de l'utilisateur pour lequel récupérer les commandes.
     *
     * @return array Un tableau d'objets Order ou null s'il n'y a pas de commandes correspondantes.
     */
    public function getByUserId(int $userId): array
    {
        $db = DBController::getInstance()->getDB();
        $orders = [];
        try {
            $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $order = new Order(
                    $row['id'],
                    $row['user_id'],
                    $row['delivery_address_id'],
                    $row['transaction_id'],
                    $row['created_at'],
                    $row['order_status'],
                    $row['special_instruction']
                );
                $orders[] = $order;
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return $orders;
    }

    /**
     * Lie les paramètres d'une requête préparée aux valeurs de l'objet Order.
     *
     * @param PDOStatement $PDOStatement La requête préparée à laquelle lier les paramètres.
     * @param Order $order L'objet Order contenant les valeurs à lier.
     */
    private function bindParam(PDOStatement $PDOStatement, Order $order): void
    {
        $user_id = $order->getUserId();
        $PDOStatement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $delivery_address_id = $order->getDeliveryAddressId();
        $PDOStatement->bindParam(':delivery_address_id', $delivery_address_id, PDO::PARAM_INT);
        $transaction_id = $order->getTransactionId();
        $PDOStatement->bindParam(':transaction_id', $transaction_id, PDO::PARAM_INT);
        $order_status = $order->getOrderStatus();
        $PDOStatement->bindParam(':order_status', $order_status);
        $special_instruction = $order->getSpecialInstruction();
        $PDOStatement->bindParam(':special_instruction', $special_instruction);
    }

    /**
     * Récupère un tableau d'objets Item associés à une commande en fonction de l'ID de la commande.
     *
     * @param int $orderId L'ID de la commande pour laquelle récupérer les articles associés.
     *
     * @return array Un tableau d'objets Item associés à la commande.
     */
    public function getItemsByOrderId(int $orderId): array
    {
        $db = DBController::getInstance()->getDB();
        $items = [];

        try {
            // Fetch all items associated with the given order ID
            $stmt = $db->prepare("SELECT items.* FROM items
                              INNER JOIN order_items ON items.id = order_items.item_id
                              WHERE order_items.order_id = :order_id");
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->execute();

            while ($itemData = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Create Item objects and add them to the $items array
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

            $stmt->closeCursor();
        } catch (PDOException|Exception $exception) {
            error_log("Database error: " . $exception->getMessage());
        }

        return $items;
    }

    /**
     * Récupère un objet Order en fonction de son ID.
     *
     * @param int $id L'ID de la commande à récupérer.
     *
     * @return Order|null L'objet Order correspondant à l'ID ou null s'il n'existe pas.
     */
    public function getById(int $id): ?Order
    {
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("SELECT * FROM orders WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stmt->closeCursor();
                return new Order(
                    $row['id'],
                    $row['user_id'],
                    $row['delivery_address_id'],
                    $row['transaction_id'],
                    $row['created_at'],
                    $row['order_status'],
                    $row['special_instruction']
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }


    /**
     * Crée un nouvel enregistrement de commande dans la base de données.
     *
     * @param Order $object L'objet Order à créer.
     *
     * @return bool True si la création a réussi, sinon false.
     */
    public function create($object): bool
    {
        if (!$object instanceof Order) return false;

        $order = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("INSERT INTO orders (user_id, delivery_address_id, transaction_id, order_status, special_instruction) 
            VALUES (:user_id, :delivery_address_id, :transaction_id, :order_status, :special_instruction)");
            $this->bindParam($stmt, $order);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }


    /**
     * Met à jour un enregistrement de commande dans la base de données.
     *
     * @param Order $object L'objet Order à mettre à jour.
     *
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function update($object): bool
    {
        if (!$object instanceof Order) return false;

        $order = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("UPDATE orders 
                             SET user_id = :user_id, 
                                 delivery_address_id = :delivery_address_id, 
                                 transaction_id = :transaction_id, 
                                 created_at = :created_at, 
                                 order_status = :order_status, 
                                 special_instruction = :special_instruction 
                             WHERE id = :id");
            $id = $order->getId();
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $this->bindParam($stmt, $order);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Supprime un enregistrement de commande de la base de données.
     *
     * @param Order $object L'objet Order à supprimer.
     *
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function delete($object): bool
    {
        if (!$object instanceof Order) return false;

        $order = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("DELETE FROM orders WHERE id = :id");
            $id = $order->getId();
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
