<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Task Management System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to the Task Management System</h1>
        <p>Manage tasks, track progress, and stay productive.</p>

        <?php if (isset($_SESSION['user'])): ?>
            <p>You are logged in as <?php echo $_SESSION['user']['name']; ?> (<?php echo $_SESSION['user']['role']; ?>)</p>
            <a href="<?php echo $_SESSION['user']['role'] === 'admin' ? 'admin/dashboard.php' : 'user/dashboard.php'; ?>">Go to Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</body>
</html>
