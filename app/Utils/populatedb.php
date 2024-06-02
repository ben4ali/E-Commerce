<?php

use database\DBController;

require_once __DIR__ . '/../database/DBController.php';
require_once __DIR__ . '/../../plugins/Faker-1.9.2/src/autoload.php';
error_reporting(E_ALL & ~E_DEPRECATED);

function INIT_populateDB(): bool
{
    $faker = Faker\Factory::create();

    $numUsers = 50;
    $numCategories = 20;
    $numAddresses = 60;
    $numItems = 100;
    $numTransactions = 50;
    $numOrders = 50;
    $numOrderItems = 150;
    $numReviews = 70;

    $userIds = [];
    $categoryIds = [];
    $addressIds = [];
    $businessIds = [];
    $merchantIds = [];
    $itemIds = [];
    $transactionIds = [];
    $orderIds = [];
    $banIds = [];

    for ($i = 0; $i < $numUsers; $i++) {
        $userIds[] = insertUser($faker);
    }

    for ($i = 0; $i < $numCategories; $i++) {
        $categoryIds[] = insertCategory($faker);
    }

    for ($i = 0; $i < $numAddresses; $i++) {
        $addressIds[] = insertAddress($faker);
    }

    foreach ($userIds as $userId) {
        $addressId = $faker->randomElement($addressIds);
        $businessIds[] = insertBusiness($faker, $userId, $addressId);
    }

    foreach ($businessIds as $businessId) {
        $userId = $faker->randomElement($userIds);
        $merchantIds[] = insertMerchant($faker, $userId, $businessId);
    }

    for ($i = 0; $i < $numItems; $i++) {
        $merchantId = $faker->randomElement($merchantIds);
        $businessId = $faker->randomElement($businessIds);
        $categoryId = $faker->randomElement($categoryIds);
        $itemIds[] = insertItem($faker, $merchantId, $businessId, $categoryId);
    }

    for ($i = 0; $i < $numTransactions; $i++) {
        $userId = $faker->randomElement($userIds);
        $billingAddress = $faker->randomElement($addressIds);
        $shippingAddress = $faker->randomElement($addressIds);
        $transactionIds[] = insertTransaction($faker, $userId, $billingAddress, $shippingAddress);
    }

    for ($i = 0; $i < $numOrders; $i++) {
        $userId = $faker->randomElement($userIds);
        $deliveryAddressId = $faker->randomElement($addressIds);
        $transactionId = $faker->randomElement($transactionIds);
        $orderIds[] = insertOrder($faker, $userId, $deliveryAddressId, $transactionId);
    }

    for ($i = 0; $i < $numOrderItems; $i++) {
        $orderId = $faker->randomElement($orderIds);
        $itemId = $faker->randomElement($itemIds);
        insertOrderItem($faker, $orderId, $itemId);
    }

    foreach ($userIds as $userId) {
        if ($faker->boolean) {
            $adminId = $faker->randomElement($userIds);
            $banId = insertBan($faker, $userId, $adminId);
            $banIds[] = $banId;

            if ($faker->boolean) {
                insertAppeal($faker, $userId, $banId);
            }
        }
    }

    for ($i = 0; $i < $numReviews; $i++) {
        $userId = $faker->randomElement($userIds);
        $productId = $faker->randomElement($itemIds);
        insertReview($faker, $userId, $productId);
    }
    return true;
}


/**
 * @param $faker
 * @return int|null
 */
function insertUser($faker): null|int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO users (hashed_password, email, first_name, last_name, date_of_birth, user_role, phone_number, profile_picture_url, warnings, isDeactivated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            password_hash($faker->password, PASSWORD_DEFAULT),
            $faker->unique()->email,
            $faker->firstName,
            $faker->lastName,
            $faker->date,
            $faker->randomElement(['user', 'admin', 'merchant']),
            $faker->phoneNumber,
            $faker->imageUrl,
            $faker->randomDigit,
            $faker->boolean,
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @return null|int
 */
function insertCategory($faker): null|int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        $stmt->execute([
            $faker->word,
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @return int|null
 */
function insertAddress($faker): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO addresses (street, city, province, country, postal_code) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $faker->streetAddress,
            $faker->city,
            $faker->state,
            $faker->country,
            $faker->postcode,
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @param $ownerId
 * @param $addressId
 * @return int|null
 */
function insertBusiness($faker, $ownerId, $addressId): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO businesses (owner_id, address_id, bs_name, email, url, bs_type, NE, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $ownerId,
            $addressId,
            $faker->company,
            $faker->unique()->email,
            $faker->url,
            $faker->word,
            $faker->randomNumber(5, true),
            $faker->text,
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @param $userId
 * @param $businessId
 * @return int|null
 */
function insertMerchant($faker, $userId, $businessId): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO merchants (user_id, business_id, phone, email) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $businessId,
            $faker->phoneNumber,
            $faker->unique()->email,
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @param $merchantId
 * @param $businessId
 * @param $categoryId
 * @return int|null
 */
function insertItem($faker, $merchantId, $businessId, $categoryId): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO items (merchant_id, business_id, category_id, item_name, description, price, stock_quantity, image_url, html_data, upc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $merchantId,
            $businessId,
            $categoryId,
            $faker->word,
            $faker->text,
            $faker->randomFloat(2, 1, 1000),
            $faker->numberBetween(0, 100),
            $faker->imageUrl,
            $faker->randomHtml(2, 3),
            $faker->ean13,
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @param $userId
 * @param $billingAddress
 * @param $shippingAddress
 * @return int|null
 */
function insertTransaction($faker, $userId, $billingAddress, $shippingAddress): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO transactions (user_id, billing_address, shipping_address, payment_method, total) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $billingAddress,
            $shippingAddress,
            $faker->creditCardType,
            $faker->randomFloat(2, 10, 500),
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @param $userId
 * @param $deliveryAddressId
 * @param $transactionId
 * @return int|null
 */
function insertOrder($faker, $userId, $deliveryAddressId, $transactionId): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO orders (user_id, delivery_address_id, transaction_id, created_at, order_status, special_instruction) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $deliveryAddressId,
            $transactionId,
            $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            $faker->randomElement(['Pending', 'Shipped', 'Delivered', 'Canceled']),
            $faker->sentence,
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @param $orderId
 * @param $itemId
 * @return int|null
 */
function insertOrderItem($faker, $orderId, $itemId): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO order_items (order_id, item_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([
            $orderId,
            $itemId,
            $faker->numberBetween(1, 10),
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @param $userId
 * @param $adminId
 * @return int|null
 */
function insertBan($faker, $userId, $adminId): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO bans (user_id, admin_id, created_date, reason) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $adminId,
            $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            $faker->sentence,
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @param $userId
 * @param $banId
 * @return int|null
 */
function insertAppeal($faker, $userId, $banId): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO appeals (user_id, ban_id, created_at, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $banId,
            $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            $faker->text,
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}

/**
 * @param $faker
 * @param $userId
 * @param $productId
 * @return int|null
 */
function insertReview($faker, $userId, $productId): ?int
{
    $db = DBController::getInstance()->getDB();
    try {
        $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, comment, number_stars, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $productId,
            $faker->text,
            $faker->numberBetween(1, 5),
            $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
        ]);
        $stmt->closeCursor();
        return $db->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Une erreur est survenue: ' . $exception->getMessage());
    }
    return null;
}
