<?php
session_start();
require_once 'includes/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Expense Tracker</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <button class="btn btn-primary" onclick="openAddExpenseModal()">
                <i class="fas fa-plus"></i> Add Expense / Income
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon income">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="stat-content">
                    <h3 id="totalIncome">$0.00</h3>
                    <p>Total Income</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon expense">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="stat-content">
                    <h3 id="totalExpense">$0.00</h3>
                    <p>Total Expenses</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon balance">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-content">
                    <h3 id="totalBalance">$0.00</h3>
                    <p>Balance</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card">
                <h3>Monthly Trend</h3>
                <canvas id="monthlyChart"></canvas>
            </div>

            <div class="chart-card">
                <h3>Category Breakdown</h3>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="transactions-section">
            <div class="section-header">
                <h3>Recent Transactions</h3>
                <div class="filter-controls">
                    <select id="typeFilter">
                        <option value="">All Types</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                    <select id="categoryFilter">
                        <option value="">All Categories</option>
                    </select>
                </div>
            </div>

            <div class="transactions-table">
                <table id="transactionsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsBody">
                        <!-- Transactions will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Expense Modal -->
    <div id="expenseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Add Expense</h3>
                <span class="close" onclick="closeExpenseModal()">&times;</span>
            </div>

            <form id="expenseForm">
                <input type="hidden" id="expenseId" name="id">

                <div class="form-group">
                    <label for="expenseDate">Date</label>
                    <input type="date" id="expenseDate" name="date" required>
                </div>

                <div class="form-group">
                    <label for="expenseAmount">Amount</label>
                    <input type="number" step="0.01" id="expenseAmount" name="amount" required>
                </div>

                <div class="form-group">
                    <label for="expenseType">Type</label>
                    <select id="expenseType" name="type" required>
                        <option value="expense">Expense</option>
                        <option value="income">Income</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="expenseCategory">Category</label>
                    <select id="expenseCategory" name="category_id" required>
                        <!-- Categories will be loaded here -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="expenseDescription">Description</label>
                    <textarea id="expenseDescription" name="description" rows="3"></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeExpenseModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/dashboard.js"></script>
</body>

</html>