<?php
session_start();
$pageTitle = 'About';
include 'includes/header.php';
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-info-circle"></i> About Expense Tracker
            </h1>
        </div>

             <div style="line-height: 1.8; color: #374151;">
            <p style="font-size: 1.1rem; margin-bottom: 2rem;">
                Welcome to our comprehensive expense tracking application! This tool is designed to help you take
                control of your personal finances by providing an easy-to-use platform for monitoring your income and
                expenses.
            </p>

            <h2 style="color:rgb(51, 64, 87); margin-bottom: 1rem;">
                <i class="fas fa-bullseye"></i> Our Mission
            </h2>
            <p style="margin-bottom: 2rem;">
                We believe that financial awareness is the first step toward financial freedom. Our mission is to
                provide a simple yet powerful tool that helps individuals track their spending patterns, identify areas
                for improvement, and make informed financial decisions.
            </p>

            <h2 style="color: #2d3748; margin-bottom: 1rem;">
                <i class="fas fa-star"></i> Key Features
            </h2>
            <div
                style="display: grid; grid-template-columns:  repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
                 <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #667eea;">
                   <h3 style="color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-plus-circle" style="color: #667eea;"></i> Easy Transaction Entry
                   </h3> 
                    <p>Quickly add income and expenses with our intuitive form interface. Set dates, amounts,
                        categories, and descriptions with ease.</p>
                    
                    </div>

                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #10b981;">
                    <h3 style="color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-chart-pie" style="color: #10b981;"></i> Visual Analytics
                    </h3>
                    
                    <p>Beautiful charts and graphs show your spending patterns, monthly trends, and category breakdowns
                        at a glance.</p>
                    
                   
                     </div>

                     <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #f59e0b;">
                   <h3 style="color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-tags" style="color: #f59e0b;"></i> Smart Categorization
                    </h3> 
                     <td><p>Organize expenses with predefined categories like Food, Transport, Utilities, and more for better
                        insights.</p> 
                    </div>

                     <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #ef4444;">
                     <h3 style="color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-edit" style="color: #ef4444;"></i> Full Management
                    </h3> 
                    <p>Edit, update, or delete any transaction. Keep your financial records accurate and up-to-date.</p>
                    
                  
                     </div>
            
            </div>

            <h2 style="color: #2d3748; margin-bottom: 1rem;">
                <i class="fas fa-screwdriver-wrench"></i> Technology Stack
            </h2>
            <p style="margin-bottom: 1rem;">This application is built using modern web technologies to ensure
                reliability, security, and performance:</p>
            <ul style="margin-left: 2rem; margin-bottom: 2rem;">
                <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript, Chart.js for visualizations</li>
                <li><strong>Backend:</strong> PHP for server-side logic and API endpoints</li>
                <li><strong>Database:</strong> MySQL for secure data storage</li>
                <li><strong>Security:</strong> Password hashing, input sanitization, and SQL injection protection</li>
                <li><strong>Design:</strong> Responsive design that works on all devices</li>
            </ul>

            <h2 style="color: #2d3748; margin-bottom: 1rem;">
                <i class="fas fa-shield-alt"></i> Security & Privacy
            </h2>
            <p style="margin-bottom: 2rem;">
                We take your financial privacy seriously. All passwords are securely hashed, user sessions are
                protected, and your data is stored with industry-standard security measures. Only you have access to
                your financial information.
            </p>

            <h2 style="color: #2d3748; margin-bottom: 1rem;">
                <i class="fas fa-rocket"></i> Getting Started
            </h2>
            <p style="margin-bottom: 1rem;">Ready to take control of your finances? Here's how to get started:</p>
            <ol style="margin-left: 2rem; margin-bottom: 2rem;">
                <li>Create your free account by clicking the "Register" button</li>
                <li>Log in to access your personal dashboard</li>
                <li>Start adding your income and expenses</li>
                <li>Use the charts and analytics to understand your spending patterns</li>
                <li>Make informed decisions about your financial future</li>
            </ol>

            <h2 style="color: #2d3748; margin-bottom: 1rem;">
                <i class="fas fa-brain"></i>  Developers
            </h2>
            <div
                style="display: grid; grid-template-columns:  repeat(2, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
                 <div style="background:rgb(201, 199, 228); padding: 1.5rem; border-radius: 8px; border-left: 5px solid rgb(48, 50, 56);">
                   <h3 style="color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-user-gear" style="color:rgb(92, 133, 167);"></i> Rumeth Wijethunga
                   </h3> 
                    <p>Focused on PHP and server side logic.Built the login system,session handling,and database connectivity using PHP and MySQL</p>
                    
                    </div>

                    <div style="background: rgb(201, 199, 228); padding: 1.5rem; border-radius: 8px; border-left: 4px solid rgb(48, 50, 56);">
                    <h3 style="color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-user-gear" style="color:rgb(92, 133, 167);"></i> Sachin Perera
                    </h3>
                    
                    <p>Passionate about user experience and responsive design.Handled the layout,styling, and navigation using HTML,CSS and JavaScript to make the site clean and interactive.</p>
                    
                   
                     </div>

                     <div style="background:rgb(201, 199, 228) ; padding: 1.5rem; border-radius: 8px; border-left: 4px solid rgb(48, 50, 56);">
                   <h3 style="color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-user-gear" style="color:rgb(92, 133, 167);"></i> Sanduni Lakshika
                    </h3> 
                     <td><p>Designed and managed the database structure in phpMyAdmin.Ensured secure and organized storage of user data,income and expense records.</p> 
                    </div>

                     <div style="background:rgb(201, 199, 228) ; padding: 1.5rem; border-radius: 8px; border-left: 4px solid rgb(48, 50, 56);">
                     <h3 style="color: #2d3748; margin-bottom: 0.5rem;">
                        <i class="fas fa-user-gear" style="color:rgb(92, 133, 167);"></i> Buddhika Senevirathne
                    </h3> 
                    <p>Developed the core logic of the system using PHP.Handled user authentication,secure form processing and backend validation to ensure smooth data handling between the database and user interface.</p>
                    
                  
                     </div>
            
            </div>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <div
                    style="text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 12px; margin-top: 2rem;">
                    <h3 style="margin-bottom: 1rem;">Ready to Start Your Financial Journey?</h3>
                    <p style="margin-bottom: 1.5rem; opacity: 0.9;">Join thousands of users who have taken control of their
                        finances</p>
                    <a href="register.php" class="btn" style="background: white; color: #667eea; margin-right: 1rem;">
                        <i class="fas fa-user-plus"></i> Create Free Account
                    </a>
                    <a href="login.php" class="btn btn-secondary">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>