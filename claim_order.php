<?php
require_once __DIR__ . '/db.php';

// Claims votes to session if order is approved and not yet claimed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { json_response(['error' => 'Method not allowed'], 405); }

$order_id = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';
if ($order_id === '') { json_response(['error' => 'order_id is required'], 400); }

$stmt = $mysqli->prepare('SELECT status, qty_votes, claimed FROM orders WHERE order_id = ? LIMIT 1');
$stmt->bind_param('s', $order_id);
$stmt->execute();
$stmt->bind_result($status, $qty, $claimed);
if (!$stmt->fetch()) { $stmt->close(); json_response(['error' => 'Order not found'], 404); }
$stmt->close();

if ($status !== 'approved') { json_response(['error' => 'Order not approved yet'], 400); }
if ((int)$claimed === 1) { json_response(['message' => 'Already claimed', 'balance' => get_vote_balance()]); }

// Mark claimed and credit votes
$stmt2 = $mysqli->prepare('UPDATE orders SET claimed = 1 WHERE order_id = ?');
$stmt2->bind_param('s', $order_id);
if (!$stmt2->execute()) { $stmt2->close(); json_response(['error' => 'Failed to mark claimed'], 500); }
$stmt2->close();

add_vote_balance((int)$qty);
json_response(['message' => 'Votes credited', 'credited' => (int)$qty, 'balance' => get_vote_balance()]);


