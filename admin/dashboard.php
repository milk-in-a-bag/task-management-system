<?php
require_once '../includes/auth.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}
?>

<h1>Admin Dashboard</h1>
<p>Welcome, <?php echo $_SESSION['user']['name']; ?></p>

<ul>
    <li><a href="register.php">Register New User</a></li>
    <li><a href="users.php">Manage Users</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>
