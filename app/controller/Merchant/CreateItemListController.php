<?php

namespace controller\Merchant;

use controller\AbstractController;
use database\DBController;
use model\Business;
use model\DAO\CategoryDAO;
use model\DAO\ItemDAO;

require_once __DIR__ . '/../../database/DBController.php';
require_once __DIR__ . '/../AbstractController.php';
require_once __DIR__ . '/../../model/Business.php';
require_once __DIR__ . '/../../model/DAO/CategoryDAO.php';
require_once __DIR__ . '/../../model/DAO/ItemDAO.php';
class CreateItemListController extends AbstractController
{

    public function execute(): void
    {
        session_start();
        if (isset($_SESSION['business'])) {
            $data = [];

            $items = null;
            $business = null;
            if ($_SESSION['business'] instanceof Business) {
                $business = $_SESSION['business'];
            }
            if ($business != null) {
                $items = ItemDAO::getInstance()->getByBusiness($business);
            }
            if ($items != null) {
                $data['items'] = $items;
                $categoryNames = [];
                foreach ($items as $item) {
                    if (!isset($categoryNames[$item->getCategoryId()])) {
                        $categoryNames[$item->getCategoryId()] = CategoryDAO::getInstance()->getById($item->getCategoryId())->getName();
                    }
                }
                $data['categories'] = $categoryNames;

                // Query to calculate the number of items sold
                $businessId = $business->getId(); // Assuming Business class has a getId method.
                $query = "SELECT item_id, SUM(quantity) as total_sold FROM order_items 
                      INNER JOIN orders ON orders.id = order_items.order_id 
                      INNER JOIN items ON items.id = order_items.item_id 
                      WHERE items.business_id = :business_id 
                      GROUP BY item_id";
                $fields = [':business_id'];
                $values = [$businessId];
                $soldItems = DBController::getInstance()->sendQuery($query, $fields, $values);

                $data['soldItems'] = $soldItems;
            }

            $_SESSION['itemListData'] = $data;
            Header('Location: merchant/viewItems.php');
            exit;
        }
    }

    public function getActionName(): string
    {
        return "CreateItemList";
    }
}