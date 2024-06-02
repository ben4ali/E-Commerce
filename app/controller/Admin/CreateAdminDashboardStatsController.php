<?php

namespace controller\Admin;

use controller\AbstractController;
use database\DBController;

require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../Utils/date.php';

class CreateAdminDashboardStatsController extends AbstractController
{
    public function execute(): void
    {
        session_start();
        $currentDateRange  = getDateRange();            // Utils/date.php
        $previousDateRange = getPreviousDateRange();    // Utils/date.php

        $currentData = [
            'totalUsers' => $this->calculateTotalUsers(),
            'totalMerchants' => $this->calculateTotalMerchants(),
            'soldProducts' => $this->calculateTotalSoldProducts($currentDateRange),
            'totalSells' => $this->calculateTotalSells($currentDateRange)
        ];

        $previousData = [
            'soldProducts' => $this->calculateTotalSoldProducts($previousDateRange),
            'totalSells' => $this->calculateTotalSells($previousDateRange)
        ];

        $percentageChange = calculatePercentageChange($currentData, $previousData); // Utils/date.php

        $_SESSION['adminDashboardData'] = [
            'currentData' => $currentData,
            'previousData' => $previousData,
            'percentageChange' => $percentageChange
        ];
        header('Location: admin/dashboard.php');
    }

    private function calculateTotalUsers(): int
    {
        $query = "SELECT COUNT(*) AS totalUsers FROM users";
        $result = DBController::getInstance()->sendQuery($query, [], []);
        return $result[0]['totalUsers'] ?? 0;
    }

    private function calculateTotalMerchants(): int
    {
        $query = "SELECT COUNT(*) AS totalMerchants FROM merchants";
        $result = DBController::getInstance()->sendQuery($query, [], []);
        return $result[0]['totalMerchants'] ?? 0;
    }

    private function calculateTotalSoldProducts(array $dateRange): int
    {
        $query = "SELECT SUM(order_items.quantity) AS totalSold 
                  FROM order_items 
                  JOIN orders ON order_items.order_id = orders.id 
                  WHERE orders.created_at BETWEEN :startDate AND :endDate";
        $result = DBController::getInstance()->sendQuery($query, [':startDate', ':endDate'], [$dateRange['startDate'], $dateRange['endDate']]);
        return $result[0]['totalSold'] ?? 0;
    }

    private function calculateTotalSells(array $dateRange): float
    {
        $query = "SELECT SUM(transactions.total) AS totalSells 
                  FROM transactions 
                  JOIN orders ON transactions.id = orders.transaction_id 
                  WHERE orders.created_at BETWEEN :startDate AND :endDate";
        $result = DBController::getInstance()->sendQuery($query, [':startDate', ':endDate'], [$dateRange['startDate'], $dateRange['endDate']]);
        return $result[0]['totalSells'] ?? 0.0;
    }

    public function getActionName(): string
    {
        return 'getAdminDashboardStats';
    }
}