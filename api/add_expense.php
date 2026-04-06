<?php
header('Content-Type: application/json');
session_start();

require_once '../config/database.php';
require_once '../includes/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (empty($input['amount']) || empty($input['type']) || empty($input['category_id']) || empty($input['date'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit();
    }

    $userId = $_SESSION['user_id'];
    $amount = floatval($input['amount']);
    $type = $input['type'];
    $categoryId = intval($input['category_id']);
    $description = $input['description'] ?? '';
    $date = $input['date'];

    // Validate type
    if (!in_array($type, ['income', 'expense'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid type']);
        exit();
    }

    // Validate date format
    if (!DateTime::createFromFormat('Y-m-d', $date)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid date format']);
        exit();
    }

    // Validate amount
    if ($amount <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Amount must be positive']);
        exit();
    }

    $pdo = getDB();

    // Check if category exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
    $stmt->execute([$categoryId]);
    if (!$stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid category']);
        exit();
    }

    // Insert expense
    $stmt = $pdo->prepare("
        INSERT INTO expenses (user_id, amount, type, category_id, description, expense_date) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([$userId, $amount, $type, $categoryId, $description, $date]);

    $expenseId = $pdo->lastInsertId();

    // Return success response
    echo json_encode([
        'success' => true,
        'id' => $expenseId,
        'message' => 'Expense added successfully'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>