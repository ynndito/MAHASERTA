<?php
require_once __DIR__ . '/db.php';

// Simulate QRIS callback to mark order as paid and credit votes to session balance
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$order_id = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';
if ($order_id === '') { json_response(['error' => 'order_id is required'], 400); }

// Load order
$stmt = $mysqli->prepare('SELECT id, status, qty_votes FROM orders WHERE order_id = ? LIMIT 1');
$stmt->bind_param('s', $order_id);
$stmt->execute();
$stmt->bind_result($id, $status, $qty_votes);
if (!$stmt->fetch()) {
    $stmt->close();
    json_response(['error' => 'Order not found'], 404);
}
$stmt->close();

if ($status === 'paid') {
    // Already paid; still ensure session has credit at least
    add_vote_balance($qty_votes);
    json_response(['message' => 'Already paid', 'balance' => get_vote_balance(), 'order_id' => $order_id, 'status' => 'paid']);
}

// Mark as paid
$stmt2 = $mysqli->prepare('UPDATE orders SET status = "paid" WHERE order_id = ?');
$stmt2->bind_param('s', $order_id);
if (!$stmt2->execute()) {
    $stmt2->close();
    json_response(['error' => 'Failed to update order'], 500);
}
$stmt2->close();

// Credit votes to session
add_vote_balance($qty_votes);

json_response(['message' => 'Payment confirmed', 'order_id' => $order_id, 'status' => 'paid', 'credited_votes' => $qty_votes, 'balance' => get_vote_balance()]);


