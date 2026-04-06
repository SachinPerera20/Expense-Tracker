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

    // Get current month and year
    $currentMonth = date('Y-m');
    $currentYear = date('Y');

    // Get total income and expenses
    $stmt = $pdo->prepare("
        SELECT 
            type,
            SUM(amount) as total
        FROM expenses 
        WHERE user_id = ?
        GROUP BY type
    ");
    $stmt->execute([$userId]);

    $totals = ['income' => 0, 'expense' => 0];
    while ($row = $stmt->fetch()) {
        $totals[$row['type']] = floatval($row['total']);
    }

    // Get monthly data for the current year
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(expense_date, '%Y-%m') as month,
            type,
            SUM(amount) as total
        FROM expenses 
        WHERE user_id = ? AND YEAR(expense_date) = ?
        GROUP BY month, type
        ORDER BY month
    ");
    $stmt->execute([$userId, $currentYear]);

    $monthlyData = [];
    while ($row = $stmt->fetch()) {
        $month = $row['month'];
        if (!isset($monthlyData[$month])) {
            $monthlyData[$month] = ['income' => 0, 'expense' => 0];
        }
        $monthlyData[$month][$row['type']] = floatval($row['total']);
    }

    // Get category breakdown for current month
    $stmt = $pdo->prepare("
        SELECT 
            c.name,
            c.color,
            c.icon,
            SUM(e.amount) as total,
            COUNT(e.id) as count
        FROM expenses e
        JOIN categories c ON e.category_id = c.id
        WHERE e.id = ? AND DATE_FORMAT(e.expense_date, '%Y-%m') = ? AND e.type = 'expense'
        GROUP BY c.id, c.name, c.color, c.icon
        ORDER BY total DESC
    ");
    $stmt->execute([$userId, $currentMonth]);

    $categoryBreakdown = [];
    while ($row = $stmt->fetch()) {
        $categoryBreakdown[] = [
            'name' => $row['name'],
            'color' => $row['color'],
            'icon' => $row['icon'],
            'total' => floatval($row['total']),
            'count' => intval($row['count'])
        ];
    }

    // Get recent transactions count
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM expenses 
        WHERE user_id = ? AND expense_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    ");
    $stmt->execute([$userId]);
    $recentTransactionsCount = $stmt->fetch()['count'];

    // Get this month's stats
    $stmt = $pdo->prepare("
        SELECT 
            type,
            SUM(amount) as total,
            COUNT(*) as count
        FROM expenses 
        WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?
        GROUP BY type
    ");
    $stmt->execute([$userId, $currentMonth]);

    $monthlyTotals = ['income' => 0, 'expense' => 0];
    $monthlyCounts = ['income' => 0, 'expense' => 0];

    while ($row = $stmt->fetch()) {
        $monthlyTotals[$row['type']] = floatval($row['total']);
        $monthlyCounts[$row['type']] = intval($row['count']);
    }

    // Calculate balance
    $balance = $totals['income'] - $totals['expense'];
    $monthlyBalance = $monthlyTotals['income'] - $monthlyTotals['expense'];

    // Prepare monthly chart data
    $monthlyChartData = [];
    for ($i = 0; $i < 12; $i++) {
        $month = date('Y-m', strtotime("-$i months"));
        $monthName = date('M', strtotime("-$i months"));

        $monthlyChartData[] = [
            'month' => $monthName,
            'income' => $monthlyData[$month]['income'] ?? 0,
            'expense' => $monthlyData[$month]['expense'] ?? 0,
            'balance' => ($monthlyData[$month]['income'] ?? 0) - ($monthlyData[$month]['expense'] ?? 0)
        ];
    }

    // Reverse to show chronological order
    $monthlyChartData = array_reverse($monthlyChartData);

    echo json_encode([
        'success' => true,
        'data' => [
            'totals' => [
                'income' => $totals['income'],
                'expense' => $totals['expense'],
                'balance' => $balance
            ],
            'monthly' => [
                'income' => $monthlyTotals['income'],
                'expense' => $monthlyTotals['expense'],
                'balance' => $monthlyBalance
            ],
            'counts' => [
                'total_transactions' => $monthlyCounts['income'] + $monthlyCounts['expense'],
                'recent_transactions' => $recentTransactionsCount
            ],
            'charts' => [
                'monthly' => $monthlyChartData,
                'categories' => $categoryBreakdown
            ]
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