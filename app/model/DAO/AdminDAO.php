<?php

namespace model\DAO;

use database\DBController;
use DateTime;
use Exception;
use model\Item;
use model\User;
use PDO;
use PDOException;
use function error_log;

require_once __DIR__ . '/../User.php';
require_once __DIR__ . '/../Item.php';
require_once __DIR__ . '/../DAO/BanDAO.php';
require_once __DIR__ . '/../DAO/UserDAO.php';
require_once __DIR__ . '/../../database/DBController.php';
require_once 'DAOInterface.php';

class AdminDAO implements DAOInterface
{
    private static AdminDAO $instance;

    /**
     * Retourne l'instance AdminDAO.
     *
     * @return AdminDAO L'instance unique de la classe AdminDAO.
     */
    public static function getInstance(): AdminDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retourne l'utilisateur qui matche le prénom.
     *
     * Retourne null si aucun utilisateur ne matche le nom.
     *
     * @param string $firstName Le prénom de l'utilisateur à rechercher.
     * @return User|null L'objet User correspondant au nom, ou null s'il n'existe pas.
     */
    function getByFirstName(string $firstName): ?User
    {
        return UserDAO::getInstance()->getByFirstName($firstName);
    }

    /**
     * Retourne l'utilisateur qui matche l'ID.
     *
     * Retourne null si aucun utilisateur ne matche l'ID.
     *
     * @param int $id L'ID de l'utilisateur à rechercher.
     * @return User|null L'objet User correspondant à l'ID, ou null s'il n'existe pas.
     */
    function getUserById(int $id): ?User
    {
        return UserDAO::getInstance()->getById($id);
    }

