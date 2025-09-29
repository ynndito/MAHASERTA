<?php
require_once __DIR__ . '/db.php';
require_admin();

$notice = '';
$error = '';

// Handle candidate add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_candidate') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        if (!is_dir(__DIR__ . '/uploads')) { @mkdir(__DIR__ . '/uploads', 0777, true); }
        $fname = 'cand_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = __DIR__ . '/uploads/' . $fname;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
            $photo = 'uploads/' . $fname;
        }
    }
    if ($name !== '') {
        $stmt = $mysqli->prepare('INSERT INTO candidates (name, photo) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $photo);
        if ($stmt->execute()) { $notice = 'Candidate added.'; } else { $error = 'Failed to add candidate.'; }
        $stmt->close();
    } else {
        $error = 'Name is required';
    }
}

// Fetch candidates and totals
$candidates = [];
$res = $mysqli->query('SELECT c.id, c.name, c.photo, COALESCE(SUM(v.qty),0) AS total_votes FROM candidates c LEFT JOIN votes v ON v.candidate_id = c.id GROUP BY c.id ORDER BY c.id ASC');
while ($row = $res->fetch_assoc()) { $candidates[] = $row; }
$res->close();

// Totals
$totalVotes = 0;
$res2 = $mysqli->query('SELECT COALESCE(SUM(qty),0) AS tv FROM votes');
if ($row = $res2->fetch_assoc()) { $totalVotes = (int)$row['tv']; }
$res2->close();

// Approve/Reject orders
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['approve_order','reject_order'], true)) {
    $orderId = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';
    if ($orderId !== '') {
        if ($_POST['action'] === 'approve_order') {
            $stmt = $mysqli->prepare('UPDATE orders SET status = "approved", approved_by = ?, approved_at = NOW() WHERE order_id = ?');
            $stmt->bind_param('is', $_SESSION['admin_id'], $orderId);
        } else {
            $stmt = $mysqli->prepare('UPDATE orders SET status = "rejected", approved_by = ?, approved_at = NOW() WHERE order_id = ?');
            $stmt->bind_param('is', $_SESSION['admin_id'], $orderId);
        }
        $stmt->execute();
        $stmt->close();
        $notice = 'Order updated.';
    }
}

// Load pending/awaiting orders
$orders = [];
$res3 = $mysqli->query('SELECT id, order_id, qty_votes, amount, status, proof_path, created_at FROM orders WHERE status IN ("awaiting_review") ORDER BY created_at DESC');
while ($row = $res3->fetch_assoc()) { $orders[] = $row; }
$res3->close();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-neutral-100">
    <div class="max-w-5xl mx-auto p-4">
      <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        <div class="text-sm">Logged in as <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong> — <a class="text-blue-600" href="logout.php">Logout</a></div>
      </div>

      <?php if ($notice): ?><div class="mb-3 p-3 rounded bg-green-50 text-green-700 border border-green-200 text-sm"><?= htmlspecialchars($notice) ?></div><?php endif; ?>
      <?php if ($error): ?><div class="mb-3 p-3 rounded bg-red-50 text-red-700 border border-red-200 text-sm"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow p-5">
          <h2 class="text-lg font-semibold mb-3">Add Candidate</h2>
          <form method="post" enctype="multipart/form-data" class="space-y-3">
            <input type="hidden" name="action" value="add_candidate" />
            <div>
              <label class="block text-sm font-medium">Name</label>
              <input type="text" name="name" class="mt-1 w-full border rounded px-3 py-2" required />
            </div>
            <div>
              <label class="block text-sm font-medium">Photo</label>
              <input type="file" name="photo" accept="image/*" class="mt-1 w-full" />
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded px-4 py-2" type="submit">Add</button>
          </form>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
          <h2 class="text-lg font-semibold mb-3">Stats</h2>
          <div class="text-sm">Total Votes: <strong><?= number_format($totalVotes) ?></strong></div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow p-5 mt-6">
        <h2 class="text-lg font-semibold mb-3">Candidates</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left border-b">
                <th class="py-2 pr-3">ID</th>
                <th class="py-2 pr-3">Photo</th>
                <th class="py-2 pr-3">Name</th>
                <th class="py-2">Total Votes</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($candidates as $c): ?>
                <tr class="border-b">
                  <td class="py-2 pr-3"><?= (int)$c['id'] ?></td>
                  <td class="py-2 pr-3"><?php if (!empty($c['photo'])): ?><img src="<?= htmlspecialchars($c['photo']) ?>" alt="photo" class="h-10 w-14 object-cover rounded" /><?php endif; ?></td>
                  <td class="py-2 pr-3"><?= htmlspecialchars($c['name']) ?></td>
                  <td class="py-2"><?= (int)$c['total_votes'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow p-5 mt-6">
        <h2 class="text-lg font-semibold mb-3">Orders Awaiting Review</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left border-b">
                <th class="py-2 pr-3">Order ID</th>
                <th class="py-2 pr-3">Qty</th>
                <th class="py-2 pr-3">Amount</th>
                <th class="py-2 pr-3">Proof</th>
                <th class="py-2 pr-3">Status</th>
                <th class="py-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($orders)): ?>
                <tr><td colspan="6" class="py-4 text-neutral-500">No orders awaiting review.</td></tr>
              <?php else: foreach ($orders as $o): ?>
                <tr class="border-b">
                  <td class="py-2 pr-3 font-mono text-xs"><?= htmlspecialchars($o['order_id']) ?></td>
                  <td class="py-2 pr-3"><?= (int)$o['qty_votes'] ?></td>
                  <td class="py-2 pr-3">Rp <?= number_format((int)$o['amount'], 0, ',', '.') ?></td>
                  <td class="py-2 pr-3">
                    <?php if (!empty($o['proof_path'])): ?>
                      <a class="text-blue-600" href="<?= htmlspecialchars($o['proof_path']) ?>" target="_blank">View</a>
                    <?php else: ?>—<?php endif; ?>
                  </td>
                  <td class="py-2 pr-3"><?= htmlspecialchars($o['status']) ?></td>
                  <td class="py-2">
                    <form method="post" class="inline">
                      <input type="hidden" name="action" value="approve_order" />
                      <input type="hidden" name="order_id" value="<?= htmlspecialchars($o['order_id']) ?>" />
                      <button class="px-3 py-1 rounded bg-green-600 text-white" type="submit">Approve</button>
                    </form>
                    <form method="post" class="inline ml-2">
                      <input type="hidden" name="action" value="reject_order" />
                      <input type="hidden" name="order_id" value="<?= htmlspecialchars($o['order_id']) ?>" />
                      <button class="px-3 py-1 rounded bg-red-600 text-white" type="submit">Reject</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
  </html>


