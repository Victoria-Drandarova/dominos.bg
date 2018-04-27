<?php

namespace Model;

/**
 * Description of ProductsModel
 *
 * @author denis
 */
class ProductsModel implements \JsonSerializable{

    public $id;
    public $name;
    public $price;
    public $img;
    public $categoryId;
    
    public function __construct($id, $name, $price, $img, $categoryId) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->img = $img;
        $this->categoryId = $categoryId;
    }
    function getCategoryId() {
        return $this->categoryId;
    }

    function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getImg() {
        return $this->img;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setImg($img) {
        $this->img = $img;
    }
    
    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
