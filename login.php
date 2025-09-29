<?php
require_once __DIR__ . '/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: admin.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $stmt = $mysqli->prepare('SELECT id, password FROM admins WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($admin_id, $hash);
    if ($stmt->fetch()) {
        if (strcasecmp($hash, md5($password)) === 0) {
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_username'] = $username;
            header('Location: admin.php');
            exit;
        }
    }
    $stmt->close();
    $error = 'Invalid username or password';
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-neutral-100">
    <div class="min-h-screen flex items-center justify-center p-4">
      <form method="post" class="w-full max-w-sm bg-white rounded-xl shadow p-6">
        <h1 class="text-xl font-bold mb-4">Admin Login</h1>
        <?php if ($error): ?>
          <div class="mb-3 text-sm text-red-600"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <label class="block text-sm font-medium">Username</label>
        <input type="text" name="username" class="mt-1 mb-3 w-full border rounded px-3 py-2" required />
        <label class="block text-sm font-medium">Password</label>
        <input type="password" name="password" class="mt-1 mb-4 w-full border rounded px-3 py-2" required />
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-4 py-2">Login</button>
      </form>
    </div>
  </body>
</html>


