<?php
session_start();
// User login na thakle login page-e pathiye dibe
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/db.php';
require_once 'classes/Product.php';

$database = new Database();
$db = $database->getConnection();
$productObj = new Product($db);

// URL parameters handling (Category ar Search tracking)
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : null;
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : null;

// Dynamic Data load kora
$categories = $productObj->getCategories();
$products = $productObj->getProducts($selected_category, $search_keyword);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamGlow - Cosmetic Store</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        /* Carousel aesthetic styles */
        .carousel-item {
            height: 380px;
        }
        .carousel-item img {
            object-fit: cover;
            width: 100%;
            height: 100%;
            filter: brightness(0.85);
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px);
            border-radius: 12px;
            padding: 20px;
            bottom: 30px;
        }
    </style>
</head>
<body>

<!-- Navbar Section -->
<nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">💄 GlamGlow</a>
        <div class="d-flex align-items-center">
            
            <!-- Cart Count Calculation Session Framework -->
            <?php 
                $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; 
            ?>
            <a href="view_cart.php" class="btn btn-warning btn-sm me-3 position-relative">
                🛒 Cart 
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
                    <?= $cart_count; ?>
                </span>
            </a>

            <span class="text-white me-3">Hi, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Hero Slider / Carousel Section -->
<div class="container mt-4">
    <div id="glamGlowCarousel" class="carousel slide shadow-sm" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#glamGlowCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#glamGlowCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#glamGlowCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner" style="border-radius: 16px; overflow: hidden;">
            
            <!-- Slide 1: General Brand Cosmetics -->
            <div class="carousel-item active">
                <img src="http://googleusercontent.com/image_collection/image_retrieval/12891064939921184483_0" class="d-block w-100" alt="Glamorous Makeup Collection">
                <div class="carousel-caption d-none d-md-block text-start px-4">
                    <span class="badge bg-danger mb-2">New Arrivals</span>
                    <h2 class="fw-bold text-white">GlamGlow Professional Line</h2>
                    <p>Elevate your everyday makeup routine with our premium, skin-friendly ingredients.</p>
                </div>
            </div>

            <!-- Slide 2: Lipsticks Collection -->
            <div class="carousel-item">
                <img src="http://googleusercontent.com/image_collection/image_retrieval/365385946391336981_0" class="d-block w-100" alt="Bold Velvet Lipsticks">
                <div class="carousel-caption d-none d-md-block text-start px-4">
                    <span class="badge bg-warning text-dark mb-2">Hot Trend</span>
                    <h2 class="fw-bold text-white">Seductive Velvet Lipsticks</h2>
                    <p>Experience ultra-pigmented formulas that stay flawless all day long without drying.</p>
                </div>
            </div>

            <!-- Slide 3: Eye Palettes -->
            <div class="carousel-item">
                <img src="http://googleusercontent.com/image_collection/image_retrieval/11409012627563168491_0" class="d-block w-100" alt="Creative Eyeshadow Palette">
                <div class="carousel-caption d-none d-md-block text-start px-4">
                    <span class="badge bg-success mb-2">Limited Edition</span>
                    <h2 class="fw-bold text-white">Masterpiece Eyeshadows</h2>
                    <p>9 highly blendable shades designed for dramatic daytime and glamorous night looks.</p>
                </div>
            </div>

        </div>
        
        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#glamGlowCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#glamGlowCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- Main Container -->
<div class="container my-5">
    <div class="row">
        
        <!-- Sidebar Category Filter (Left Side) -->
        <div class="col-md-3 mb-4">
            <h5 class="fw-bold mb-3">Categories</h5>
            <div class="list-group">
                <a href="index.php" class="list-group-item list-group-item-action <?= !$selected_category ? 'active bg-danger border-danger' : '' ?>">
                    All Products
                </a>
                <?php foreach($categories as $cat): ?>
                    <a href="index.php?category=<?= $cat['id']; ?>" 
                       class="list-group-item list-group-item-action <?= $selected_category == $cat['id'] ? 'active bg-danger border-danger' : '' ?>">
                        <?= htmlspecialchars($cat['category_name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Products Grid Layout (Right Side) -->
        <div class="col-md-9">
            
            <!-- Live Custom Search Form -->
            <div class="card p-3 shadow-sm mb-4 border-0 bg-white">
                <form action="index.php" method="GET" class="row g-2">
                    <?php if($selected_category): ?>
                        <input type="hidden" name="category" value="<?= $selected_category; ?>">
                    <?php endif; ?>
                    
                    <div class="col-md-9 col-sm-8">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search makeup items (e.g. Lipstick, Foundation)..." 
                               value="<?= htmlspecialchars($search_keyword ?? ''); ?>">
                    </div>
                    <div class="col-md-3 col-sm-4 d-flex gap-1">
                        <button type="submit" class="btn btn-danger w-100 shadow-sm">Search</button>
                        <?php if($search_keyword): ?>
                            <a href="index.php<?= $selected_category ? '?category='.$selected_category : ''; ?>" class="btn btn-outline-secondary">Clear</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Active Filter Badge -->
            <?php if($search_keyword): ?>
                <p class="text-muted small">Showing results for: <span class="badge bg-dark fs-6"><?= htmlspecialchars($search_keyword); ?></span></p>
            <?php endif; ?>

            <h4 class="fw-bold mb-4">Our Makeup Collection</h4>
            <div class="row">
                
                <?php if (count($products) > 0): ?>
                    <?php foreach($products as $product): ?>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm product-card">
                                <img src="assets/images/<?= htmlspecialchars($product['image']); ?>" 
                                     class="card-img-top p-3" 
                                     alt="<?= htmlspecialchars($product['product_name']); ?>"
                                     style="height: 200px; object-fit: contain;"
                                     onerror="this.src='https://placehold.co/200x200?text=Cosmetic+Item'">
                                
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title fw-bold text-dark"><?= htmlspecialchars($product['product_name']); ?></h6>
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?= htmlspecialchars(substr($product['description'], 0, 70)) . '...'; ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="fw-bold text-danger fs-5">$<?= number_format($product['price'], 2); ?></span>
                                        <!-- Direct Session Cart Action Link -->
                                        <a href="cart_action.php?action=add&id=<?= $product['id']; ?>" class="btn btn-sm btn-danger px-3 shadow-sm">
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">No makeup products found!</div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<!-- Bootstrap 5 JS Bundle CDN for Carousel transitions -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>