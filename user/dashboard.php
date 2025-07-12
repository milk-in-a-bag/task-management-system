<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['user']['role'] !== 'user') {
    die("Access denied");
}

$user_id = $_SESSION['user']['id'];

// Handle status update
if (isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    if ($status === 'Completed') {
        $stmt = $pdo->prepare("UPDATE tasks SET status = ?, completed_at = NOW() WHERE id = ? AND assigned_to = ?");
    } else {
        $stmt = $pdo->prepare("UPDATE tasks SET status = ?, completed_at = NULL WHERE id = ? AND assigned_to = ?");
    }

    $stmt->execute([$status, $task_id, $user_id]);

    header("Location: dashboard.php");
    exit;
}

// Fetch user's tasks
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE assigned_to = ? ORDER BY deadline ASC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="../assests/user.css">
</head>
<body>
  <div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
    <a class="logout" href="../logout.php">← Logout</a>

    <h3>Your Tasks</h3>

    <?php if (count($tasks) === 0): ?>
      <p class="no-tasks">🎉 No tasks assigned yet!</p>
    <?php else: ?>
      <table>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>Deadline</th>
          <th>Status</th>
          <th>Update</th>
        </tr>
        <?php foreach ($tasks as $task): ?>
          <tr>
            <td><?= htmlspecialchars($task['title']) ?></td>
            <td><?= nl2br(htmlspecialchars($task['description'])) ?></td>
            <td><?= $task['deadline'] ?></td>
            <td class="status <?= strtolower(str_replace(' ', '-', $task['status'])) ?>">
              <?= $task['status'] ?>
            </td>

            <td>
              <form method="POST">
                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                <select name="status">
                  <option value="Pending" <?= $task['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="In Progress" <?= $task['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                  <option value="Completed" <?= $task['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
                <button type="submit" name="update_status">Update</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
