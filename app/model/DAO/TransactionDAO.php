<?php

namespace model\DAO;

use database\DBController;
use model\Transaction;
use PDO;
use PDOException;
use PDOStatement;

class TransactionDAO implements DAOInterface
{

    private static TransactionDAO $instance;

    /**
     * Récupère un objet Transaction en fonction de son ID.
     *
     * @param int $id L'ID de la transaction à récupérer.
     *
     * @return Transaction|null L'objet Transaction correspondant à l'ID ou null s'il n'existe pas.
     */
    public function getById(int $id): ?Transaction
    {
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("SELECT * FROM transactions WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new Transaction(
                    $row['id'],
                    $row['user_id'],
                    $row['billing_address'],
                    $row['shipping_address'],
                    $row['payment_method'],
                    $row['total']
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Retourne l'instance TransactionDAO.
     *
     * @return TransactionDAO L'instance unique de la classe TransactionDAO.
     */
    public static function getInstance(): TransactionDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getByBusinessId(int $businessId): array {
        $db = DBController::getInstance()->getDB();
        $transactions = [];
        try {
            $stmt = $db->prepare("
            SELECT t.id AS transaction_id, t.user_id, t.billing_address AS billing_address_id,
                t.shipping_address AS shipping_address_id, t.payment_method, t.total
            FROM transactions AS t
            JOIN orders AS o ON t.id = o.transaction_id
            JOIN order_items AS oi ON o.id = oi.order_id
            JOIN items AS i ON oi.item_id = i.id
            WHERE i.business_id = :businessId;
            ");
            $stmt->bindParam(':businessId', $businessId, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $transactions[] = new Transaction(
                    $row['transaction_id'],
                    $row['user_id'],
                    $row['billing_address_id'],
                    $row['shipping_address_id'],
                    $row['payment_method'],
                    $row['total']
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return $transactions;
    }


    /**
     * Lie les paramètres d'une requête préparée aux valeurs de l'objet Transaction.
     *
     * @param PDOStatement $PDOStatement La requête préparée à laquelle lier les paramètres.
     * @param Transaction $transaction L'objet Transaction contenant les valeurs à lier.
     */
    private function bindParam(PDOStatement $PDOStatement, Transaction $transaction): void
    {
        $user_id = $transaction->getUserId();
        $PDOStatement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $billing_address = $transaction->getBillingAddress();
        $PDOStatement->bindParam(':billing_address', $billing_address, PDO::PARAM_INT);
        $shipping_address = $transaction->getShippingAddress();
        $PDOStatement->bindParam(':shipping_address', $shipping_address, PDO::PARAM_INT);
        $payment_method = $transaction->getPaymentMethod();
        $PDOStatement->bindParam(':payment_method', $payment_method);
        $total = $transaction->getTotal();
        $PDOStatement->bindParam(':total', $total);
    }

    /**
     * Récupère un tableau d'objets Transaction en fonction de l'ID de l'utilisateur.
     *
     * @param int $userId L'ID de l'utilisateur pour lequel récupérer les transactions.
     *
     * @return array Un tableau d'objets Transaction ou null s'il n'y a pas de transactions correspondantes.
     */
    public function getByUserId(int $userId): array
    {
        $db = DBController::getInstance()->getDB();
        $transactions = [];
        try {
            $stmt = $db->prepare("SELECT * FROM transactions WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $transaction = new Transaction(
                    $row['id'],
                    $row['user_id'],
                    $row['billing_address'],
                    $row['shipping_address'],
                    $row['payment_method'],
                    $row['total']
                );
                $transactions[] = $transaction;
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return $transactions;
    }

    /**
     * Crée un nouvel enregistrement de transaction dans la base de données.
     *
     * @param Transaction $object L'objet Transaction à créer.
     *
     * @return bool True si la création a réussi, sinon false.
     */
    public function create($object): bool
    {
        if (!$object instanceof Transaction) return false;

        $transaction = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("INSERT INTO transactions (user_id, billing_address, shipping_address, payment_method, total) 
            VALUES (:user_id, :billing_address, :shipping_address, :payment_method, :total)");
            $this->bindParam($stmt, $transaction);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Met à jour un enregistrement de transaction dans la base de données.
     *
     * @param Transaction $object L'objet Transaction à mettre à jour.
     *
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function update($object): bool
    {
        if (!$object instanceof Transaction) return false;

        $transaction = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("UPDATE transactions 
                             SET user_id = :user_id, 
                                 billing_address = :billing_address, 
                                 shipping_address = :shipping_address, 
                                 payment_method = :payment_method, 
                                 total = :total 
                             WHERE id = :id");
            $id = $transaction->getId();
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $this->bindParam($stmt, $transaction);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Supprime un enregistrement de transaction de la base de données.
     *
     * @param Transaction $object L'objet Transaction à supprimer.
     *
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function delete($object): bool
    {
        if (!$object instanceof Transaction) return false;

        $transaction = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("DELETE FROM transactions WHERE id = :id");
            $id = $transaction->getId();
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