    function findUsers(array $criteria): array
    {
        $db = DBController::getInstance()->getDB();
        $conditions = [];
        $bindParams = [];

        // Build the WHERE clause dynamically based on non-empty criteria
        if (!empty($criteria['firstName'])) {
            $conditions[] = 'first_name = :firstName';
            $bindParams[':firstName'] = $criteria['firstName'];
        }
        if (!empty($criteria['lastName'])) {
            $conditions[] = 'last_name = :lastName';
            $bindParams[':lastName'] = $criteria['lastName'];
        }
        if (!empty($criteria['email'])) {
            $conditions[] = 'email = :email';
            $bindParams[':email'] = $criteria['email'];
        }
        if (!empty($criteria['phone'])) {
            $conditions[] = 'phone_number = :phone';
            $bindParams[':phone'] = $criteria['phone'];
        }

        $whereClause = implode(' AND ', $conditions);

        try {
            $stmt = $db->prepare("SELECT id, email, first_name, last_name, date_of_birth, user_role, register_date, 
            profile_update, phone_number, profile_picture_url, warnings, isDeactivated FROM users
            WHERE {$whereClause}");

            $stmt->execute($bindParams);
            $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $users = [];
            foreach ($usersData as $userData) {
                $user = new User(
                    $userData['id'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $userData['email'],
                    new DateTime($userData['date_of_birth']),
                    $userData['phone_number'],
                    $userData['profile_picture_url'],
                    new DateTime($userData['profile_update']),
                    'xxxxxxxxxxxxxxxxxx'
                );
                $user->setRole($userData['user_role']);
                $user->setWarnings($userData['warnings']);
                $users[] = $user;
            }

            return $users;
        } catch (PDOException|Exception $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }

        return [];
    }


    /**
     * Retourne tous les utilisateurs.
     *
     * Retourne un tableau vide s'il n'y a aucun utilisateur.
     *
     * @return array Le tableau d'objets User.
     * @throws Exception
     */
    function getAllUsers(): array
    {
        $db = DBController::getInstance()->getDB();
        $users = [];
        $request = $db->prepare("SELECT * FROM users");
        $request->execute();
        foreach ($request as $userData) {
            $user = new User(
                $userData['id'],
                $userData['first_name'],
                $userData['last_name'],
                $userData['email'],
                new DateTime($userData['date_of_birth']),
                $userData['phone_number'],
                $userData['profile_picture_url'],
                new DateTime($userData['profile_update']),
                $userData['hashed_password']
            );
            $user->setRole($userData['user_role']);
            $user->setWarnings($userData['warnings']);
            $users[] = $user;
        }
        $request->closeCursor();
        return $users;
    }

    /**
     * Ajoute un avertissement à un utilisateur.
     *
     * @param User $user L'objet User à qui ajouter un avertissement.
     * @return bool true si l'ajout d'avertissement réussit, sinon false.
     */
    public function addWarningToUser(User $user): bool
    {
        $db = DBController::getInstance()->getDB();

        try {
            $user_id = $user->getId();
            // Incrémente le compteur d'avertissements pour l'utilisateur dans la base de données.
            $stmt = $db->prepare("UPDATE users SET warnings = warnings + 1 WHERE id = :id");
            $stmt->bindParam(':id', $user_id);
            $result = $stmt->execute();

            // Vérifie si la mise à jour a réussi.
            if ($result) {
                return true;
            }
        } catch (PDOException|Exception $exception) {
            error_log("Erreur de base de données : " . $exception->getMessage());
        }

        return false;
    }

    /**
     * Retourne l'item qui matche le nom.
     *
     * Retourne null si aucun item ne matche le nom.
     *
     * @param string $name Le nom de l'item à rechercher.
     * @return Item|null L'objet Item correspondant au nom, ou null s'il n'existe pas.
     */
    function getItemByName(string $name): ?Item
    {
        $db = DBController::getInstance()->getDB();

        try {
            // Préparation de la requête SQL.
            $stmt = $db->prepare("SELECT * FROM items WHERE item_name = :item_name");
            $stmt->bindParam(':item_name', $name);
            // Exécute & ferme la requête SQL.
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
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Retourne l'utilisateur qui matche l'ID.
     *
     * Retourne null si aucun utilisateur ne matche l'ID.
     *
     * @param int $id L'ID de l'item à rechercher.
     * @return Item|null L'objet Item correspondant à l'ID, ou null s'il n'existe pas.
     */
    function getItemById(int $id): ?Item
    {
        $db = DBController::getInstance()->getDB();

        try {
            // Préparation de la requête SQL.
            $stmt = $db->prepare("SELECT * FROM items WHERE id = :item_id");
            $stmt->bindParam(':item_id', $id);
            // Exécute & ferme la requête SQL.
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
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Retourne un produit aléatoire.
     *
     * @return Item|null L'objet Item correspondant au produit aléatoire, ou null s'il n'existe pas.
     */
    function getRandomItem(): ?Item
    {
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->query("SELECT * FROM items ORDER BY RAND() LIMIT 1");
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
            error_log("Erreur de base de données : " . $exception->getMessage());
        }
        return null;
    }

    /**
     * Retourne tous les produits.
     *
     * Retourne null si aucun produit n'est trouvé.
     *
     * @return array|null Le tableau d'objets Item, ou null s'il n'y a aucun produit.
     * @throws Exception
     */
    function getAllItems(): ?array
    {
        $db = DBController::getInstance()->getDB();
        $items = [];
        $request = $db->prepare("SELECT * FROM items");
        $request->execute();
        foreach ($request as $itemData) {
            $unItem = new Item(
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
            $items[] = $unItem;
        }
        $request->closeCursor();
        return $items;
    }

    /**
     * Recherche le mot de passe utilisateur dans la base de données sous forme hachée.
     * Si aucun utilisateur n'est trouvé, null est retourné.
     *
     * @param string $email L'adresse email de l'utilisateur dont on recherche le mot de passe.
     * @return string|null Le mot de passe haché de l'utilisateur, ou null s'il n'est pas trouvé.
     */
    public function retrieveHashedPassword(string $email): ?string
    {
        return UserDAO::getInstance()->retrieveHashedPassword($email);
    }

    public function banUser(int $userId, int $adminId, string $reason): bool
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("INSERT INTO bans (user_id, admin_id, reason) VALUES (:userId, :adminId, :reason)");
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':adminId', $adminId);
            $stmt->bindParam(':reason', $reason);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }

    /**
     * @param int $banId
     * @return bool
     */
    public function unbanUser(int $banId): bool
    {
        try {
            return BanDAO::getInstance()->delete(BanDAO::getInstance()->getById($banId));
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }

    /**
     * Supprime un objet (utilisateur ou item) de la base de données.
     *
     * @param mixed $object L'objet à supprimer.
     * @return bool true si la suppression réussit, sinon false.
     */
    public function delete($object): bool
    {
        if ($object instanceof User) {
            return UserDAO::getInstance()->delete($object);
        } elseif ($object instanceof Item) {
            return ItemDAO::getInstance()->delete($object);
        }
        return false;
    }

    /**
     * Crée un objet (utilisateur ou item) dans la base de données.
     *
     * @param mixed $object L'objet à créer.
     * @return bool true si la création réussit, sinon false.
     */
    public function create($object): bool
    {
        if ($object instanceof User) {
            return UserDAO::getInstance()->create($object);
        } elseif ($object instanceof Item) {
            return ItemDAO::getInstance()->create($object);
        }
        return false;
    }

    /**
     * Met à jour un objet (utilisateur ou item) dans la base de données.
     *
     * @param mixed $object L'objet à mettre à jour.
     * @return bool true si la mise à jour réussit, sinon false.
     */
    public function update($object): bool
    {
        if ($object instanceof User) {
            return UserDAO::getInstance()->update($object);
        } elseif ($object instanceof Item) {
            return ItemDAO::getInstance()->update($object);
        }
        return false;
    }
}
