<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$candidate_id = isset($_POST['candidate_id']) ? (int)$_POST['candidate_id'] : 0;
if ($candidate_id <= 0) { json_response(['error' => 'candidate_id is required'], 400); }

// Ensure candidate exists
$stmt = $mysqli->prepare('SELECT id FROM candidates WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $candidate_id);
$stmt->execute();
$stmt->bind_result($cid);
if (!$stmt->fetch()) { $stmt->close(); json_response(['error' => 'Candidate not found'], 404); }
$stmt->close();

$balance = get_vote_balance();
if ($balance <= 0) { json_response(['error' => 'No vote balance. Please pay first.'], 400); }

// Consume all remaining balance as qty
$qty = consume_all_balance();

$stmt2 = $mysqli->prepare('INSERT INTO votes (candidate_id, qty) VALUES (?, ?)');
$stmt2->bind_param('ii', $candidate_id, $qty);
if (!$stmt2->execute()) {
    // Restore balance on failure
    add_vote_balance($qty);
    json_response(['error' => 'Failed to store vote'], 500);
}
$stmt2->close();

json_response(['message' => 'Vote recorded', 'candidate_id' => $candidate_id, 'qty' => $qty]);


