<?php

namespace Controller;

spl_autoload_register(function ($class) {

    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . $c . '.php';
});

use Model\Dao\SizeDao;
use Model\Dao\ProductsDao;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Description of SizesController
 *
 * @author denis
 */
class SizesController {

    private static $_instance;

    const SMAL = 1;
    const MEDIUM = 2;
    const LARGE = 3;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new SizesController();
        }
        return self::$_instance;
    }

    public function getSizes($prodId) {
        $sizeDao = new SizeDao();
        $productsDao = new ProductsDao();
        try {
            $productSizeId = $_SESSION["cart"][$prodId]["size_id"];
            $r = $sizeDao->getPrizeById($productSizeId);
            
            $total = 0;
            
            foreach ($_SESSION["cart"][$prodId]["extraIng"] as $inId) {
                $prodPrice = $productsDao->getIngrById($inId);
                $total += $prodPrice["price"];
            }
            
            $total += $_SESSION["cart"][$prodId]["price"] *
            $_SESSION["cart"][$prodId]["quantity"];
            $total + ($r["cost"]);
            
            $sizes = $sizeDao->getSizeList();
            $sizes["total"] = $total;
            $sizes["id"] = $r["id"];
            return json_encode($sizes);
        } catch (\PDOException $exp) {
            return $exp->getMessage();
        }
    }

    public function changeSize($sizeId, $productId) {
        $sizeDao = new SizeDao();
        $singleProduct = new ProductsDao();
        if ((integer) $sizeId > 3 || (integer) $sizeId < 1) {
            return;
        }
        try {

            $price = $sizeDao->getPrizeById($sizeId);

            $success = $singleProduct->getSingleProduct($productId);
            // взимаме оригиналната цена за да добавяма/премахваме от нея
            $originalPrice = $success["price"];
            if ($success) {
                if (in_array($success["id"], array_column($_SESSION["cart"], "id"))) {
                    /* set default quantity  to  1 */
                    switch ($price["id"]) {
                        case self::SMAL :
                            $_SESSION["cart"][$success["id"]]["price"] = $originalPrice - 2;
                            $_SESSION["cart"][$success["id"]]["size_id"] = self::SMAL;
                            $_SESSION["cart"][$success["id"]]["size"] = "Small";
                            return json_encode($_SESSION["cart"][$success["id"]]["price"]);

                        case self::MEDIUM :
                            $_SESSION["cart"][$success["id"]]["price"] = $originalPrice;
                            $_SESSION["cart"][$success["id"]]["size_id"] = self::MEDIUM;
                            $_SESSION["cart"][$success["id"]]["size"] = "Medium";
                            return $_SESSION["cart"][$success["id"]]["price"];

                        case self::LARGE :
                            $_SESSION["cart"][$success["id"]]["price"] = $originalPrice + 2;
                            $_SESSION["cart"][$success["id"]]["size_id"] = self::LARGE;
                            $_SESSION["cart"][$success["id"]]["size"] = "Large";
                            return json_encode($_SESSION["cart"][$success["id"]]["price"]);

                        default:
                            return;
                    }
                } else {
                    //todo return err msg
                }
            } else {
                //todo return err msg
            }
        } catch (\PDOException $exp) {
            return $exp->getMessage();
        }
    }

}

if (isset($_GET["size"])) {
    $sizeController = SizesController::getInstance();
    $productId = trim(htmlentities($_GET["size"]));
    echo $sizeController->getSizes($productId);
}

if (isset($_GET["changeSize"]) && isset($_GET["productId"])) {
    $sizeController = SizesController::getInstance();
    $size = trim(htmlentities($_GET["changeSize"]));
    $product = trim(htmlentities($_GET["productId"]));
    echo $sizeController->changeSize($size, $product);
}