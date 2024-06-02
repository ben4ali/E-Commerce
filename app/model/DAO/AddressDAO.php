<?php

namespace model\DAO;

use database\DBController;
use model\Address;
use PDO;
use PDOException;
use PDOStatement;

require_once __DIR__ . '/../Address.php';
require_once __DIR__ . '/../../database/DBController.php';
require_once 'DAOInterface.php';

class AddressDAO implements DAOInterface
{
    private static AddressDAO $instance;

    /**
     * Récupère une adresse par son ID.
     *
     * @param int $id L'ID de l'adresse à récupérer.
     * @return Address|null L'objet Address correspondant à l'ID, ou null s'il n'existe pas.
     */
    public function getById(int $id): ?Address
    {
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("SELECT * FROM addresses WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stmt->closeCursor();
                return new Address(
                    $row['id'],
                    $row['street'],
                    $row['city'],
                    $row['province'],
                    $row['country'],
                    $row['postal_code']
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Retourne l'instance AddressDAO.
     *
     * @return AddressDAO L'instance unique de la classe AddressDAO.
     */
    public static function getInstance(): AddressDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lie les paramètres de l'objet Address à une requête PDOStatement.
     *
     * @param PDOStatement $PDOStatement L'objet PDOStatement à lier.
     * @param Address $address L'objet Address contenant les données à lier.
     * @return void
     */
    private function bindParam(PDOStatement $PDOStatement, Address $address): void
    {
        $street = $address->getStreet();
        $PDOStatement->bindParam(':street', $street);
        $city = $address->getCity();
        $PDOStatement->bindParam(':city', $city);
        $province = $address->getProvince();
        $PDOStatement->bindParam(':province', $province);
        $country = $address->getCountry();
        $PDOStatement->bindParam(':country', $country);
        $postalCode = $address->getPostalCode();
        $PDOStatement->bindParam(':postal_code', $postalCode);
    }

    /**
     * @param string $street
     * @param string $city
     * @param string $province
     * @param string $country
     * @param string $postal_code
     * @return Address|null
     */
    public function getExact(string $street, string $city, string $province, string $country, string $postal_code): ?Address {
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("SELECT * FROM addresses WHERE street = :street AND city = :city AND province = :province
                                        AND country = :country AND postal_code = :postal_code");
            $stmt->bindParam(':street', $street);
            $stmt->bindParam(':city',  $city);
            $stmt->bindParam(':province', $province);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':postal_code', $postal_code);
            $stmt->execute();
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stmt->closeCursor();
                return new Address(
                    $row['id'],
                    $row['street'],
                    $row['city'],
                    $row['province'],
                    $row['country'],
                    $row['postal_code']
                );
            }
            $stmt->closeCursor();
        } catch (PDOException $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Crée une nouvelle adresse dans la base de données.
     *
     * @param mixed $object L'objet Address à créer.
     * @return bool true si la création réussit, sinon false.
     */
    public function create($object): bool
    {
        if (!$object instanceof Address) return false;

        $address = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("INSERT INTO addresses (street, city, province, country, postal_code) 
            VALUES (:street, :city, :province, :country, :postal_code)");
            $this->bindParam($stmt, $address);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Met à jour une adresse existante dans la base de données.
     *
     * @param mixed $object L'objet Address à mettre à jour.
     * @return bool true si la mise à jour réussit, sinon false.
     */
    public function update($object): bool
    {
        if (!$object instanceof Address) return false;

        $address = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("UPDATE addresses 
                             SET street = :street, 
                                 city = :city, 
                                 province = :province, 
                                 country = :country, 
                                 postal_code = :postal_code 
                             WHERE id = :id");
            $id = $address->getId();
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $this->bindParam($stmt, $address);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Supprime une adresse existante de la base de données.
     *
     * @param mixed $object L'objet Address à supprimer.
     * @return bool true si la suppression réussit, sinon false.
     */
    public function delete($object): bool
    {
        if (!$object instanceof Address) return false;

        $address = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("DELETE FROM addresses WHERE id = :id");
            $id = $address->getId();
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return false;
    }
}
