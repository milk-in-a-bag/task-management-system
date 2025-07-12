<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $role]);

    header("Location: users.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register User</title>
  <link rel="stylesheet" href="../assests/admin-register.css">
</head>
<body>
  <div class="container">
    <h2>Register New User</h2>

    <form method="POST" action="">
      <label>Name:</label>
      <input type="text" name="name" required>

      <label>Email:</label>
      <input type="email" name="email" required><br>

      <label>Password:</label>
      <input type="password" name="password" required><br>

      <label>Role:</label>
      <select name="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select><br><br>

      <button type="submit" name="register">Register</button>
    </form>

    <br>
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>
