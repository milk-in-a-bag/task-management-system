<?php
require_once 'includes/db.php';
require_once 'config.php';

$name = ADMIN_NAME;
$email = ADMIN_EMAIL;
$password = password_hash(ADMIN_PASSWORD, PASSWORD_DEFAULT);
$role = "admin";

// Check if the admin already exists to avoid duplicates
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetchColumn() == 0) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $role]);
    echo "✅ Admin created.";
} else {
    echo "ℹ️ Admin already exists.";
}
