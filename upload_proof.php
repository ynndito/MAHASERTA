<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { json_response(['error' => 'Method not allowed'], 405); }

$order_id = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';
if ($order_id === '') { json_response(['error' => 'order_id is required'], 400); }

// Validate order exists
$stmt = $mysqli->prepare('SELECT id FROM orders WHERE order_id = ? LIMIT 1');
$stmt->bind_param('s', $order_id);
$stmt->execute();
$stmt->bind_result($id);
if (!$stmt->fetch()) { $stmt->close(); json_response(['error' => 'Order not found'], 404); }
$stmt->close();

if (!isset($_FILES['proof']) || $_FILES['proof']['error'] !== UPLOAD_ERR_OK) {
    json_response(['error' => 'Proof file is required'], 400);
}

$ext = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));
$allowed = ['jpg','jpeg','png','pdf','webp'];
if (!in_array($ext, $allowed, true)) { json_response(['error' => 'Invalid file type'], 400); }

if (!is_dir(__DIR__ . '/proofs')) { @mkdir(__DIR__ . '/proofs', 0777, true); }
$fname = 'proof_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$dest = __DIR__ . '/proofs/' . $fname;
if (!move_uploaded_file($_FILES['proof']['tmp_name'], $dest)) {
    json_response(['error' => 'Failed to store file'], 500);
}
$path = 'proofs/' . $fname;

// Update order status and proof path
$stmt2 = $mysqli->prepare('UPDATE orders SET proof_path = ?, status = "awaiting_review" WHERE order_id = ?');
$stmt2->bind_param('ss', $path, $order_id);
if (!$stmt2->execute()) { $stmt2->close(); json_response(['error' => 'Failed to update order'], 500); }
$stmt2->close();

json_response(['message' => 'Proof uploaded', 'order_id' => $order_id, 'proof_path' => $path, 'status' => 'awaiting_review']);


