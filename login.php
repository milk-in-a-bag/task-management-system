<?php
session_start();

if (isset($_POST['login'])) {
    require_once 'includes/db.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];

        $redirect = ($user['role'] === 'admin') ? 'admin/dashboard.php' : 'user/dashboard.php';
        header("Location: $redirect");
        exit;
    } else {
        $_SESSION['error'] = "Invalid credentials!";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Task Manager</title>
  <link rel="stylesheet" href="assests/login.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <button type="submit" name="login">Login</button>
    </form>
  </div>
</body>
</html>
