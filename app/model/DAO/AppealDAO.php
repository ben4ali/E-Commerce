<?php

namespace model\DAO;

use database\DBController;
use DateTime;
use Exception;
use model\Appeal;
use PDO;
use PDOException;

require_once __DIR__ . '/../Ban.php';
require_once __DIR__ . '/../User.php';
require_once __DIR__ . '/BanDAO.php';
require_once __DIR__ . '/UserDAO.php';
require_once __DIR__ . '/../../database/DBController.php';
require_once 'DAOInterface.php';

/**
 * Cette classe gère l'accès aux données pour les appels (appeals).
 */
class AppealDAO implements DAOInterface
{
    private static AppealDAO $instance;

    /**
     * Récupère les appels en fonction de l'ID de l'administrateur.
     *
     * @param int $adminId L'ID de l'administrateur.
     * @return array|null Un tableau d'objets Appeal correspondant à l'administrateur, ou null si aucun appel n'est trouvé.
     */
    public function getByAdminId(int $adminId): ?array
    {
        return $this->getByParam('admin_id', $adminId);
    }

    /**
     * Récupère les appels en fonction d'un paramètre spécifié.
     *
     * @param string $field Le champ de la table sur lequel effectuer la recherche.
     * @param mixed $value La valeur à rechercher dans le champ spécifié.
     * @return array|Appeal|null Si un seul appel correspond, renvoie l'objet Appeal correspondant.
     *                              Si plusieurs appels correspondent, renvoie un tableau d'objets Appeal.
     *                              Si aucun appel ne correspond, renvoie null.
     */
    private function getByParam(string $field, mixed $value): array|Appeal|null
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT * FROM appeals WHERE $field = :value");
            $stmt->bindParam(':value', $value);
            $stmt->execute();
            $appealData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $result = [];
            foreach ($appealData as $data) {
                // creating the user object.
                $user = UserDAO::getInstance()->getById($data['user_id']);
                // creating the ban object.
                $ban = BanDAO::getInstance()->getById($data['ban_id']);
                // creating the appeal object.
                if ($user != null && $ban != null) {
                    $result[] = new Appeal(
                        $data['id'],
                        $user,
                        $ban,
                        $data['created_at'],
                        $data['comment']
                    );
                }
            }
            if (count($result) === 1) {
                return $result[0];
            }
            return $result;
        } catch (PDOException|Exception $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return [];
    }

    /**
     * Retourne l'instance AppealDAO.
     *
     * @return AppealDAO L'instance unique de la classe AppealDAO.
     */
    public static function getInstance(): AppealDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Récupère l'appel en fonction de son ID.
     *
     * @param int $appealId L'ID de l'appel.
     * @return Appeal|null L'objet Appeal correspondant à l'ID, ou null si aucun appel n'est trouvé.
     */
    public function getById(int $appealId): ?Appeal
    {
        return $this->getByParam('id', $appealId);
    }

    /**
     * Récupère l'appel en fonction de l'ID de l'utilisateur.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @return Appeal|null L'objet Appeal correspondant à l'utilisateur, ou null si aucun appel n'est trouvé.
     */
    public function getByUserId(int $userId): ?Appeal
    {
        return $this->getByParam('userId', $userId);
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
        $appeals = [];

        try {
            $stmt = $db->prepare("SELECT * FROM appeals;");
            $stmt->execute();
            foreach ($stmt as $appealData) {
                // create user object.
                $user = UserDAO::getInstance()->getById($appealData['user_id']);
                $ban = BanDAO::getInstance()->getById($appealData['ban_id']);
                if ($user != null && $ban != null) {
                    // create ban object.
                    $appeal = new Appeal(
                        $appealData['id'],
                        $user,
                        $ban,
                        new DateTime($appealData['created_at']),
                        $appealData['comment']
                    );
                    $appeals[] = $appeal;
                }
            }
            $stmt->closeCursor();
            return $appeals;
        } catch (Exception $exception) {
            error_log($exception);
        }
        return $appeals;
    }

    /**
     * Crée un nouvel appel dans la base de données.
     *
     * @param mixed $object L'objet Appeal à créer.
     * @return bool true si la création réussit, sinon false.
     */
    public function create($object): bool
    {
        if (!$object instanceof Appeal) return false;

        $appeal = $object;
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("INSERT INTO appeals (user_id, ban_id, comment) 
            VALUES (:user_id, :ban_id, :comment)");
            $user_id = $appeal->getUser()->getId();
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $ban_id = $appeal->getBan()->getId();
            $stmt->bindParam(':ban_id', $ban_id, PDO::PARAM_INT);
            $comment = $appeal->getComment();
            $stmt->bindParam(':comment', $comment);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Met à jour un appel existant dans la base de données.
     *
     * @param mixed $object L'objet Appeal à mettre à jour.
     * @return bool true si la mise à jour réussit, sinon false.
     */
    public function update($object): bool
    {
        if (!$object instanceof Appeal) return false;

        $appeal = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("UPDATE appeals SET user_id = :user_id, ban_id = :ban_id, comment = :comment WHERE id = :id");
            $user_id = $appeal->getUser()->getId();
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $ban_id = $appeal->getBan()->getId();
            $stmt->bindParam(':ban_id', $ban_id, PDO::PARAM_INT);
            $comment = $appeal->getComment();
            $stmt->bindParam(':comment', $comment);
            $id = $appeal->getId();
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
     * Supprime un appel de la base de données.
     *
     * @param mixed $object L'objet Appeal à supprimer.
     * @return bool true si la suppression réussit, sinon false.
     */
    public function delete($object): bool
    {
        if (!$object instanceof Appeal) return false;

        $appeal = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("DELETE FROM appeals WHERE id = :id");
            $id = $appeal->getId();
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
