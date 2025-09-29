<?php
// Basic MySQL connection helper
// Adjust credentials if your XAMPP setup differs

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'votingdb';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([ 'error' => 'Database connection failed', 'detail' => $mysqli->connect_error ]);
    exit;
}

// Set charset
$mysqli->set_charset('utf8mb4');

function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function require_admin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

function get_vote_balance() {
    if (!isset($_SESSION['vote_balance'])) {
        $_SESSION['vote_balance'] = 0;
    }
    return (int)$_SESSION['vote_balance'];
}

function add_vote_balance($qty) {
    $qty = max(0, (int)$qty);
    $_SESSION['vote_balance'] = get_vote_balance() + $qty;
    return $_SESSION['vote_balance'];
}

function consume_all_balance() {
    $bal = get_vote_balance();
    $_SESSION['vote_balance'] = 0;
    return $bal;
}
?>


