<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Users</title>
  <link rel="stylesheet" href="../assests/admin-users.css">
</head>
<body>
  <div class="container">

    <?php if (isset($_GET['success'])): ?>
      <p class="success-msg">User registered successfully!</p>
    <?php endif; ?>

    <h1>All Users</h1>

    <p class="nav-links">
      <a href="dashboard.php">← Back to Dashboard</a> |
      <a href="register.php">+ Register New User</a>
    </p>

    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($users as $user): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= ucfirst($user['role']) ?></td>
          <td><?= $user['created_at'] ?></td>
          <td class="actions">
            <a href="edit_user.php?id=<?= $user['id'] ?>">Edit</a> |
            <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

  </div>
</body>
</html>
