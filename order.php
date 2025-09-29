<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;
$qty = max(1, min(1000, $qty));
$amount = $qty * 3000; // Rp

$order_id = 'ORD-' . strtoupper(bin2hex(random_bytes(6)));

$stmt = $mysqli->prepare('INSERT INTO orders (user_id, order_id, qty_votes, amount, status) VALUES (NULL, ?, ?, ?, "pending")');
$stmt->bind_param('sii', $order_id, $qty, $amount);
if (!$stmt->execute()) {
    json_response(['error' => 'Failed to create order'], 500);
}
$stmt->close();

$_SESSION['current_order_id'] = $order_id;

json_response([
    'order_id' => $order_id,
    'qty_votes' => $qty,
    'amount' => $amount,
    'status' => 'pending'
]);


