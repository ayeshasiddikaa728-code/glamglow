<?php
session_start();
require_once 'config/db.php';
require_once 'classes/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if($user->login($email, $password)) {
        header("Location: index.php"); // Login success hole homepage-e jabe
        exit;
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>GlamGlow - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h3 class="text-center text-danger mb-4">GlamGlow Login</h3>
                <?php if(isset($_GET['signup'])): ?>
                    <div class="alert alert-success">Registration Successful! Please Login.</div>
                <?php endif; ?>
                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Login</button>
                    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Signup here</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>