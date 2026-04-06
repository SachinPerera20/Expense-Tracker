<?php
session_start();
require_once 'includes/auth.php';

// Redirect to dashboard if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker - Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Take Control of Your Finances</h1>
                <p>Track your expenses, manage your budget, and achieve your financial goals with our easy-to-use
                    expense tracker.</p>

                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-primary">Get Started</a>
                    <a href="login.php" class="btn btn-secondary">Sign In</a>
                </div>
            </div>

            <div class="hero-features">
                <div class="feature-card">
                    <i class="fas fa-chart-pie"></i>
                    <h3>Visual Analytics</h3>
                    <p>See your spending patterns with beautiful charts and graphs</p>
                </div>

                <div class="feature-card">
                    <i class="fas fa-tags"></i>
                    <h3>Smart Categories</h3>
                    <p>Organize expenses with customizable categories</p>
                </div>

                <div class="feature-card">
                    <i class="fas fa-mobile-alt"></i>
                    <h3>Mobile Friendly</h3>
                    <p>Access your finances anywhere, anytime</p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>

</html>