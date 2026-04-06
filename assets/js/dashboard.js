// Dashboard specific JavaScript
let monthlyChart = null;
let categoryChart = null;
let currentExpenseId = null;

document.addEventListener('DOMContentLoaded', function () {
    // Initialize dashboard
    initializeDashboard();

    // Set up form handlers
    setupFormHandlers();

    // Set up filter handlers
    setupFilterHandlers();

    // Set default date to today
    document.getElementById('expenseDate').value = new Date().toISOString().split('T')[0];
});

function initializeDashboard() {
    // Load dashboard data
    loadDashboardStats();
    loadTransactions();
    loadCategories();
}

function loadDashboardStats() {
    API.get('api/get_stats.php')
        .then(response => {
            if (response.success) {
                updateStatsCards(response.data);
                updateCharts(response.data.charts);
            }
        })
        .catch(error => {
            console.error('Error loading stats:', error);
            showNotification('Error loading dashboard statistics', 'error');
        });
}

function updateStatsCards(data) {
    document.getElementById('totalIncome').textContent = formatCurrency(data.totals.income);
    document.getElementById('totalExpense').textContent = formatCurrency(data.totals.expense);
    document.getElementById('totalBalance').textContent = formatCurrency(data.totals.balance);

    // Update balance color based on positive/negative
    const balanceElement = document.getElementById('totalBalance');
    const balanceCard = balanceElement.closest('.stat-card');

    if (data.totals.balance >= 0) {
        balanceCard.classList.remove('negative');
        balanceCard.classList.add('positive');
    } else {
        balanceCard.classList.remove('positive');
        balanceCard.classList.add('negative');
    }
}

function updateCharts(chartData) {
    updateMonthlyChart(chartData.monthly);
    updateCategoryChart(chartData.categories);
}

