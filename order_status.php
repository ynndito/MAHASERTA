<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') { json_response(['error' => 'Method not allowed'], 405); }

$order_id = isset($_GET['order_id']) ? trim($_GET['order_id']) : '';
if ($order_id === '') { json_response(['error' => 'order_id is required'], 400); }

$stmt = $mysqli->prepare('SELECT order_id, status, qty_votes, amount, proof_path, claimed FROM orders WHERE order_id = ? LIMIT 1');
$stmt->bind_param('s', $order_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) { json_response(['error' => 'Order not found'], 404); }

json_response([
    'order_id' => $row['order_id'],
    'status' => $row['status'],
    'qty_votes' => (int)$row['qty_votes'],
    'amount' => (int)$row['amount'],
    'proof_path' => $row['proof_path'],
    'claimed' => (int)$row['claimed']
]);


