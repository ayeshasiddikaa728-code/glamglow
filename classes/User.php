<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. User Registration (Signup)
    public function register($username, $email, $password) {
        // Email ready ache kina check kora
        $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":email", $email);
        $checkStmt->execute();

        if($checkStmt->rowCount() > 0) {
            return "Email already exists!";
        }

        // Main Insert Query
        $query = "INSERT INTO " . $this->table_name . " (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($query);

        // Security: Password Hashing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Bind parameters
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);

        if($stmt->execute()) {
            return true;
        }
        return "Something went wrong!";
    }

    // 2. User Authentication (Login)
    public function login($email, $password) {
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Password Match Checking
            if(password_verify($password, $row['password'])) {
                // Session Variables Set kora
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                return true;
            }
        }
        return false;
    }
}
?>