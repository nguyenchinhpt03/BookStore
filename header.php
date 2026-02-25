<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore Online</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><a href="index.php">Bookstore</a></h1>
            </div>
            <nav>
                <ul>
                    <li class="search-bar-item">
                        <form action="index.php" method="get">
                            <input type="text" name="search" placeholder="Search for books..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </li>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li><a href="admin/index.php">Admin Panel</a></li>
                        <?php endif; ?>
                        <li><a href="order_history.php">Order History</a></li>
                        <li><a href="profile.php">Hi, <?php echo htmlspecialchars($_SESSION['fullname']); ?></a></li>
                        <li><a href="logout.php" id="logout-link">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="main-content">

    <div id="logout-confirm-box" class="message-box-overlay">
        <div class="message-box">
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to log out of your account?</p>
            <div class="message-box-buttons">
                <button id="cancel-logout" class="btn btn-cancel">Cancel</button>
                <a href="logout.php" class="btn btn-confirm">Logout</a>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLink = document.getElementById('logout-link');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(event) {
                event.preventDefault();
                const confirmBox = document.getElementById('logout-confirm-box');
                confirmBox.style.display = 'flex';
                setTimeout(() => confirmBox.classList.add('show'), 10);
            });
        }

        const confirmBox = document.getElementById('logout-confirm-box');
        const cancelBtn = document.getElementById('cancel-logout');
        
        function closeConfirmBox() {
            confirmBox.classList.remove('show');
            setTimeout(() => confirmBox.style.display = 'none', 300);
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeConfirmBox);
        }

        if (confirmBox) {
            confirmBox.addEventListener('click', function(event) {
                if (event.target === confirmBox) {
                    closeConfirmBox();
                }
            });
        }
    });
    </script>