<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /skilledu/login.php'); exit; }

$email = trim(strtolower($_POST['email'] ?? ''));
$pw    = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$u = $stmt->fetch();

if (!$u || !password_verify($pw, $u['password_hash'])) {
    flash_set('error', 'Invalid email or password.');
    header('Location: /skilledu/login.php'); exit;
}

$_SESSION['user_id']   = (int)$u['id'];
$_SESSION['full_name'] = $u['full_name'];
$_SESSION['role']      = $u['role'];

header('Location: ' . ($u['role'] === 'admin' ? '/skilledu/admin/index.php' : '/skilledu/index.php'));
