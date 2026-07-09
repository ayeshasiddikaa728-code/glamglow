<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/db.php';
$database = new Database();
$db = $database->getConnection();

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products_in_cart = array();
$total_price = 0;

if (!empty($cart_items)) {
    // Array index keys matching custom placeholder setup 
    $ids = implode(',', array_keys($cart_items));
    $query = "SELECT * FROM products WHERE id IN ($ids)";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $products_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamGlow - Your Cart</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar Section -->
<nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">💄 GlamGlow</a>
        <div class="d-flex align-items-center">
            <a href="index.php" class="btn btn-outline-light btn-sm me-3">Back to Shop</a>
            <span class="text-white me-3">Hi, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-danger">🛒 Your Shopping Cart</h2>
    </div>

    <?php if (empty($products_in_cart)): ?>
        <div class="card shadow-sm p-5 text-center border-0 bg-white">
            <div class="py-4">
                <h4 class="text-muted mb-3">Your cart is currently empty!</h4>
                <p class="text-secondary">Quick select your favorite cosmetics now.</p>
                <a href="index.php" class="btn btn-danger shadow-sm mt-2 px-4">Shop Cosmetics</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm p-4 border-0 bg-white">
            <table class="table align-middle">
                <thead>
                    <tr class="text-secondary">
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products_in_cart as $item): 
                        $qty = $cart_items[$item['id']];
                        $subtotal = $item['price'] * $qty;
                        $total_price += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <strong class="text-dark d-block"><?= htmlspecialchars($item['product_name']); ?></strong>
                            </td>
                            <td>$<?= number_format($item['price'], 2); ?></td>
                            <td>
                                <span class="badge bg-secondary p-2 fs-6"><?= $qty; ?></span>
                            </td>
                            <td class="fw-bold text-danger">$<?= number_format($subtotal, 2); ?></td>
                            <td class="text-end">
                                <a href="cart_action.php?action=remove&id=<?= $item['id']; ?>" class="btn btn-sm btn-outline-danger px-3">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="table-light">
                        <td colspan="3" class="text-end fw-bold fs-5">Grand Total:</td>
                        <td colspan="2" class="fw-bold text-danger fs-4 text-end pe-4">$<?= number_format($total_price, 2); ?></td>
                    </tr>
                </tbody>
            </table>
            
            <!-- নতুন ডাইনামিক চেকআউট লিংক বাটনটি নিচে সেট করা হয়েছে -->
            <div class="text-end mt-4">
                <a href="checkout.php" class="btn btn-success btn-lg shadow px-5 fw-bold">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>