function updateMonthlyChart(monthlyData) {
    const ctx = document.getElementById('monthlyChart').getContext('2d');

    if (monthlyChart) {
        monthlyChart.destroy();
    }

    monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [
                {
                    label: 'Income',
                    data: monthlyData.map(item => item.income),
                    borderColor: '#4BC0C0',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Expenses',
                    data: monthlyData.map(item => item.expense),
                    borderColor: '#FF6384',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

function updateCategoryChart(categoryData) {
    const ctx = document.getElementById('categoryChart').getContext('2d');

    if (categoryChart) {
        categoryChart.destroy();
    }

    if (categoryData.length === 0) {
        // Show empty state
        ctx.font = '16px Arial';
        ctx.fillStyle = '#666';
        ctx.textAlign = 'center';
        ctx.fillText('No expenses this month', ctx.canvas.width / 2, ctx.canvas.height / 2);
        return;
    }

    categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.name),
            datasets: [{
                data: categoryData.map(item => item.total),
                backgroundColor: categoryData.map(item => item.color),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': $' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

function loadTransactions() {
    const typeFilter = document.getElementById('typeFilter').value;
    const categoryFilter = document.getElementById('categoryFilter').value;

    let url = 'api/get_expenses.php?limit=20';
    if (typeFilter) url += '&type=' + typeFilter;
    if (categoryFilter) url += '&category_id=' + categoryFilter;

    API.get(url)
        .then(response => {
            if (response.success) {
                updateTransactionsTable(response.data.expenses);
            }
        })
        .catch(error => {
            console.error('Error loading transactions:', error);
            showNotification('Error loading transactions', 'error');
        });
}

function updateTransactionsTable(transactions) {
    const tbody = document.getElementById('transactionsBody');
    tbody.innerHTML = '';

    if (transactions.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No transactions found</td></tr>';
        return;
    }

    transactions.forEach(transaction => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${formatDate(transaction.date)}</td>
            <td>${transaction.description || '-'}</td>
            <td>
                <span class="category-badge" style="background-color: ${transaction.category.color}">
                    <i class="${transaction.category.icon}"></i>
                    ${transaction.category.name}
                </span>
            </td>
            <td class="amount ${transaction.type}">${formatCurrency(transaction.amount)}</td>
            <td>
                <span class="type-badge ${transaction.type}">${transaction.type}</span>
            </td>
            <td>
                <button class="btn-icon" onclick="editExpense(${transaction.id})" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-icon btn-danger" onclick="deleteExpense(${transaction.id})" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function loadCategories() {
    API.get('api/get_expenses.php?limit=1')
        .then(response => {
            if (response.success) {
                updateCategorySelects(response.data.categories);
            }
        })
        .catch(error => {
            console.error('Error loading categories:', error);
        });
}

function updateCategorySelects(categories) {
    const expenseCategory = document.getElementById('expenseCategory');
    const categoryFilter = document.getElementById('categoryFilter');

    // Clear current options
    expenseCategory.innerHTML = '';
    categoryFilter.innerHTML = '<option value="">All Categories</option>';

    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        expenseCategory.appendChild(option);

        // Add to filter as well
        const filterOption = option.cloneNode(true);
        categoryFilter.appendChild(filterOption);
    });
}


function setupFormHandlers() {
    const expenseForm = document.getElementById('expenseForm');
    expenseForm.addEventListener('submit', function (e) {
        e.preventDefault();
        saveExpense();
    });
}

function setupFilterHandlers() {
    document.getElementById('typeFilter').addEventListener('change', loadTransactions);
    document.getElementById('categoryFilter').addEventListener('change', loadTransactions);
}

function openAddExpenseModal() {
    currentExpenseId = null;
    document.getElementById('modalTitle').textContent = 'Add Expense';
    document.getElementById('expenseForm').reset();
    document.getElementById('expenseDate').value = new Date().toISOString().split('T')[0];
    document.getElementById('expenseModal').style.display = 'block';
}

function closeExpenseModal() {
    document.getElementById('expenseModal').style.display = 'none';
    currentExpenseId = null;
}

function editExpense(expenseId) {
    // Find the expense data from the table
    const row = document.querySelector(`button[onclick="editExpense(${expenseId})"]`).closest('tr');
    const cells = row.querySelectorAll('td');

    // Get expense data - this is a simplified version
    // In a real app, you'd fetch the full expense data via API
    currentExpenseId = expenseId;
    document.getElementById('modalTitle').textContent = 'Edit Expense';
    document.getElementById('expenseId').value = expenseId;

    // You would need to fetch the full expense data here
    // For now, we'll use a simplified approach
    showNotification('Edit functionality requires additional API call', 'info');

    document.getElementById('expenseModal').style.display = 'block';
}

function saveExpense() {
    const form = document.getElementById('expenseForm');
    const formData = new FormData(form);
    const data = {};

    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }

    const isEdit = currentExpenseId !== null;
    const endpoint = isEdit ? 'api/update_expense.php' : 'api/add_expense.php';
    const method = isEdit ? 'PUT' : 'POST';

    if (isEdit) {
        data.id = currentExpenseId;
    }

    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;

    fetch(endpoint, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.error || 'An error occurred');
                });
            }
            return response.json();
        })
        .then(response => {
            if (response.success) {
                showNotification(isEdit ? 'Expense updated successfully' : 'Expense added successfully');
                closeExpenseModal();
                loadDashboardStats();
                loadTransactions();
            }
        })
        .catch(error => {
            console.error('Error saving expense:', error);
            showNotification(error.message, 'error');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
}

function deleteExpense(expenseId) {
    if (!confirm('Are you sure you want to delete this expense?')) {
        return;
    }

    API.delete(`api/delete_expense.php?id=${expenseId}`)
        .then(response => {
            if (response.success) {
                showNotification('Expense deleted successfully');
                loadDashboardStats();
                loadTransactions();
            }
        })
        .catch(error => {
            console.error('Error deleting expense:', error);
            showNotification(error.message, 'error');
        });
}

function loadCategories() {
    API.get('api/get_categories.php')
        .then(response => {
            if (response.success) {
                updateCategorySelects(response.data);
            } else {
                console.error('Failed to load categories:', response.error);
            }
        })
        .catch(error => {
            console.error('Error loading categories:', error);
        });
}


// Close modal when clicking outside
window.addEventListener('click', function (e) {
    const modal = document.getElementById('expenseModal');
    if (e.target === modal) {
        closeExpenseModal();
    }
});