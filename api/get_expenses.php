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

try {
    $userId = $_SESSION['user_id'];
    $pdo = getDB();

    // Get query parameters
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // Build query
    $whereConditions = ['e.user_id = ?'];
    $params = [$userId];

    if ($type && in_array($type, ['income', 'expense'])) {
        $whereConditions[] = 'e.type = ?';
        $params[] = $type;
    }

    if ($categoryId > 0) {
        $whereConditions[] = 'e.category_id = ?';
        $params[] = $categoryId;
    }

    if ($startDate && DateTime::createFromFormat('Y-m-d', $startDate)) {
        $whereConditions[] = 'e.expense_date >= ?';
        $params[] = $startDate;
    }

    if ($endDate && DateTime::createFromFormat('Y-m-d', $endDate)) {
        $whereConditions[] = 'e.expense_date <= ?';
        $params[] = $endDate;
    }

    $whereClause = implode(' AND ', $whereConditions);

    // Get total count
    $countStmt = $pdo->prepare("
        SELECT COUNT(*) as total 
        FROM expenses e 
        WHERE {$whereClause}
    ");
    $countStmt->execute($params);
    $totalCount = $countStmt->fetch()['total'];

    // Get expenses with pagination
    // Build final SQL with LIMIT and OFFSET directly (safe because they're cast to integers)
    $sql = "
SELECT 
    e.id,
    e.amount,
    e.type,
    e.description,
    e.expense_date,
    e.created_at,
    c.name as category_name,
    c.icon as category_icon,
    c.color as category_color
FROM expenses e
JOIN categories c ON e.category_id = c.id
WHERE {$whereClause}
ORDER BY e.expense_date DESC, e.created_at DESC
LIMIT $limit OFFSET $offset
";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);  // No need to append limit & offset to $params


    $expenses = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $expenses[] = [
            'id' => $row['id'],
            'amount' => floatval($row['amount']),
            'type' => $row['type'],
            'description' => $row['description'],
            'date' => $row['expense_date'],
            'created_at' => $row['created_at'],
            'category' => [
                'name' => $row['category_name'],
                'icon' => $row['category_icon'],
                'color' => $row['category_color']
            ]
        ];
    }

    // Also get categories for filters
    $categoriesStmt = $pdo->prepare("SELECT id, name, icon, color FROM categories ORDER BY name");
    $categoriesStmt->execute();
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'expenses' => $expenses,
            'total' => $totalCount,
            'limit' => $limit,
            'offset' => $offset,
            'categories' => $categories
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>