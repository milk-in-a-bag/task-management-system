<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}

$id = $_GET['id'] ?? null;
if (!$id) die("No user ID");

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) die("User not found");

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $password, $role, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $id]);
    }

    header("Location: users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link rel="stylesheet" href="../assests/edit-user.css">
</head>
<body>
  <div class="container">
    <h2>Edit User</h2>

    <form method="POST">
      <label>Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

      <label>Password (leave blank to keep current)</label>
      <input type="password" name="password">

      <label>Role</label>
      <select name="role">
        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>

      <button type="submit" name="update">Update User</button>
    </form>

    <a class="back-link" href="users.php">← Back to Users</a>
  </div>
</body>
</html>
