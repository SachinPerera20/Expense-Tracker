<?php
session_start();
require_once 'includes/auth.php';

// Logout user and redirect
logoutUser();
header('Location: index.php?message=logged_out');
exit;
?>