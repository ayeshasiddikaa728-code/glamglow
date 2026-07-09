<?php
class Order {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function createOrder($user_id, $name, $phone, $address, $total_amount, $cart_items, $products) {
        try {
            
            $this->conn->beginTransaction();

            
            $query = "INSERT INTO orders (user_id, customer_name, phone, address, total_amount) 
                      VALUES (:user_id, :name, :phone, :address, :total_amount)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":total_amount", $total_amount);
            $stmt->execute();
            
            
            $order_id = $this->conn->lastInsertId();

            
            $itemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                          VALUES (:order_id, :product_id, :quantity, :price)";
            $itemStmt = $this->conn->prepare($itemQuery);

            foreach ($products as $product) {
                $product_id = $product['id'];
                $quantity = $cart_items[$product_id];
                $price = $product['price'];

                $itemStmt->bindParam(":order_id", $order_id);
                $itemStmt->bindParam(":product_id", $product_id);
                $itemStmt->bindParam(":quantity", $quantity);
                $itemStmt->bindParam(":price", $price);
                $itemStmt->execute();
            }

            
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>