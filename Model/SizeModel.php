<?php

namespace Model;

/**
 * Description of SizeModel
 *
 * @author denis
 */
class SizeModel implements \JsonSerializable{
    
    private $id;
    private $size;
    private $cost;
    
    function __construct($id, $size, $cost) {
        $this->id = $id;
        $this->size = $size;
        $this->cost = $cost;
    }
    
    function getId() {
        return $this->id;
    }

    function getSize() {
        return $this->size;
    }

    function getCost() {
        return $this->cost;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSize($size) {
        $this->size = $size;
    }

    function setCost($cost) {
        $this->cost = $cost;
    }
    
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}
