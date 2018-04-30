<?php

namespace Controller;

spl_autoload_register(function ($class) {

    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . $c . '.php';
});

use Model\PurchaseModel;
use Model\dao\PurchaseDao;
use Model\dao\HistoryDao;

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
        try {
            /* tazi edenica trqbwa  da se zamesti ot potrebitelskoto ID */
            $userId = $_SESSION["userDetails"]["id"];
            $orderId = $purchaseDao->insertOrder($userId);

            foreach ($_SESSION["cart"] as $proId) {
                $purchaseModel = new PurchaseModel(
                $userId, $orderId, $proId["id"], $proId["quantity"]);
                $purchaseDao->insertHistory($purchaseModel);
                /* когато в продукта има допълнителна съставка я запписваме в базата
                 * заедно със ид-то на поръчката и продукта в които се садържа  
                 */
                if ($proId["extraIng"]) {
                    $this->addExtraIngr($orderId, $proId["id"], $proId["extraIng"]);
                }
            }
            unset($_SESSION["cart"]);
            echo "success";
        } catch (\PDOException $exp) {
            return $exp->getMessage();
        }
    }

    public function addExtraIngr($ordId, $prodId, array $ingrArr) {
        $purchaseDao = new PurchaseDao();
        try {
            foreach ($ingrArr as $ingId) {
                $purchaseDao->extraIngrToProduct($ordId, $prodId, $ingId);
            }
        } catch (\PDOException $exp) {
            return $exp->getMessage();
        }
    }

    public function getHistoryList() {
        $historyDao = new HistoryDao();
        $userId = $_SESSION["userDetails"]["id"];

        try {
            $historyResult = $historyDao->historyList($userId);
            if ($historyResult) {
                return json_encode($historyResult);
            } else {
                return false;
            }
        } catch (\PDOException $exp) {
            return $exp->getMessage();
        }
    }

    public function getHistoryById($historyId) {
        $historyDao = new HistoryDao();
        $userId = $_SESSION["userDetails"]["id"];
        $historyArray = [];

        try {
            $total = 0;
            $historyResult = $historyDao->getHistory($historyId, $userId);
            if ($historyResult) {
                /* $historyResult["id"] = productID */
                foreach ($historyResult as $order) {
                    $quantity = $order["quantity"];
                    $total += $order["price"] * $quantity;
                    
                    try {
                        $extraIngr = $historyDao->getExtraIngr($order["id"], $historyId);
                        if ($extraIngr) {//ако има допълнителни съставки вземи им цената и я добави
                            foreach ($extraIngr as $price) {
                                $price = $price["price"] * $quantity;
                                $total += $price;
                                
                            }
                        }
                        
                    } catch (\PDOException $exp) {
                        return $exp->getMessage();
                    }
                }
               
                if ($extraIngr) {
                    $historyResult["extraIng"] = $extraIngr;
                    $historyResult["total"] = round($total, 2);
                    $historyArray[] = $historyResult;
                } else {
                    $historyResult["total"] = round($total, 2);
                    return json_encode($historyResult);
                }

                return json_encode($historyResult);
            } else {
                return false;
            }
        } catch (\PDOException $exp) {
            return $exp->getMessage();
        }
    }
}

/* finish order */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["finish_order"])) {
    $purchaseController = PurchaseController::getInstance();

    echo $purchaseController->insertPurchase();
}
/* get list  of  history */
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["get_history"])) {
    $purchaseController = PurchaseController::getInstance();
    echo $purchaseController->getHistoryList();
}

/* get selected hitory */
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["historyId"])) {
    $purchaseController = PurchaseController::getInstance();
    $id = trim(htmlentities($_GET["historyId"]));
    echo $purchaseController->getHistoryById($id);
}
