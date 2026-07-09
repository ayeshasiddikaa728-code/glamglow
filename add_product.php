<?php
session_start();
// সিকিউরিটির জন্য চেক (লগইন ছাড়া যেন কেউ প্রোডাক্ট অ্যাড করতে না পারে)
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/db.php';
require_once 'classes/Product.php';

$database = new Database();
$db = $database->getConnection();
$productObj = new Product($db);

$categories = $productObj->getCategories();
$message = "";
$messageClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST['product_name']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    
    // ইমেজ আপলোড লজিক
    $image_name = $_FILES['product_image']['name'];
    $image_tmp = $_FILES['product_image']['tmp_name'];
    $target_dir = "assets/images/";
    
    // ইউনিক নাম তৈরি করা যেন একই নামের ছবি রিপ্লেস না হয়ে যায়
    $unique_image_name = time() . "_" . basename($image_name);
    $target_file = $target_dir . $unique_image_name;

    if (!empty($product_name) && !empty($category_id) && !empty($price) && !empty($image_name)) {
        // ইমেজটি নির্দিষ্ট ফোল্ডারে মুভ করা
        if (move_uploaded_file($image_tmp, $target_file)) {
            // ডাটাবেজে সেভ করা
            $result = $productObj->addProduct($category_id, $product_name, $price, $unique_image_name, $description);
            
            if ($result) {
                $message = "💄 Product Added Successfully!";
                $messageClass = "alert-success";
            } else {
                $message = "Failed to add product to database.";
                $messageClass = "alert-danger";
            }
        } else {
            $message = "Failed to upload image. Check if assets/images/ folder exists.";
            $messageClass = "alert-danger";
        }
    } else {
        $message = "All fields are required!";
        $messageClass = "alert-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GlamGlow - Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">💄 GlamGlow Admin</a>
        <a href="index.php" class="btn btn-outline-light btn-sm">View Shop</a>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow border-0 p-4 bg-white">
                <h3 class="fw-bold text-danger mb-4">✨ Add New Makeup Product</h3>
                
                <?php if(!empty($message)): ?>
                    <div class="alert <?= $messageClass; ?>"><?= $message; ?></div>
                <?php endif; ?>

                <!-- ফাইল আপলোডের জন্য enctype="multipart/form-data" দিতেই হবে -->
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Name</label>
                        <input type="text" name="product_name" class="form-control" placeholder="e.g. Waterproof Liquid Lipstick" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['category_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Price ($)</label>
                            <input type="number" name="price" step="0.01" class="form-control" placeholder="19.99" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Image</label>
                        <input type="file" name="product_image" class="form-control" accept="image/*" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Write features or details about this cosmetic item..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100 shadow mt-3">Upload Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>