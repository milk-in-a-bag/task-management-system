<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}

$id = $_GET['id'] ?? null;
if (!$id) die("Task ID not provided");

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) die("Task not found");

$users_stmt = $pdo->query("SELECT id, name FROM users ORDER BY name");
$users = $users_stmt->fetchAll();

if (isset($_POST['update_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];
    $assigned_to = $_POST['assigned_to'];

    $update = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, deadline = ?, status = ?, assigned_to = ? WHERE id = ?");
    $update->execute([$title, $description, $deadline, $status, $assigned_to, $id]);

    header("Location: tasks.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Task</title>
  <link rel="stylesheet" href="../assests/edit-task.css">
</head>
<body>
  <div class="container">
    <h2>Edit Task</h2>

    <form method="POST">
      <label>Title:</label>
      <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>

      <label>Description:</label>
      <textarea name="description" rows="4" required><?= htmlspecialchars($task['description']) ?></textarea>

      <label>Deadline:</label>
      <input type="date" name="deadline" value="<?= $task['deadline'] ?>" required>

      <label>Status:</label>
      <select name="status">
        <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
        <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
      </select>

      <label>Assign to:</label>
      <select name="assigned_to" required>
        <?php foreach ($users as $user): ?>
          <option value="<?= $user['id'] ?>" <?= $user['id'] == $task['assigned_to'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($user['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button type="submit" name="update_task">Update Task</button>
    </form>

    <div class="back-link">
      <a href="tasks.php">← Back to Tasks</a>
    </div>
  </div>
</body>
</html>
