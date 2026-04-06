<?php
session_start();
require_once 'includes/auth.php';

$pageTitle = 'Register';

// Redirect if already logged in
redirectIfLoggedIn();

$error = '';
$success = '';

if ($_POST) {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill in all fields.';
    } elseif (!validateEmail($email)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        $result = registerUser($username, $email, $password);
        if ($result['success']) {
            $success = 'Registration successful! You can now login.';
        } else {
            $error = $result['message'];
        }
    }
}

include 'includes/header.php';
?>

<div class="container">
    <div class="card" style="max-width: 400px; margin: 3rem auto;">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-user-plus"></i> Create Account
            </h2>
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

        <form method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-input" required minlength="3"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" required
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required minlength="6">
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-input" required
                    minlength="6">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e9ecef;">
            <p>Already have an account? <a href="login.php" style="color: #667eea; text-decoration: none;">Login
                    here</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>