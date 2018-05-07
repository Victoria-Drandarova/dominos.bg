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
            
            /* връща информация за размера според ид-то му */
            $r = $sizeDao->getPrizeById($productSizeId);

            $total = 0;
            /* проверяваме дали  в продукта има допълнителни съставки за
             * да добавим цената им в общата цена на продукта
             */
            foreach ($_SESSION["cart"][$prodId]["extraIng"] as $inId) {
                $prodPrice = $productsDao->getIngrById($inId);
                $total += $prodPrice["price"];
            }

            $total += $_SESSION["cart"][$prodId]["price"];
            $total += ($r["cost"]);

            $sizes = $sizeDao->getSizeList();
            
            $std = new \stdClass();

            $std->sizeList = $sizes;
            $std->total = $total;
            $std->sizeId = $r["id"];

            return json_encode($std);
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
            /* данни на  размера */
            $sizePrice = $sizeDao->getPrizeById($sizeId);
            
            /* настоящият размер  на  продукта */
            $productSizeId = $_SESSION["cart"][$productId]["size_id"];
            
            $total = 0;

            $total += $_SESSION["cart"][$productId]["price"];
            /* проверяваме дали  в продукта има допълнителни съставки за
             * да добавим сената им в общата цена на продукта
             */
            foreach ($_SESSION["cart"][$productId]["extraIng"] as $inId) {
                $proPr = $singleProduct->getIngrById($inId);
                $total += $proPr["price"];
            }
            $_SESSION["cart"][$productId]["size"] = $sizePrice["size"];
            $_SESSION["cart"][$productId]["size_id"] = $sizePrice["id"];
            $total += ($sizePrice["cost"]);
            $total = number_format((float)$total, 2);
            if ($productSizeId == $sizeId) {
                return;
            }
            return json_encode($total);

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