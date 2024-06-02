<?php

namespace controller;

use controller\Admin\AddWarningController;
use controller\Admin\BanUserController;
use controller\Admin\CreateAdminDashboardStatsController;
use controller\Admin\DeleteItemController;
use controller\Admin\DeleteUserController;
use controller\Admin\SearchFullUserInfoController;
use controller\Admin\SearchUserController;
use controller\Admin\UnbanUserController;
use controller\Admin\UpdateItemController;
use controller\Admin\UpdateUserController;

use controller\Client\AuthController;
use controller\Client\LogoutController;
use controller\Client\RegisterController;
use controller\Client\UnregisterController;
use controller\Client\UpdateController;
use controller\Client\AddItemCartController;
use controller\Client\RemoveItemCartController;

use controller\Item\BuyController;

use controller\Merchant\AddItemController;
use controller\Merchant\CreateDashboardStats;
use controller\Merchant\CreateItemListController;
use controller\Merchant\CreateOrderListController;
use controller\Merchant\CreateTransactionListController;
use controller\Merchant\RegisterMerchantController;
use controller\Services\PopulateDBController;

/** Et oublie pas de rajouter ici pcq sinon, tu vas avoir des erreurs comme tantôt */
// User controller files
require_once("Client/AuthController.php");
require_once("Client/LogoutController.php");
require_once("Client/RegisterController.php");
require_once("Client/UnregisterController.php");
require_once("Client/UpdateController.php");
require_once("Client/AddItemCartController.php");
require_once("Client/RemoveItemCartController.php");
// Item controller files
require_once("Item/AddController.php");
require_once("Item/DeleteController.php");
require_once("Item/UpdateAllController.php");
require_once("Item/UpdateQuantityController.php");
require_once("Item/BuyController.php");
// Admin controller files
require_once("Admin/UpdateUserController.php");
require_once("Admin/UpdateItemController.php");
require_once("Admin/DeleteUserController.php");
require_once("Admin/DeleteItemController.php");
require_once("Admin/AddWarningController.php");
require_once("Admin/BanUserController.php");
require_once("Admin/UnbanUserController.php");
require_once("Admin/SearchUserController.php");
require_once("Admin/SearchFullUserInfoController.php");
require_once("Admin/CreateAdminDashboardStatsController.php");
// Service controller files
require_once("Services/PopulateDBController.php");
// Merchant controller files
require_once("Merchant/RegisterMerchantController.php");
require_once("Merchant/CreateItemListController.php");
require_once("Merchant/CreateOrderListController.php");
require_once("Merchant/CreateMerchantDashboardStatsController.php");
require_once("Merchant/CreateTransactionListController.php");
require_once("Merchant/AddItemController.php");

class ControllerFactory
{
    private static array $registeredControllers = [];

    public function __construct()
    {
        ControllerFactory::init();
    }

    /**
     * Quand tu vas faire des nouveaux contrôleurs, rajoute-les ici
     * Comme ça, ils vont se register.
     *
     * @return void
     */
    private static function init(): void
    {
        // user actions
        ControllerFactory::$registeredControllers['authenticateClient'] = new AuthController();
        ControllerFactory::$registeredControllers['logoutClient'] = new LogoutController();
        ControllerFactory::$registeredControllers['registerClient'] = new RegisterController();
        ControllerFactory::$registeredControllers['unregisterClient'] = new UnregisterController();
        ControllerFactory::$registeredControllers['updateClient'] = new UpdateController();
        ControllerFactory::$registeredControllers['addItemToCart'] = new AddItemCartController();
        ControllerFactory::$registeredControllers['removeItemFromCart'] = new RemoveItemCartController();
        // merchant actions
        ControllerFactory::$registeredControllers['addProduit'] = new UpdateController();
        ControllerFactory::$registeredControllers['deleteProduit'] = new UpdateController();
        ControllerFactory::$registeredControllers['updateProduitAll'] = new UpdateController();
        ControllerFactory::$registeredControllers['updateProduitQuantity'] = new UpdateController();
        ControllerFactory::$registeredControllers['registerMerchant'] = new RegisterMerchantController();
        ControllerFactory::$registeredControllers['CreateMerchantItemList'] = new CreateItemListController();
        ControllerFactory::$registeredControllers['getMerchantDashboardStats'] = new CreateMerchantDashboardStatsController();
        ControllerFactory::$registeredControllers['GetMerchantOrderList'] = new CreateOrderListController();
        ControllerFactory::$registeredControllers['GetMerchantTransactionList'] = new CreateTransactionListController();
        ControllerFactory::$registeredControllers['merchantAddItem'] = new AddItemController();
        // admin actions
        ControllerFactory::$registeredControllers['updateUser'] = new UpdateUserController();
        ControllerFactory::$registeredControllers['updateItem'] = new UpdateItemController();
        ControllerFactory::$registeredControllers['deleteUser'] = new DeleteUserController();
        ControllerFactory::$registeredControllers['deleteItem'] = new DeleteItemController();
        ControllerFactory::$registeredControllers['addWarning'] = new AddWarningController();
        ControllerFactory::$registeredControllers['banUser'] = new BanUserController();
        ControllerFactory::$registeredControllers['unbanUser'] = new UnbanUserController();
        ControllerFactory::$registeredControllers['searchUserAdmin'] = new SearchUserController();
        ControllerFactory::$registeredControllers['searchFullUserInfo'] = new SearchFullUserInfoController();
        ControllerFactory::$registeredControllers['adminPopulateBD'] = new PopulateDBController();
        ControllerFactory::$registeredControllers['getAdminDashboardStats'] = new CreateAdminDashboardStatsController();

        //Item actions
        ControllerFactory::$registeredControllers['buyCart'] = new BuyController();
    }

    public static function get(string $action): AbstractController
    {
        return ControllerFactory::$registeredControllers[$action];
    }

    public static function isRegistered(string $action): bool
    {
        return isset(ControllerFactory::$registeredControllers[$action]);
    }

    /**
     * Puis ici aussi, dans le cas ou tu veux faire des contrôleurs temporaire I guess
     * @param string $action
     * @return AbstractController
     */
    public static function createController(string $action): AbstractController
    {
        // has to be updated at some point.
        ControllerFactory::$registeredControllers[$action] = match ($action) {
            'authenticateClient' => new AuthController(),
            'logoutClient' => new LogoutController(),
            'registerClient' => new RegisterController(),
            'unregisterClient' => new UnregisterController(),
            'updateClient' => new UpdateController(),
            default => new DefaultController(),
        };
        return ControllerFactory::$registeredControllers[$action];
    }
}