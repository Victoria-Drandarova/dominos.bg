<?php

namespace Controller;

error_reporting(E_ALL ^ E_NOTICE);
spl_autoload_register(function ($class) {

    $c = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . $c . '.php';
});

use Model\Dao\ProductsDao;
use Model\Dao\SizeDao;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ProductsController {

    private static $instance;

    const MAX_PRODUCT = 10;
    const DEFAULT_SIZE_ID = 2;

//    private function __construct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new ProductsController();
        }
        return self::$instance;
    }

    public function addProductToCart($productId) {
        if (!isset($_SESSION["userDetails"])) {
            return false;
        }
        $singleProduct = new ProductsDao();
        try {
            $success = $singleProduct->getSingleProduct($productId);
            if ($success) {
                if (!in_array($success["id"], array_column($_SESSION["cart"], "id"))) {
                    /* set default quantity  to  1 */
                    $success["quantity"] = 1;
                    $success["extraIng"] = [];
                    $success["size"] = "Medium";
                    $success["size_id"] = SELF::DEFAULT_SIZE_ID;
                    $_SESSION["cart"][$success["id"]] = $success;
                    echo "You added " . $success['name'] . " in  your cart!";
                } else {
                    echo "You allready have this food in your cart :)";
                }

//            header("Location: ../View/some.php");
            } else {
                //todo return err msg
            }
        } catch (\PDOException $exp) {
            $this->insertErr($exp);
        }
    }

    public function addExtraIngToProd($ingId, $prdId) {
        try {
            $ingredients = new ProductsDao();
            $r = $ingredients->getIngrById($ingId);
            $size = new SizeDao();
            
            if (isset($_SESSION["cart"])) {
                $s = $size->getPrizeById($_SESSION["cart"][$prdId]["size_id"]);
                if (in_array($prdId, array_column($_SESSION["cart"], "id"))) {
                    $_SESSION["cart"][$prdId]["extraIng"][$r["id"]] = &$r["id"];
                    
                    $total = 0;

                    foreach ($_SESSION["cart"][$prdId]["extraIng"] as $inId) {
                        $prodPrice = $ingredients->getIngrById($inId);
                        $total += $prodPrice["price"];
                    }

                    $total += $_SESSION["cart"][$prdId]["price"] *
                            $_SESSION["cart"][$prdId]["quantity"];
                    $total + ($s["cost"]);

                    echo $total;

                } else {
                    //todo return err msg
                }
            } else {
                //todo return err msg
            }
        } catch (PDOException $exp) {
            $this->insertErr($exp);
        }
    }

    public function removeExtraIngFromProd($ingId, $prdId) {
        $ingredients = new ProductsDao();
        try {

            $resultIng = $ingredients->getIngrById($ingId);

            if (isset($_SESSION["cart"])) {

                if (in_array($prdId, array_column($_SESSION["cart"], "id"))) {

                    foreach ($_SESSION["cart"][$prdId]["extraIng"] as $ing) {
                        if ($ingId == $ing) {

                            unset($_SESSION["cart"][$prdId]["extraIng"][$ingId]);
                            return $resultIng["price"];
                        }
                    }
//                    header("Location: ../View/some.php");
                } else {
                    //todo return err msg
                }
            } else {
                //todo return err msg
            }
        } catch (PDOException $exp) {

            $this->insertErr($exp);
        }
    }

    public function plusQuantity($productId) {
        // if(isset($_SESSION["logged_user"]) && isset($_SESSION["cart"]))
        if (isset($_SESSION["cart"])) {

            if (in_array($productId, array_column($_SESSION["cart"], "id"))) {
                if ($_SESSION["cart"][$productId]["quantity"] === self::MAX_PRODUCT) {
                    return false;
                }
                $_SESSION["cart"][$productId]["quantity"] =
                $_SESSION["cart"][$productId]["quantity"] + 1;
                 
                $newQuantity = new \stdClass();
                $newQuantity->quantity = $_SESSION["cart"][$productId]["quantity"];
                
                return json_encode($newQuantity);
            } else {
                //todo return err msg
            }
        } else {
            //todo return err msg
        }
    }

    public function minusQunatity($productId) {
        // if(isset($_SESSION["logged_user"]) && isset($_SESSION["cart"]))
        if (isset($_SESSION["cart"])) {

            if (in_array($productId, array_column($_SESSION["cart"], "id"))) {
                if ($_SESSION["cart"][$productId]["quantity"] == 1) {
                    unset($_SESSION["cart"][$productId]);
                    return 0;
                }
                $_SESSION["cart"][$productId]["quantity"] =
                $_SESSION["cart"][$productId]["quantity"] - 1;
                 
                $newQuantity = new \stdClass();
                $newQuantity->quantity = $_SESSION["cart"][$productId]["quantity"];
                
                return json_encode($newQuantity);
            } else {
                //to do return  err msg
            }
        } else {
            //todo return err msg
        }
    }

    public function getExtraIng($extraIngId) {
        if (isset($_SESSION["cart"])) {
            $ingredients = new ProductsDao();
            try {
                $r = $ingredients->getIngrById($extraIngId);
                echo json_encode($r);
            } catch (\PDOException $exp) {
                $this->insertErr($exp);
            }
        } else {
            //todo return err msg
        }
    }

    public function getCartContent() {

        if (isset($_SESSION["userDetails"])) {
            
            $total = 0;
            
            $sizeDao = new SizeDao();
            $productDao = new ProductsDao();
            
            foreach ($_SESSION["cart"] as $product) {
                
                $total += $product["price"] * $product["quantity"];
                
                if ($product["extraIng"]) {
                    foreach ($product["extraIng"] as $ingId) {
                        $ingPrice = $productDao->getIngrById($ingId);
                        $total += $ingPrice["price"] * $product["quantity"];
                    }
                    
                }
                
                $sizePrice = $sizeDao->getPrizeById($product["size_id"]);
                $total + ($sizePrice["cost"]);
                
            }
            $content = new \stdClass();
            $content->pizzaList = $_SESSION["cart"];
            $content->total = $total;
            return json_encode($content);
        }
        return false;
    }

    public function getPizza() {
        $pizzaList = new ProductsDao();
        try {

            return $pizzaList->getAllPizza();
        } catch (PDOException $exp) {
            $this->insertErr($exp);
        }
    }

    public function getInfoProduct($productId) {

        if (!isset($_SESSION["userDetails"])) {
            return false;
        }

        try {

            $info = new ProductsDao();
            return $info->getProductInfo($productId);
        } catch (PDOException $exp) {

            $this->insertErr($exp);
        }
    }

    public function getIngCategory() {

        try {

            $ingCategory = new ProductsDao();
            return $ingCategory->getIngredientsCategory();
        } catch (PDOException $exp) {
            $this->insertErr($exp);
        }
    }

    public function isInExtraList($ingrId, $productId) {

        try {
            $proDao = new ProductsDao();
            $ingrData = $proDao->getIngrById($ingrId);
            $sizePrize = new SizeDao();
            $r = $sizePrize->getPrizeById($_SESSION["cart"][$productId]["size_id"]);

            $empty = true;
            foreach ($_SESSION["cart"][$productId]["extraIng"] as &$inId) {
                if ($ingrId == $inId) {
                    $total = $_SESSION["cart"][$productId]["price"] *
                            $_SESSION["cart"][$productId]["quantity"] + $ingrData["price"];
                    $total + ($r["cost"]);
                    $ingrData["total"] = $total;
                    echo json_encode($ingrData);
                    $empty = false;
                }
            }
            if ($empty) {
                echo 0;
            }
        } catch (\PDOException $exp) {
            $this->insertErr($exp);
        }
    }

    public function insertErr($exp) {
        $path = dirname(__DIR__);
        $path .= "/log/PDOExeption.txt";
        $errFile = fopen($path, "a");
        if ($errFile) {
            fwrite($errFile, $exp->getMessage() . '. Date -->> ' . date('l jS \of F Y h:i:s A'));
            fclose($errFile);
        } else {
            fclose($errFile);
        }
        header("Location: ../index.php?page=errpage");
    }

    public function getIngByCategory($categoryId) {

        try {

            $getIngredientsByCategory = new ProductsDao();
            return json_encode($getIngredientsByCategory->getIngredientsByCategory($categoryId));
        } catch (PDOException $exp) {
            $this->insertErr($exp);
        }
    }

}

