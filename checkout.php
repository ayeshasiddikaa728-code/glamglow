<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/db.php';
require_once 'classes/Order.php';

$database = new Database();
$db = $database->getConnection();
$orderObj = new Order($db);

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();


if (empty($cart_items)) {
    header("Location: index.php");
    exit;
}


$ids = implode(',', array_keys($cart_items));
$query = "SELECT * FROM products WHERE id IN ($ids)";
$stmt = $db->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


$total_price = 0;
foreach ($products as $item) {
    $total_price += $item['price'] * $cart_items[$item['id']];
}

$message = "";
$success = false;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $user_id = $_SESSION['user_id'];

    if (!empty($name) && !empty($phone) && !empty($address)) {
        $result = $orderObj->createOrder($user_id, $name, $phone, $address, $total_price, $cart_items, $products);
        
        if ($result) {
            $success = true;
            unset($_SESSION['cart']); // অর্ডার সফল হলে কার্ট খালি করে দেওয়া
        } else {
            $message = "Something went wrong! Please try again.";
        }
    } else {
        $message = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>GlamGlow - Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <?php if ($success): ?>
                
                <div class="card shadow p-5 text-center border-0">
                    <h1 class="display-4 text-success">🎉 Thank You!</h1>
                    <h3 class="fw-bold my-3">Your Order Has Been Placed Successfully!</h3>
                    <p class="text-muted">Our glam team will contact you shortly to confirm your cosmetics package.</p>
                    <a href="index.php" class="btn btn-danger mt-4 px-4 shadow">Continue Shopping</a>
                </div>
            <?php else: ?>
                
            
                <div class="card shadow border-0 p-4 mb-4">
                    <h2 class="fw-bold text-danger mb-4">🛍️ Checkout Details</h2>
                    
                    <?php if(!empty($message)): ?>
                        <div class="alert alert-danger"><?= $message; ?></div>
                    <?php endif; ?>

                    <div class="row">
                        
                        <div class="col-md-7 mb-4">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">Shipping Address</h5>
                            <form action="checkout.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="customer_name" class="form-control" value="<?= htmlspecialchars($_SESSION['username']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="017XXXXXXXX" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Full Delivery Address</label>
                                    <textarea name="address" class="form-control" rows="4" placeholder="House no, Road no, Area, City..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger btn-lg w-100 shadow mt-3">Confirm Order ($<?= number_format($total_price, 2); ?>)</button>
                            </form>
                        </div>

                        
                        <div class="col-md-5">
                            <div class="card bg-white p-3 border shadow-sm">
                                <h5 class="fw-bold border-bottom pb-2 mb-3">Order Summary</h5>
                                <ul class="list-group list-group-flush mb-3">
                                    <?php foreach ($products as $item): 
                                        $qty = $cart_items[$item['id']];
                                        $subtotal = $item['price'] * $qty;
                                    ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                            <div>
                                                <h6 class="my-0 fw-bold small text-dark"><?= htmlspecialchars($item['product_name']); ?></h6>
                                                <small class="text-muted">Qty: <?= $qty; ?></small>
                                            </div>
                                            <span class="text-muted">$<?= number_format($subtotal, 2); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                    <li class="list-group-item d-flex justify-content-between px-0 bg-transparent fw-bold fs-5 text-danger pt-3">
                                        <span>Total Amount:</span>
                                        <span>$<?= number_format($total_price, 2); ?></span>
                                    </li>
                                </ul>
                                <a href="view_cart.php" class="btn btn-sm btn-outline-secondary w-100">Modify Cart Items</a>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>