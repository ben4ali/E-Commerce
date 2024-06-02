<?php

namespace model\DAO;

use database\DBController;
use model\Merchant;
use PDO;
use PDOException;
use PDOStatement;
use Exception;
use function error_log;

require_once __DIR__ . '/../../database/DBController.php';
require_once __DIR__ . '/DAOInterface.php';
require_once __DIR__ . '/../../model/Merchant.php';
require_once __DIR__ . '/../../model/DAO/UserDAO.php';
require_once __DIR__ . '/../../model/DAO/BusinessDAO.php';

class MerchantDAO implements DAOInterface
{
    private static MerchantDAO $instance;

    /**
     * Obtient l'instance unique de la classe MerchantDAO.
     *
     * @return MerchantDAO L'instance unique de la classe MerchantDAO.
     */
    public static function getInstance(): MerchantDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return array|null
     */
    function getAll(): null|array {
        $db = DBController::getInstance()->getDB();
        $merchants = [];
        try {
            // Initialisation des variables de bases.
            $error_msg = "Erreur lors de l'otention des données relatives au(x) marchant(s): ";
            $merchantsErr = [];
            // Obtention des données relatives aux merchants.
            $stmt = $db->prepare("SELECT id,user_id,business_id,phone,email FROM merchants;");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = UserDAO::getInstance()->getById($row['user_id']);
                $business = BusinessDAO::getInstance()->getByMerchantId($row['id']);
                if($user != null && $business != null) {
                    $merchants[] = new Merchant($row['id'], $user, $business);
                } else $merchantsErr[] = $row['user_id'];
            }
            $stmt->closeCursor();

            // Vérification des erreurs, si oui, création d'une exception d'avertissement.
            if(!empty($merchantsErr)) {
                foreach ($merchantsErr as $merchantId) $error_msg .= $merchantId . ' ';
                throw new Exception($error_msg);
            }

        } catch (PDOException|Exception $exception) {
            $_SESSION['message'] = $exception->getMessage();
            error_log("Database error: " . $exception->getMessage());
        }
        return $merchants;
    }

    /**
     * Vérifie si le mot de passe correspond à l'email d'un marchand.
     *
     * @param string $email L'email du marchand.
     * @param string $password Le mot de passe à vérifier.
     *
     * @return bool True si le mot de passe correspond, sinon false.
     */
    function verifyPassword(string $email, string $password): bool
    {
        $merchantData = $this->getByEmail($email);
        if ($merchantData) {
            return password_verify(
                $password,
                $merchantData['hashed_password']
            );
        } else return false;
    }

    private function getByParam(string $field, $value): ?Merchant
    {
        try {
            $db = DBController::getInstance()->getDB();
            $stmt = $db->prepare("SELECT * FROM merchants WHERE $field = :value");
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            $merchantData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($merchantData) {
                $owner = UserDAO::getInstance()->getById($merchantData['user_id']);
                $business = BusinessDAO::getInstance()->getByMerchantId($owner->getId());
                return new Merchant($merchantData['id'], $owner, $business);
            }
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Récupère les données d'un marchand en fonction de son email.
     *
     * @param string $email L'email du marchand à rechercher.
     *
     * @return Merchant|null Les données du marchand ou null s'il n'existe pas.
     */
    public function getByEmail(string $email): ?Merchant
    {
        return $this->getByParam('email', $email);
    }

    public function getByUserId(int $id): ?Merchant {
        return $this->getByParam('user_id', $id);
    }

    public function getByPhoneNumber(string $phone): ?Merchant
    {
        return $this->getByParam('phone', $phone);
    }

    /**
     * Lie les paramètres d'une requête préparée aux valeurs de l'objet Merchant.
     *
     * @param PDOStatement $PDOStatement La requête préparée à laquelle lier les paramètres.
     * @param Merchant $merchant L'objet Merchant contenant les valeurs à lier.
     */
    private function bindParam(PDOStatement $PDOStatement, Merchant $merchant): void
    {
        $userId = $merchant->getId();
        $PDOStatement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $businessId = $merchant->getBusiness();
        $PDOStatement->bindParam(':business_id', $businessId, PDO::PARAM_INT);
        $phone = $merchant->getPhoneNumber();
        $PDOStatement->bindParam(':phone', $phone);
        $email = $merchant->getEmail();
        $PDOStatement->bindParam(':email', $email);
    }

    /**
     * Crée un nouvel enregistrement de marchand dans la base de données.
     *
     * @param Merchant $object L'objet Merchant à créer.
     *
     * @return bool True si la création a réussi, sinon false.
     */
    public function create($object): bool
    {
        if (!$object instanceof Merchant) return false;

        $merchant = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("INSERT INTO merchants (user_id, business_id, phone, email) VALUES (:user_id, :business_id, :phone, :email)");
            $this->bindParam($stmt, $merchant);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Met à jour un enregistrement de marchand dans la base de données.
     *
     * @param Merchant $object L'objet Merchant à mettre à jour.
     *
     * @return bool True si la mise à jour a réussi, sinon false.
     */
    public function update($object): bool
    {
        if (!$object instanceof Merchant) return false;

        $merchant = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("UPDATE merchants SET user_id = :user_id, business_id = :business_id, 
                     phone = :phone, email = :email WHERE id = :id");
            $this->bindParam($stmt, $merchant);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Supprime un enregistrement de marchand de la base de données.
     *
     * @param Merchant $object L'objet Merchant à supprimer.
     *
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function delete($object): bool
    {
        if (!$object instanceof Merchant) return false;

        $merchant = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("DELETE FROM merchants WHERE id = :id");
            $merchantId = $merchant->getId();
            $stmt->bindParam(':id', $merchantId);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }
}