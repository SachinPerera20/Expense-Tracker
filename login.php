<?php
session_start();
require_once 'includes/auth.php';

$pageTitle = 'Login';
$customStyle = 'assets/css/style.css'; // Use your main style.css

redirectIfLoggedIn();

$error = '';
$success = '';

if ($_POST) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $result = loginUser($username, $password);
        if ($result['success']) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-header">
        <h2><i class="fas fa-sign-in-alt"></i> Login</h2>
        <p>Access your account to manage your finances</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="auth-form">
        <div class="form-group">
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" required
                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>
    </form>

    <div class="auth-links">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>