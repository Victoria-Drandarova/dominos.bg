<?php
namespace Model;
/**
 * Description of PurchaseModel
 *
 * @author denis
 */
class PurchaseModel implements \JsonSerializable{
    
    private $userId;
    private $orderId;
    private $productId;
    private $quantity;
    
    public function __construct($userId, $orderId, $productId, $quantity) {
        $this->userId = $userId;
        $this->orderId = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
    
    public function getUserId() {
        return $this->userId;
    }

    public function getOrderId() {
        return $this->orderId;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
        
    public function jsonSerialize() {
        return get_object_vars($this);
    }

}
