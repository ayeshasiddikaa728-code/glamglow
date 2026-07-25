<div align="center">

# 💄 GlamGlow

### ✨ Cosmetic E-Commerce Platform

A modern full-stack beauty shopping platform built with **PHP**, **MySQL**, **Bootstrap 5**, and **PDO**.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

</div>

---

# 📑 Table of Contents

- [🌟 Key Features](#-key-features)
- [🛠️ Technologies Used](#️-technologies-used)
- [🌟 Why GlamGlow?](#-why-glamglow)
- [📂 Project Structure](#-project-structure)
- [🗄️ Database Setup](#️-database-setup-sql-schema)
- [🚀 Getting Started](#-getting-started-locally)
- [📸 Screenshots](#-screenshots)
- [🔄 Project Workflow](#-project-workflow)
- [🎯 Project Highlights](#-project-highlights)
- [🚀 Future Improvements](#-future-improvements)
- [👩‍💻 Author](#-author)
- [📝 License](#-license)

---

# 🌟 Key Features

### 🔐 User Authentication
- Secure user registration and login system.
- Password encryption using **PASSWORD_BCRYPT**.

### 🛍️ Dynamic Product Catalog
- Browse products by category.
- Instant search functionality.
- Clean and responsive product display.

### 🛒 Session-Based Shopping Cart
- Add products to cart.
- Update item quantities.
- Remove products.
- Cart managed using PHP Sessions.

### 💳 Order Placement & Checkout
- Customer information form.
- Multi-table database transaction.
- Stores order details and purchased items securely.

### 🛠️ Admin Panel
- Upload new products.
- Manage product information.
- Real-time image upload functionality.

---

# 🛠️ Technologies Used

| Layer | Technology |
|--------|------------|
| 🎨 Frontend | HTML5, CSS3, JavaScript, Bootstrap 5 |
| ⚙️ Backend | PHP (Object-Oriented Programming & PDO) |
| 🗄️ Database | MySQL |

---

# 🌟 Why GlamGlow?

**GlamGlow** is a full-stack cosmetic e-commerce platform designed to deliver a smooth and user-friendly online shopping experience. It combines secure authentication, efficient product management, a session-based shopping cart, and a structured checkout process to create a complete beauty shopping solution.

---

# 📂 Project Structure

```text
glamglow/
├── assets/
│   └── images/              # Uploaded product image files
├── classes/
│   ├── User.php             # Authentication and user operations
│   ├── Product.php          # Product management and filtering
│   └── Order.php            # Order placement and transaction handling
├── config/
│   └── db.php               # Database connection using PDO
├── index.php                # Homepage and product showcase
├── view_cart.php            # Shopping cart page
├── checkout.php             # Checkout and order summary
├── add_product.php          # Admin product upload page
├── login.php                # User login page
├── register.php             # User registration page
└── logout.php               # Logout and session destruction
```

---

# 🗄️ Database Setup (SQL Schema)

Before running the project, create a database named **glamglow_db** in **phpMyAdmin**, then execute the following SQL script.

```sql
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

-- Sample Categories
INSERT INTO categories (category_name) VALUES
('Lipstick'),
('Skincare'),
('Foundation'),
('Eye Makeup');

-- 3. Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    product_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE CASCADE
);

-- 4. Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    customer_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
);

-- 5. Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE,
    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE SET NULL
);
```

---

# 🚀 Getting Started Locally

## 📋 Prerequisites

- XAMPP / WAMP installed with **PHP 8.x**
- MySQL
- A modern web browser (Chrome, Firefox, or Edge)

---

## 📥 Installation

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/ayeshasiddikaa728-code/glamglow.git
```

Or download the ZIP file from GitHub.

---

### 2️⃣ Move the Project

Copy the project folder into your local server directory.

```text
C:\xampp\htdocs\glamglow
```

---

### 3️⃣ Import the Database

- Open **http://localhost/phpmyadmin/**
- Create a database named:

```text
glamglow_db
```

- Import the SQL script provided above.

---

### 4️⃣ Configure Database Connection

Open **config/db.php** and verify the database credentials.

```php
$host = "localhost";
$db_name = "glamglow_db";
$username = "root";
$password = "";
```

---

### 5️⃣ Run the Application

Open your browser and visit:

```text
http://localhost/glamglow/index.php
```

---

# 📸 Screenshots

> 📷 Add screenshots of your project here after uploading them to a `screenshots` folder.

| Home Page | Shopping Cart |
|------------|---------------|
| ![](screenshots/home.png) | ![](screenshots/cart.png) |

| Login | Admin Panel |
|--------|-------------|
| ![](screenshots/login.png) | ![](screenshots/admin.png) |

---

# 🔄 Project Workflow

```text
              User
                │
                ▼
       Browse Products
                │
                ▼
        Search by Category
                │
                ▼
         Add to Shopping Cart
                │
                ▼
            Checkout
                │
                ▼
      Order Saved in Database
                │
                ▼
      Admin Manages Products
```

---

# 🎯 Project Highlights

✅ Secure Authentication System

✅ Category-Based Product Browsing

✅ Dynamic Product Search

✅ Session-Based Shopping Cart

✅ Multi-Table Order Management

✅ Responsive Bootstrap Interface

✅ Object-Oriented PHP

✅ PDO Database Connection

✅ Admin Product Management

---

# 🚀 Future Improvements

- ❤️ Wishlist System
- 💳 Online Payment Gateway Integration
- 📦 Order Tracking
- ⭐ Product Reviews & Ratings
- 🔔 Email Notifications
- 📱 Progressive Web App (PWA)
- 🌙 Dark Mode
- ❤️ Favorite Products

---

# 👩‍💻 Author

## Mahbuba Siddika Aysah....

🎓 Third-Year Software Engineering Student

💻 Passionate about Full-Stack Web Development

✨ Always learning and building creative web applications.

⭐ If you like this project, don't forget to give it a **Star** on GitHub!

---

# 📝 License

This project is open-source and available under the **MIT License**.

---

<div align="center">

### 💖 Thank you for visiting this repository!

If you found this project helpful, consider giving it a ⭐ on GitHub.

**Happy Coding! 🚀**

</div>
