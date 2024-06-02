<?php

namespace model\DAO;

use database\DBController;
use DateTime;
use Exception;
use model\User;
use PDO;
use PDOException;
use PDOStatement;
use function error_log;
use function password_verify;

require_once __DIR__ . '/../../database/DBController.php';
require_once 'DAOInterface.php';

class UserDAO implements DAOInterface
{
    private static UserDAO $instance;

    /**
     * Effectue la dernière étape de l'authentification de l'utilisateur en modifiant
     * son statut dans la base de données de 'inactif' à 'actif' et en enregistrant les informations
     * nécessaires dans le cookie du navigateur.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @return bool True si l'authentification réussit, sinon false.
     */
    function authenticate(string $email): bool
    {
        return true;
    }

    /**
     * Effectue la première étape de l'authentification en vérifiant si l'e-mail et le mot de passe
     * correspondent. Si la correspondance est réussie, la méthode createOPT() est appelée pour envoyer
     * un OTP à l'utilisateur.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @param string $password Le mot de passe hashed de l'utilisateur.
     * @param bool $checkIfDeactivated = false Si vrai, va retourner null si le compte est désactivé et existant.
     * @return bool True si l'authentification réussit, sinon false.
     */
    function verifyPassword(string $email, string $password, bool $checkIfDeactivated): bool
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("SELECT hashed_password, isDeactivated FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if ($userData) {
                if ($checkIfDeactivated && $userData['isDeactivated']) return false;
                return password_verify($password, $userData['hashed_password']);
            } else return false;

        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Retourne l'instance unique de UserDAO.
     *
     * @return UserDAO L'instance unique de UserDAO.
     */
    public static function getInstance(): UserDAO
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lie les paramètres de l'objet User à une instruction PDOStatement.
     *
     * @param PDOStatement $PDOStatement L'instruction PDOStatement à laquelle lier les paramètres.
     * @param User $user L'objet User contenant les valeurs des paramètres à lier.
     * @return PDOStatement
     */
    private function bindParam(PDOStatement $PDOStatement, User $user): PDOStatement
    {
        $email = $user->getEmail();
        $PDOStatement->bindParam(':email', $email);
        $firstName = $user->getFirstName();
        $PDOStatement->bindParam(':first_name', $firstName);
        $lastName = $user->getLastName();
        $PDOStatement->bindParam(':last_name', $lastName);
        $dateTime = $user->getBirthDate();
        $dateString = $dateTime->format('Y-m-d H:i:s');
        $PDOStatement->bindParam(':date_of_birth', $dateString);
        $phoneNumber = $user->getPhoneNumber();
        $PDOStatement->bindParam(':phone_number', $phoneNumber);
        $profilePictureUrl = $user->getProfilePictureUrl();
        $PDOStatement->bindParam(':profile_picture_url', $profilePictureUrl);
        $warnings = $user->getWarnings();
        $PDOStatement->bindParam(':warnings', $warnings);
        return $PDOStatement;
    }

