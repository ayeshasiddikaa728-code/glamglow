<<?php
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Shob category fetch korar jonno
    public function getCategories() {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Shob products, category wise filtered, ba search filtered products fetch korar jonno
    public function getProducts($category_id = null, $search_keyword = null) {
        $query = "SELECT * FROM products WHERE 1=1";
        
        if ($category_id) {
            $query .= " AND category_id = :category_id";
        }
        
        if ($search_keyword) {
            $query .= " AND (product_name LIKE :search OR description LIKE :search)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($category_id) {
            $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        }
        if ($search_keyword) {
            $search_param = "%" . $search_keyword . "%";
            $stmt->bindParam(":search", $search_param, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // নতুন প্রোডাক্ট অ্যাড করার জন্য মেথড (classes/Product.php এর ভেতরে থাকবে)
public function addProduct($category_id, $product_name, $price, $image, $description) {
    $query = "INSERT INTO products (category_id, product_name, price, image, description) 
              VALUES (:category_id, :product_name, :price, :image, :description)";
              
    $stmt = $this->conn->prepare($query);
    
    $stmt->bindParam(":category_id", $category_id);
    $stmt->bindParam(":product_name", $product_name);
    $stmt->bindParam(":price", $price);
    $stmt->bindParam(":image", $image);
    $stmt->bindParam(":description", $description);
    
    if($stmt->execute()) {
        return true;
    }
    return false;
}
}
?>