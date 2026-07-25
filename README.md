# 💄 GlamGlow — Cosmetic E-Commerce Platform

A full-stack online beauty store designed to browse cosmetic categories, manage a session-based shopping cart, and process product checkouts with an integrated admin panel.

---

## 🌟 Key Features

* 🔐 **User Authentication:** Secure registration and login system with password encryption (`PASSWORD_BCRYPT`).
* 🛍️ **Dynamic Product Catalog:** Category-wise product filtering and instant search functionality.
* 🛒 **Session-Based Cart:** Add, view, update, and remove cosmetic items dynamically using PHP sessions.
* 💳 **Order Placement & Checkout:** Multi-table transaction system that records order details and items in the database.
* 🛠️ **Admin Management:** Web interface for administrators to upload new products with real-time image uploads.

---

## 🛠️ Technologies Used

* **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
* **Backend:** PHP (Object-Oriented Programming & PDO)
* **Database:** MySQL

---

## 📂 Project Structure

```text
glamglow/
├── assets/
│   └── images/          # Uploaded product image files
├── classes/
│   ├── User.php         # Authentication and user operations
│   ├── Product.php      # Fetching, filtering, and adding products
│   └── Order.php        # Transaction and order placement system
├── config/
│   └── db.php           # Database connection using PDO
├── index.php            # Main homepage and product showcase
├── view_cart.php        # Shopping cart page
├── checkout.php         # Order summary and delivery address form
├── add_product.php      # Admin product upload page
├── login.php            # User login page
├── register.php         # User registration page
└── logout.php           # Session destruction and logout

🗄️ Database Setup (SQL Schema)
Before running the project, create a database named glamglow_db in phpMyAdmin and execute the following SQL script:
CREATE DATABASE IF NOT EXISTS glamglow_db;
USE glamglow_db;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);

-- Sample Categories Insert
INSERT INTO categories (category_name) VALUES 
('Lipstick'), ('Skincare'), ('Foundation'), ('Eye Makeup');

-- 3. Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    product_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- 4. Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    customer_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 5. Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

🚀 Getting Started Locally
Prerequisites
XAMPP / WAMP installed with PHP 8.x and MySQL.

Web browser (Chrome, Firefox, Edge).

Installation Steps
1. Clone the Repository

https://github.com/ayeshasiddikaa728-code/glamglow.git

2. Move to Server Directory
Copy the glamglow project folder into your local server root:

XAMPP: C:/xampp/htdocs/glamglow

3. Import Database

Open http://localhost/phpmyadmin/.

Create a database named glamglow_db.

Import the SQL code provided in the Database Setup section above.


4. Verify Database Connection
Make sure your config/db.php has correct credentials:
$host = "localhost";
$db_name = "glamglow_db";
$username = "root";
$password = "";

5. Run the Application
Open your browser and navigate to:

Plaintext
http://localhost/glamglow/index.php
📝 License
This project is open-source and available under the MIT License.


