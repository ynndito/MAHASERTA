<?php
require_once __DIR__ . '/db.php';

header('Cache-Control: no-store');

$data = [];

$res = $mysqli->query('SELECT c.id, c.name, c.photo, COALESCE(SUM(v.qty),0) AS votes FROM candidates c LEFT JOIN votes v ON v.candidate_id = c.id GROUP BY c.id ORDER BY c.id ASC');
while ($row = $res->fetch_assoc()) {
    $data[] = [
        'id' => (int)$row['id'],
        'name' => $row['name'],
        'photo' => $row['photo'],
        'votes' => (int)$row['votes']
    ];
}
$res->close();

$total = 0;
foreach ($data as $it) { $total += $it['votes']; }

json_response([
    'total' => $total,
    'candidates' => $data
]);


