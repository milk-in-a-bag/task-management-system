<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}

// Fetch tasks with user info
$stmt = $pdo->query("
    SELECT tasks.*, users.name AS assignee_name 
    FROM tasks 
    LEFT JOIN users ON tasks.assigned_to = users.id 
    ORDER BY tasks.created_at DESC
");
$tasks = $stmt->fetchAll();

// Fetch all users
$users_stmt = $pdo->query("SELECT id, name FROM users ORDER BY name");
$users = $users_stmt->fetchAll();

// Handle form submission
if (isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $assigned_to = $_POST['assigned_to'];

    $insert = $pdo->prepare("INSERT INTO tasks (title, description, deadline, assigned_to) VALUES (?, ?, ?, ?)");
    $insert->execute([$title, $description, $deadline, $assigned_to]);

    // Send email
    $user_stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $user_stmt->execute([$assigned_to]);
    $user = $user_stmt->fetch();

    if ($user) {
        require_once '../includes/mailer.php';
        sendTaskAssignedEmail($user['email'], $user['name'], $title, $deadline);
    }

    header("Location: tasks.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Tasks</title>
  <link rel="stylesheet" href="../assests/admin-tasks.css">
</head>
<body>
  <div class="container">
    <h2>All Tasks</h2>
    <div class="links">
      <a href="dashboard.php">← Back to Dashboard</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>Assigned To</th>
          <th>Deadline</th>
          <th>Status</th>
          <th>Created</th>
          <th>Completed</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tasks as $task): ?>
        <tr>
          <td><?= htmlspecialchars($task['title']) ?></td>
          <td><?= nl2br(htmlspecialchars($task['description'])) ?></td>
          <td><?= htmlspecialchars($task['assignee_name']) ?></td>
          <td><?= $task['deadline'] ?></td>
          <td>
            <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $task['status'])) ?>">
            <?= $task['status'] ?>
            </span>
          </td>

          <td><?= date('Y-m-d H:i', strtotime($task['created_at'])) ?></td>
          <td>
            <?= $task['completed_at'] ? date('Y-m-d H:i', strtotime($task['completed_at'])) : '—' ?>
          </td>

          <td>
            <a href="edit_task.php?id=<?= $task['id'] ?>">Edit</a> |
            <a href="delete_task.php?id=<?= $task['id'] ?>" onclick="return confirm('Delete this task?')">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <hr>

    <h3>Create New Task</h3>
    <form method="POST">
      <label>Title:</label>
      <input type="text" name="title" required>

      <label>Description:</label>
      <textarea name="description" rows="4" required></textarea>

      <label>Deadline:</label>
      <input type="date" name="deadline" required>

      <label>Assign to:</label>
      <select name="assigned_to" required>
        <option value="">-- Select User --</option>
        <?php foreach ($users as $user): ?>
          <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit" name="add_task">Create Task</button>
    </form>
  </div>
</body>
</html>
