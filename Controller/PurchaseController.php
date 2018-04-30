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
            $orderId = $purchaseDao->insertOrder(1);

            foreach ($_SESSION["cart"] as $proId) {
                $purchaseModel = new PurchaseModel(1, $orderId, $proId["id"], $proId["quantity"]);
                $purchaseDao->insertHistory($purchaseModel);
                /* когато в продукта има допълнителна съставка я запписваме в базата
                 * заедно със ид-то на поръчката и продукта в които  се  садържа  
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
            $historyResult = $historyDao->getHistory($historyId, $userId);
            if ($historyResult) {
                /* $historyResult["id"] = productID */
                foreach ($historyResult as $order) {
                    try {
                        $extraIngr = $historyDao->getExtraIngr($order["id"], $historyId);
                    } catch (\PDOException $exp) {
                        return $exp->getMessage();
                    }
                }

                /* @var $extraIngr array */
                if ($extraIngr) {
                    $historyResult["extraIng"] = $extraIngr;
                    $historyArray[] = $historyResult;
                } else {
                    return json_encode($historyResult);
                }

                return json_encode($historyArray);
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
