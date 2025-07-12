<?php
require_once '../includes/auth.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assests/admin.css">
</head>
<body>
  <div class="container">
    <h1>Admin Dashboard</h1>
    <p class="welcome">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></p>

    <div class="links">
      <a href="register.php">➕ Register New User</a>
      <a href="users.php">👥 Manage Users</a>
      <a href="tasks.php">📝 Manage Tasks</a>
      <a href="../logout.php" class="logout">🚪 Logout</a>
    </div>
  </div>
</body>
</html>
