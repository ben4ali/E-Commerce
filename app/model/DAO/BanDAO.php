<?php

namespace model\DAO;

use database\DBController;
use DateTime;
use Exception;
use model\Ban;
use PDO;
use PDOException;

require_once __DIR__ . '/../../database/DBController.php';
require_once __DIR__ . '/UserDAO.php';
require_once __DIR__ . '/../Ban.php';
require_once __DIR__ . '/../User.php';
require_once 'DAOInterface.php';

/**
 * Cette classe gère l'accès aux données pour les bannissements (bans).
 */
class BanDAO implements DAOInterface
{
    private static BanDAO $instance;

    /**
     * Récupère les bannissements en fonction de l'ID de l'administrateur.
     *
     * @param int $adminId L'ID de l'administrateur.
     * @return array|null Un tableau d'objets Ban correspondant à l'administrateur, ou null si aucun bannissement n'est trouvé.
     */
    public function getByAdminId(int $adminId): ?array
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM bans WHERE admin_id = :admin_id");
            $stmt->bindParam(':admin_id', $adminId, PDO::PARAM_INT);
            $stmt->execute();
            $banData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $bans = [];
            foreach ($banData as $data) {
                $user = UserDAO::getInstance()->getById($data['user_id']);
                $admin = UserDAO::getInstance()->getById($data['admin_id']);
                if ($user != null && $admin != null) {
                    // create ban object.
                    $bans[] = new Ban(
                        $data['id'],
                        $user,
                        $admin,
                        new DateTime($data['created_date']),
                        $data['reason']
                    );
                }
            }
            return $bans;
        } catch (PDOException|Exception $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Retourne l'instance BanDAO.
     *
     * @return BanDAO L'instance unique de la classe BanDAO.
     */
    public static function getInstance(): BanDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Récupère le bannissement en fonction de l'ID de l'utilisateur.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @return Ban|null L'objet Ban correspondant à l'utilisateur, ou null si aucun bannissement n'est trouvé.
     */
    public function getByUserId(int $userId): ?Ban
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM bans WHERE user_id = :user_id LIMIT 1");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $banData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $user = UserDAO::getInstance()->getById($banData['user_id']);
            $admin = UserDAO::getInstance()->getById($banData['admin_id']);
            if ($user != null && $admin != null && $banData) {
                return new Ban(
                    $banData['id'],
                    $user,
                    $admin,
                    new DateTime($banData['created_date']),
                    $banData['reason']
                );
            }
        } catch (PDOException|Exception $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Récupère le bannissement en fonction de son ID.
     *
     * @param int $banId L'ID du bannissement.
     * @return Ban|null L'objet Ban correspondant à l'ID, ou null si aucun bannissement n'est trouvé.
     */
    public function getById(int $banId): ?Ban
    {
        $db = DBController::getInstance()->getDB();
        try {
            // Préparation de la requête SQL.
            $stmt = $db->prepare("SELECT * FROM bans WHERE id = :id");
            $stmt->bindParam(':id', $banId, PDO::PARAM_INT);
            // Exécution et fermeture de la requête SQL.
            $stmt->execute();
            $banData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $user = UserDAO::getInstance()->getById($banData['user_id']);
            $admin = UserDAO::getInstance()->getById($banData['admin_id']);
            if ($user != null && $admin != null && $banData) {
                return new Ban(
                    $banData['id'],
                    $user,
                    $admin,
                    new DateTime($banData['created_date']),
                    $banData['reason']
                );
            }
        } catch (PDOException|Exception $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Retourne tous les utilisateurs.
     *
     * Retourne un tableau vide s'il n'y a aucun utilisateur.
     *
     * @return array Le tableau d'objets User.
     */
    function findAll(): array
    {
        $db = DBController::getInstance()->getDB();
        $bans = [];

        try {
            $stmt = $db->prepare("SELECT * FROM bans;");
            $stmt->execute();
            foreach ($stmt as $banData) {
                // create user object.
                $user = UserDAO::getInstance()->getById($banData['user_id']);
                $admin = UserDAO::getInstance()->getById($banData['admin_id']);
                if ($user != null && $admin != null) {
                    // create ban object.
                    $ban = new Ban(
                        $banData['id'],
                        $user,
                        $admin,
                        new DateTime($banData['created_date']),
                        $banData['reason']
                    );
                    $bans[] = $ban;
                }
            }
            $stmt->closeCursor();
            return $bans;
        } catch (Exception $exception) {
            error_log($exception);
        }
        return $bans;
    }

    /**
     * Crée un nouveau bannissement dans la base de données.
     *
     * @param mixed $object L'objet Ban à créer.
     * @return bool true si la création réussit, sinon false.
     */
    public function create($object): bool
    {
        if (!$object instanceof Ban) {
            return false;
        }

        $ban = $object;
        $db = DBController::getInstance()->getDB();

        try {
            // Préparation de la requête SQL.
            $stmt = $db->prepare("INSERT INTO bans (user_id, admin_id, reason) VALUES (:user_id, :admin_id, :reason)");

            $user_id = $ban->getUser()->getId();
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $admin_id = $ban->getAdmin()->getId();
            $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
            $reason = $ban->getReason();
            $stmt->bindParam(':reason', $reason);

            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Met à jour un bannissement existant dans la base de données.
     *
     * @param mixed $object L'objet Ban à mettre à jour.
     * @return bool true si la mise à jour réussit, sinon false.
     */
    public function update($object): bool
    {
        if (!$object instanceof Ban) {
            return false;
        }

        $ban = $object;
        $db = DBController::getInstance()->getDB();

        try {
            // Préparation de la requête SQL.
            $stmt = $db->prepare("UPDATE bans SET user_id = :user_id, admin_id = :admin_id, reason = :reason WHERE id = :id");

            $user_id = $ban->getUser()->getId();
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $admin_id = $ban->getAdmin()->getId();
            $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
            $reason = $ban->getReason();
            $stmt->bindParam(':reason', $reason);
            $id = $ban->getId();
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Supprime un bannissement de la base de données.
     *
     * @param mixed $object L'objet Ban à supprimer.
     * @return bool true si la suppression réussit, sinon false.
     */
    public function delete($object): bool
    {
        if (!$object instanceof Ban) {
            return false;
        }

        $ban = $object;
        $db = DBController::getInstance()->getDB();

        try {
            // Préparation de la requête SQL.
            $stmt = $db->prepare("DELETE FROM bans WHERE id = :id");
            $id = $ban->getId();
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