    private function getByParam(string $field, string $value): ?User
    {
        $db = DBController::getInstance()->getDB();
        try {
            // Préparation de la requête SQL.
            $stmt = $db->prepare("SELECT * FROM users WHERE $field = :value;");
            $stmt->bindParam(':value', $value);
            // Execute & ferme la requête SQL.
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if ($userData) {
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
                return $user;
            }
        } catch (PDOException|Exception $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return null;
    }

    /**
     * @param int $id
     * @return User|null
     */
    function getById(int $id): ?User
    {
        return $this->getByParam('id', $id);
    }

    /**
     * @param string $firstName
     * @return User|null
     */
    function getByFirstName(string $firstName): ?User {
        return $this->getByParam('first_name', $firstName);
    }

    /**
     * Trouve un utilisateur dans la base de données en utilisant son adresse e-mail.
     * Si un utilisateur est trouvé, une instance de User est renvoyée, sinon null est renvoyé.
     *
     * @param string $email L'adresse e-mail de l'utilisateur à rechercher.
     * @return User|null L'objet User correspondant à l'adresse e-mail ou null s'il n'existe pas.
     */
    function getByEmail(string $email): ?User
    {
        return $this->getByParam('email', $email);
    }

    /**
     * Trouve un utilisateur dans la base de données en utilisant son numéro de téléphone.
     * Si un utilisateur est trouvé, une instance de User est renvoyée, sinon null est renvoyé.
     *
     * @param string $phoneNumber Le numéro de téléphone de l'utilisateur à rechercher.
     * @return User|null L'objet User correspondant au numéro de téléphone ou null s'il n'existe pas.
     */
    function getByPhoneNumber(string $phoneNumber): ?User
    {
        return $this->getByParam('phone_number', $phoneNumber);
    }

    /**
     * Crée un nouvel enregistrement d'utilisateur dans la base de données.
     *
     * @param $object L'objet User à créer.
     * @return bool True si la création réussit, sinon false.
     */
    function create($object): bool
    {
        if (!$object instanceof User) return false;

        $user = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("INSERT INTO users (hashed_password, email, first_name, last_name, date_of_birth, phone_number, profile_picture_url, warnings, user_role) 
                VALUES (:hashed_password, :email, :first_name, :last_name, :date_of_birth, :phone_number, :profile_picture_url, :warnings, :role)");
            $password = $user->getPassword();
            $role = $user->getRole();
            $stmt->bindParam(':hashed_password', $password);
            $stmt->bindParam(':role', $role);
            $stmt = $this->bindParam($stmt, $user);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
            return false;
        }
    }

    /**
     * Désactive un utilisateur en mettant à jour son statut dans la base de données.
     *
     * Cette méthode définie le champ 'isDeactivated' à 1 pour l'utilisateur spécifié dans la table 'users'.
     *
     * @param User $user L'utilisateur à désactiver.
     *
     * @return bool Vrai si l'utilisateur a été désactivé avec succès ; sinon, faux.
     *              Renvoie faux en cas d'erreur de base de données.
     */
    function deactivateUser(User $user): bool
    {
        $db = DBController::getInstance()->getDB();
        try {
            $stmt = $db->prepare("UPDATE users SET isDeactivated = 1 WHERE id = :id");
            $id = $user->getId();
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
     * Supprime un enregistrement utilisateur de la base de données.
     *
     * @param $object L'objet User à supprimer.
     * @return bool True si la suppression réussit, sinon false.
     */
    function delete($object): bool
    {
        if (!$object instanceof User) return false;

        $user = $object;
        $db = DBController::getInstance()->getDB();

        try {
            // delete transactions related to the user.
            $transactionDAO = new TransactionDAO();
            $transactions = $transactionDAO->getByUserId($user->getId());
            foreach ($transactions as $transaction) $transactionDAO->delete($transaction);
            // delete orders related to the user.
            $orderDAO = new OrderDAO();
            $orders = $orderDAO->getByUserId($user->getId());
            foreach ($orders as $order) $orderDAO->delete($order);
            // delete user data.
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            $id = $user->getId();
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
     * Met à jour les informations utilisateur dans la base de données, généralement
     * lorsqu'un utilisateur modifie son profil.
     *
     * @param $object L'objet User à mettre à jour.
     * @return bool True si la mise à jour réussit, sinon false.
     */
    function update($object): bool
    {
        if (!$object instanceof User) return false;

        $user = $object;
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("UPDATE users SET email = :email, first_name = :first_name, last_name = :last_name, 
                 date_of_birth = :date_of_birth, phone_number = :phone_number, profile_picture_url = :profile_picture_url, warnings = :warnings WHERE id = :id");
            $stmt = $this->bindParam($stmt, $user);
            $id = $user->getId();
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
     * Crée un nouvel OTP (One-Time Password) et le sauvegarde dans la base de données.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @return bool True si la création de l'OTP réussit, sinon false.
     */
    function createOPT(string $email): bool
    {
        $db = DBController::getInstance()->getDB();

        try {
            // Création du OPT
            $otp = mt_rand(10000, 99999);
            $msg = "Voici votre code OTP à confirmer: " . $otp;
            $msg = wordwrap($msg, 70);

            // Query du l'ID utilisateur.
            $userStmt = $db->prepare("SELECT id FROM users WHERE email = :email");
            $userStmt->bindParam(':email', $email);
            $userStmt->execute();
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Création de la nouvelle ligne loginOPT avec l'OPT et l'ID utilisateur.
                $optStmt = $db->prepare("REPLACE INTO loginOPT (user_id, opt) VALUES (:user_id, :opt)");
                $optStmt->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
                $optStmt->bindParam(':opt', $otp);
                $optStmt->execute();

                // Envoie du OPT au email de l'utilisateur.
                return mail($email, "Vérification OTP", $msg);
            } else return false; // aucun utilisateur trouvé.

        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }

    /**
     * Récupère le mot de passe hashé de l'utilisateur à partir de l'adresse e-mail.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @return string|null Le mot de passe hashé de l'utilisateur ou null s'il n'existe pas.
     */
    public function retrieveHashedPassword(string $email): ?string
    {
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("SELECT hashed_password FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Execute & ferme la requête SQL.
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($user) return $user['hashed_password'];

        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }

        return null;
    }

    /**
     * Vérifie si l'OTP (One-Time Password) est correct pour un utilisateur donné et le supprime de la base de données
     * après utilisation.
     *
     * @param int $userId L'ID de l'utilisateur.
     * @param string $opt L'OTP à vérifier.
     * @return bool True si l'OTP est correct, sinon false.
     */
    private function verifyOPT(int $userId, string $opt): bool
    {
        $db = DBController::getInstance()->getDB();

        try {
            $stmt = $db->prepare("SELECT opt FROM loginopt WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            // Execute & ferme la requête SQL.
            $stmt->execute();
            $opdData = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            if ($opt == $opdData['opt']) return true;

        } catch (PDOException $exception) {
            error_log("Database error: " . $exception->getMessage());
        }
        return false;
    }
}