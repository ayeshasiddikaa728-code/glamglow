<?php
session_start();

// Custom cart matrix empty hole set kore neya
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check product configuration key inputs
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Product jodi agey thekei cart-e thake, tobe tar quantity 1 bariye dao
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        // Notun product hole default dynamic array quantity element = 1 
        $_SESSION['cart'][$product_id] = 1;
    }
    
    // Index mapping runtime refresh path complete
    header("Location: index.php?status=success");
    exit;
}

// Cart theke dynamic item remove/delete korar code
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: view_cart.php?status=removed");
    exit;
}
?>