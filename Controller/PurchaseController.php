<?php

namespace Controller;

spl_autoload_register(function ($class) {

    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . $c . '.php';
});

use Model\PurchaseModel;
use Model\dao\PurchaseDao;
use Controller\ValidationRules\Validation;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Description of PurchaseController
 *
 * @author denis
 */
class PurchaseController {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new PurchaseController();
        }
        return self::$instance;
    }

    public function insertPurchase() {

        $purchaseDao = new PurchaseDao();
        
        /* tazi edenica trqbwa  da se zamesti ot potrebitelskoto ID*/
        $orderId = $purchaseDao->insertOrder(1);

        foreach ($_SESSION["cart"] as $proId) {
            $purchaseModel = new PurchaseModel(1, $orderId, $proId["id"], $proId["quantity"]);
            $purchaseDao->insertHistory($purchaseModel);
        }
        unset($_SESSION["cart"]);
        echo "success";
    }

}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["finish_order"])) {
    $purchaseController = PurchaseController::getInstance();

    echo $purchaseController->insertPurchase();
}