/* retrun jason with all pizzas */
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["pizza"])) {
    $products = ProductsController::getInstance();
    echo json_encode($products->getPizza());
}
/* retrun selected pizza with content of the pizza */
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["productId"])) {
    $products = ProductsController::getInstance();
    $productId = trim(htmlentities($_GET["productId"]));
    echo json_encode($products->getInfoProduct($productId));
}
/* retrun jason with all ingredients categories */
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["categories"])) {
    $products = ProductsController::getInstance();
    echo json_encode($products->getIngCategory());
}
/* return list of ingredients by category ID */
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["categoriesId"])) {
    $products = ProductsController::getInstance();
    $categoriesId = trim(htmlentities($_GET["categoriesId"]));
    echo $products->getIngByCategory($categoriesId);
}
/* insert new prduct to cart */
if (isset($_POST["proId"])) {
    $products = ProductsController::getInstance();
    $prodId = trim(htmlentities($_POST["proId"]));
    $products->addProductToCart($prodId);
}
/* return  cart content */
if (isset($_POST["cart"])) {
    $products = ProductsController::getInstance();
    echo $products->getCartContent();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["prId"])) {
    $products = ProductsController::getInstance();
    $prId = trim(htmlentities($_GET["prId"]));
    echo $products->plusQuantity($prId);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["minusId"])) {
    $products = ProductsController::getInstance();
    $minusId = trim(htmlentities($_GET["minusId"]));
    echo $products->minusQunatity($minusId);
}

if (isset($_POST["ingId"]) && isset($_POST["prdId"])) {
    $products = ProductsController::getInstance();
    $ingId = trim(htmlentities($_POST["ingId"]));
    $prodId = trim(htmlentities($_POST["prdId"]));
    echo $products->addExtraIngToProd($ingId, $prodId);
}


if (isset($_POST["minusIngId"]) && isset($_POST["minusPrdId"])) {
    $products = ProductsController::getInstance();
    $ingId = trim(htmlentities($_POST["minusIngId"]));
    $prodId = trim(htmlentities($_POST["minusPrdId"]));
    echo $products->removeExtraIngFromProd($ingId, $prodId);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["extraIngId"])) {
    $products = ProductsController::getInstance();
    $ingId = trim(htmlentities($_GET["extraIngId"]));
    echo $products->getExtraIng($ingId);
}

if (isset($_GET["iId"]) && isset($_GET["proId"])) {
    $products = ProductsController::getInstance();
    $ingId = trim(htmlentities($_GET["iId"]));
    $proId = trim(htmlentities($_GET["proId"]));
    return $products->isInExtraList($ingId, $proId);
}

if (isset($_GET["total"])) {
    
    echo $_SESSION[0];
}