<?php

namespace model\DAO;

use database\DBController;
use model\Business;
use PDO;
use PDOException;
use PDOStatement;
use Exception;

require_once __DIR__ . '/../../database/DBController.php';
require_once __DIR__ . '/DAOInterface.php';
require_once __DIR__ . '/../../model/Business.php';
require_once __DIR__ . '/../../model/DAO/BusinessDAO.php';

class BusinessDAO implements DAOInterface
{
    private static BusinessDAO $instance;

    /**
     * Récupère un objet Business à partir de son ID.
     *
     * @param int $id L'ID de l'objet Business à récupérer.
     *
     * @return Business|null L'objet Business correspondant à l'ID ou null s'il n'existe pas.
     */
    function getById(int $id): ?Business
    {
        return $this->getByParam('id', $id);
    }

    /**
     * Récupère un objet Business en fonction d'un champ et d'une valeur donnés.
     *
     * @param string $field Le nom du champ sur lequel baser la recherche.
     * @param string $value La valeur à rechercher dans le champ.
     *
     * @return Business|null L'objet Business correspondant aux critères de recherche ou null s'il n'existe pas.
     */
    private function getByParam(string $field, string $value): ?Business
    {
        $db = DBController::getInstance()->getDB();
        try {
            // Préparation de la requête SQL.
            $stmt = $db->prepare("SELECT * FROM businesses WHERE $field = :value;");
            $stmt->bindParam(':value', $value);
            // Execute & ferme la requête SQL.
            $stmt->execute();
            $businessData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if ($businessData) {
                return new Business(
                    $businessData['id'],
                    $businessData['owner_id'],
                    $businessData['address_id'],
                    $businessData['bs_name'],
                    $businessData['email'],
                    $businessData['url'],
                    $businessData['bs_type'],
                    $businessData['NE'],
                    $businessData['description']);
            }
        } catch (PDOException|Exception $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Obtient l'instance unique de la classe BusinessDAO.
     *
     * @return BusinessDAO L'instance unique de la classe BusinessDAO.
     */
    public static function getInstance(): BusinessDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lie les paramètres d'une requête préparée aux valeurs de l'objet Business.
     *
     * @param PDOStatement $PDOStatement La requête préparée à laquelle lier les paramètres.
     * @param Business $business L'objet Business contenant les valeurs à lier.
     */
    private function bindParam(PDOStatement $PDOStatement, Business $business): void
    {
        $ownerId = $business->getOwnerId();
        $PDOStatement->bindParam(':owner_id', $ownerId, PDO::PARAM_INT);
        $addressId = $business->getAddressId();
        $PDOStatement->bindParam(':address_id', $addressId, PDO::PARAM_INT);
        $bsName = $business->getName();
        $PDOStatement->bindParam(':bs_name', $bsName);
        $email = $business->getEmail();
        $PDOStatement->bindParam(':email', $email);
        $url = $business->getWebsiteURL();
        $PDOStatement->bindParam(':url', $url);
        $bsType = $business->getType();
        $PDOStatement->bindParam('bs_type', $bsType);
        $ne = $business->getNe();
        $PDOStatement->bindParam(':ne', $ne);
        $description = $business->getDescription();
        $PDOStatement->bindParam(':description', $description);
    }

    /**
     * Récupère un objet Business en fonction de son nom.
     *
     * @param string $name Le nom de l'objet Business à rechercher.
     *
     * @return Business|null L'objet Business correspondant au nom ou null s'il n'existe pas.
     */
    function getByName(string $name): ?Business
    {
        return $this->getByParam('bs_name', $name);
    }

    /**
     * Récupère un objet Business en fonction du numéro d'entreprise (NE).
     *
     * @param string $ne Le numéro d'entreprise (NE) de l'objet Business à rechercher.
     *
     * @return Business|null L'objet Business correspondant au numéro d'entreprise (NE) ou null s'il n'existe pas.
     */
    function getByNE(string $ne): ?Business
    {
        return $this->getByParam('NE', $ne);
    }

    /**
     * Récupère un objet Business en fonction de l'ID du propriétaire (merchant).
     *
     * @param int $id L'ID du propriétaire (merchant) de l'objet Business à rechercher.
     *
     * @return Business|null L'objet Business correspondant à l'ID du propriétaire (merchant) ou null s'il n'existe pas.
     */
    function getByMerchantId(int $id): ?Business
    {
        return $this->getByParam('owner_id', $id);
    }

    /**
     * Crée un nouvel enregistrement d'objet Business dans la base de données.
     *
     * @param Business $object L'objet Business à créer.
     *
     * @return bool True si la création a réussi, sinon false.
     */
    function create($object): bool
    {
        if (!$object instanceof Business) return false;

        $business = $object;
        $db = DBController::getInstance()->getDB();

        try {
            // Préparation de la requête SQL
            $stmt = $db->prepare("INSERT INTO businesses (owner_id, address_id, bs_name, email, url, bs_type, ne, description) 
                VALUES (:owner_id, :address_id, :bs_name, :email, :url, :bs_type, :ne, :description)");
            $this->bindParam($stmt, $business);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Supprime un enregistrement d'objet Business de la base de données.
     *
     * @param Business $object L'objet Business à supprimer.
     *
     * @return bool True si la suppression a réussi, sinon false.
     */
    function delete($object): bool
    {
        if (!$object instanceof Business) return false;

        $business = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("DELETE FROM businesses WHERE id = :id");
            $id = $business->getId();
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
     * Met à jour un enregistrement d'objet Business dans la base de données.
     *
     * @param Business $object L'objet Business à mettre à jour.
     *
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    function update($object): bool
    {
        if (!$object instanceof Business) return false;

        $business = $object;
        $db = DBController::getInstance()->getDB();

        try {
            // Préparation de la requête SQL pour mettre à jour l'utilisateur
            $stmt = $db->prepare("UPDATE businesses SET owner_id = :owner_id, address_id = :address_id, bs_name = :bs_name, 
                 email = :email, url = :url, bs_type = :bs_type, NE = :ne, description = :description WHERE id = :id");
            $this->bindParam($stmt, $business);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }
}