<?php
namespace Controller;
spl_autoload_register(function ($class) {

    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR .$c . '.php';
});

use Model\PurchaseModel;
use Model\dao\PurchaseDao;
use Controller\ValidationRules\Validation;
/**
 * Description of PurchaseController
 *
 * @author denis
 */
class PurchaseController {
    
    private static $instance;

    private function __construct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new ProductsController();
        }
        return self::$instance;
    }
    
    public function insertPurchase() {
        $clearData = Validation::clearData();
        
        $userId = $clearData($_SESSION["id"]);
        
        /* $_SESSION["product_id"], $_SESSION["quantity"] */
        /* $_SESSION["products_count"]*/
        $purchaseDao = new PurchaseDao();
        
        $orderId = $purchaseDao->insertOrder($userId);
        
        $purchaseModel = new PurchaseModel($orderId, $productId, $quantity);
        
        /*
         * for($i = 0; $i < $_SESSION["products_count"]; $i++ ){
         *      
         *          $purchaseDao->insertHistory($purchaseModel);
         * }
         */
    }
    
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["finish_order"])) {
    $purchaseController =  PurchaseController::getInstance();
    
}

