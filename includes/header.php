<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/auth.php';

$pageTitle = isset($pageTitle) ? $pageTitle : 'Expense Tracker';
$customStyle = isset($customStyle) ? $customStyle : 'assets/css/style.css';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($customStyle); ?>">
</head>

<body>
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php">
                        <i class="fas fa-chart-line"></i>
                        <span>ExpenseTracker</span>
                    </a>
                </div>

                <nav class="main-nav">
                    <ul>
                        <?php if (isLoggedIn()): ?>
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="about.php">About</a></li>
                            <li class="user-menu">
                                <a href="#" class="user-toggle">
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                                    <i class="fas fa-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="about.php">About</a></li>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>

                <div class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>