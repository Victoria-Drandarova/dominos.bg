<?php

namespace Model;

/**
 * Description of IngredientsModel
 *
 * @author denis
 */
class IngredientsModel {
    
    private $id;
    private $name;
    private $price;
    private $categoryId;
    
    function __construct($id, $name, $price, $categoryId) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->categoryId = $categoryId;
    }
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getPrice() {
        return $this->price;
    }

    function getCategoryId() {
        return $this->categoryId;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }
    
}
