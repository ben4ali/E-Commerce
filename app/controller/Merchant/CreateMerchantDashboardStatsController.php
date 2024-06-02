<?php

namespace controller;

use database\DBController;
use model\Business;

require_once __DIR__ . '/../../database/DBController.php';
require_once __DIR__ . '/../../Utils/date.php';

class CreateMerchantDashboardStatsController extends AbstractController
{
    public function execute(): void
    {
        session_start();
        $currentDateRange = getDateRange();             // Utils/date.php
        $previousDateRange = getPreviousDateRange();    // Utils/date.php

        $businessId = null;
        if($_SESSION['business'] instanceof Business) $businessId = $_SESSION['business']->getId();

        $currentData = [
            'totalRevenue' => $this->calculateTotalRevenue($currentDateRange, $businessId),
            'transactions' => $this->calculateTransactions($currentDateRange, $businessId),
            'orders' => $this->calculateOrders($currentDateRange, $businessId),
            'soldProducts' => $this->calculateSoldProducts($currentDateRange, $businessId)
        ];

        $previousData = [
            'totalRevenue' => $this->calculateTotalRevenue($previousDateRange, $businessId),
            'transactions' => $this->calculateTransactions($previousDateRange, $businessId),
            'orders' => $this->calculateOrders($previousDateRange, $businessId),
            'soldProducts' => $this->calculateSoldProducts($previousDateRange, $businessId)
        ];

        $percentageChange = calculatePercentageChange($currentData, $previousData); // Utils/date.php

        $_SESSION['dashboardData'] = [
            'currentData' => $currentData,
            'previousData' => $previousData,
            'percentageChange' => $percentageChange
        ];
        header('Location: merchant/dashboard.php');
    }

    private function calculateTotalRevenue(array $dateRange, int $businessId): float
    {
        $query = "SELECT SUM(transactions.total) AS totalRevenue 
              FROM transactions 
              JOIN orders ON transactions.id = orders.transaction_id 
              JOIN order_items ON orders.id = order_items.order_id
              JOIN items ON order_items.item_id = items.id
              WHERE items.business_id = :businessId
              AND orders.created_at BETWEEN :startDate AND :endDate";
        $params = [ $dateRange['startDate'], $dateRange['endDate'], $businessId ];
        $result = DBController::getInstance()->sendQuery($query, [':startDate', ':endDate', ':businessId'], $params);
        return $result[0]['totalRevenue'] ?? 0.0;
    }

    private function calculateTransactions(array $dateRange, int $businessId): int
    {
        $query = "SELECT COUNT(transactions.id) AS totalTransactions 
              FROM transactions 
              JOIN orders ON transactions.id = orders.transaction_id 
              JOIN order_items ON orders.id = order_items.order_id
              JOIN items ON order_items.item_id = items.id
              WHERE items.business_id = :businessId
              AND orders.created_at BETWEEN :startDate AND :endDate";
        $params = [ $dateRange['startDate'], $dateRange['endDate'], $businessId ];
        $result = DBController::getInstance()->sendQuery($query, [':startDate', ':endDate', ':businessId'], $params);
        return $result[0]['totalTransactions'] ?? 0;
    }

    private function calculateOrders(array $dateRange, int $businessId): int
    {
        $query = "SELECT COUNT(*) AS totalOrders FROM orders 
              JOIN order_items ON orders.id = order_items.order_id
              JOIN items ON order_items.item_id = items.id
              WHERE items.business_id = :businessId
              AND orders.created_at BETWEEN :startDate AND :endDate";
        $params = [ $dateRange['startDate'], $dateRange['endDate'], $businessId ];
        $result = DBController::getInstance()->sendQuery($query, [':startDate', ':endDate', ':businessId'], $params);
        return $result[0]['totalOrders'] ?? 0;
    }

    private function calculateSoldProducts(array $dateRange, int $businessId): int
    {
        $query = "SELECT SUM(order_items.quantity) AS totalSold 
              FROM order_items 
              JOIN orders ON order_items.order_id = orders.id 
              JOIN items ON order_items.item_id = items.id
              WHERE items.business_id = :businessId
              AND orders.created_at BETWEEN :startDate AND :endDate";
        $params = [ $dateRange['startDate'], $dateRange['endDate'], $businessId ];
        $result = DBController::getInstance()->sendQuery($query, [':startDate', ':endDate', 'businessId'], $params);
        return $result[0]['totalSold'] ?? 0;
    }


    public function getActionName(): string
    {
        return 'getMerchantDashboardStats';
    }
}
