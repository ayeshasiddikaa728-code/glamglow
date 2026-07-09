<?php
session_start();
require_once 'config/db.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(!empty($username) && !empty($email) && !empty($password)) {
        $result = $user->register($username, $email, $password);
        if ($result === true) {
            header("Location: login.php?signup=success");
            exit;
        } else {
            $message = $result; // Error message display korbe
        }
    } else {
        $message = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>GlamGlow - Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h3 class="text-center text-danger mb-4">GlamGlow Signup</h3>
                <?php if(!empty($message)): ?>
                    <div class="alert alert-danger"><?= $message; ?></div>
                <?php endif; ?>
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Register</button>
                    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>