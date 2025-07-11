<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Restrict to admin only
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

    $success = "User registered successfully!";
}
?>

<h2>Register New User</h2>

<?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

<form method="POST" action="">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Role:</label><br>
    <select name="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select><br><br>

    <button type="submit" name="register">Register</button>
</form>
