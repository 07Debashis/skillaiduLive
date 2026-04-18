<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /skilledu/signup.php'); exit; }

$name  = trim($_POST['full_name'] ?? '');
$email = trim(strtolower($_POST['email'] ?? ''));
$pw    = $_POST['password'] ?? '';

if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pw) < 6) {
    flash_set('error', 'Please enter a valid name, email and a password (min 6 chars).');
    header('Location: /skilledu/signup.php'); exit;
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    flash_set('error', 'An account with this email already exists.');
    header('Location: /skilledu/signup.php'); exit;
}

// First user becomes admin
$count = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$role  = $count === 0 ? 'admin' : 'student';

$hash = password_hash($pw, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $hash, $role]);

$_SESSION['user_id']   = (int)$pdo->lastInsertId();
$_SESSION['full_name'] = $name;
$_SESSION['role']      = $role;

header('Location: /skilledu/index.php');
