<?php

namespace model\DAO;

use database\DBController;
use DateTime;
use Exception;
use model\Review;
use PDO;
use PDOException;
use PDOStatement;

class ReviewDAO implements DAOInterface
{

    private static ReviewDAO $instance;

    /**
     * Récupère un objet Review en fonction de son ID.
     *
     * @param int $id L'ID de la review à récupérer.
     *
     * @return Review|null L'objet Review correspondant à l'ID ou null s'il n'existe pas.
     */
    public function getById(int $id): ?Review
    {
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("SELECT * FROM reviews WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stmt->closeCursor();
                return new Review(
                    $row['id'],
                    $row['user_id'],
                    $row['product_id'],
                    $row['comment'],
                    $row['number_stars'],
                    new DateTime($row['created_at'])
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Retourne l'instance ReviewDAO.
     *
     * @return ReviewDAO L'instance unique de la classe ReviewDAO.
     */
    public static function getInstance(): ReviewDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lie les paramètres d'une requête préparée aux valeurs de l'objet Review.
     *
     * @param PDOStatement $PDOStatement La requête préparée à laquelle lier les paramètres.
     * @param Review $review L'objet Review contenant les valeurs à lier.
     */
    private function bindParam(PDOStatement $PDOStatement, Review $review): void
    {
        $userId = $review->getUserId();
        $PDOStatement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $productId = $review->getProductId();
        $PDOStatement->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $comment = $review->getComment();
        $PDOStatement->bindParam(':comment', $comment);
        $numberStars = $review->getNumberStars();
        $PDOStatement->bindParam(':number_stars', $numberStars, PDO::PARAM_INT);
        $createdAt = $review->getCreatedAt();
        $PDOStatement->bindParam(':created_at', $createdAt);
    }

    public function getByUserId(int $userId): array
    {
        $db = DBController::getInstance()->getDB();
        $transactions = [];
        try {
            $stmt = $db->prepare("SELECT * FROM reviews WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $transaction = new Review(
                    $row['id'],
                    $row['user_id'],
                    $row['user_id'],
                    $row['comment'],
                    $row['number_stars'],
                    new DateTime($row['created_at'])
                );
                $transactions[] = $transaction;
            }
            $stmt->closeCursor();
        } catch (PDOException|Exception $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return $transactions;
    }
    public function getByItemId(int $product_Id): array
    {
        $db = DBController::getInstance()->getDB();
        $transactions = [];
        try {
            $stmt = $db->prepare("SELECT * FROM reviews WHERE product_id = :product_id"); // Remove the $ symbol
            $stmt->bindParam(':product_id', $product_Id, PDO::PARAM_INT); // Remove the $ symbol
            $stmt->execute();
      
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $transaction = new Review(
                    $row['id'],
                    $row['user_id'],
                    $row['product_id'], // Remove the $ symbol
                    $row['comment'],
                    $row['number_stars'],
                    new DateTime($row['created_at'])
                );
                $transactions[] = $transaction;
            }
            $stmt->closeCursor();
        } catch (PDOException|Exception $exception) {
            echo("Database error: " . $exception->getMessage());
            error_log("Database error: " . $exception->getMessage());
        }
        return $transactions;
    }

    /**
     * Crée un nouvel enregistrement de review dans la base de données.
     *
     * @param Review $object L'objet Review à créer.
     *
     * @return bool True si la création a réussi, sinon false.
     */
    public function create($object): bool
    {
        if (!$object instanceof Review) return false;

        $review = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, comment, number_stars, created_at) 
            VALUES (:user_id, :product_id, :comment, :number_stars, :created_at)");
            $this->bindParam($stmt, $review);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Met à jour un enregistrement de review dans la base de données.
     *
     * @param Review $object L'objet Review à mettre à jour.
     *
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function update($object): bool
    {
        if (!$object instanceof Review) return false;

        $review = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("UPDATE reviews 
                             SET user_id = :user_id, 
                                 product_id = :product_id, 
                                 comment = :comment, 
                                 number_stars = :number_stars, 
                                 created_at = :created_at 
                             WHERE id = :id");
            $id = $review->getId();
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $this->bindParam($stmt, $review);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Supprime un enregistrement de review de la base de données.
     *
     * @param Review $object L'objet Review à supprimer.
     *
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function delete($object): bool
    {
        if (!$object instanceof Review) return false;

        $review = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("DELETE FROM reviews WHERE id = :id");
            $id = $review->getId();
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
