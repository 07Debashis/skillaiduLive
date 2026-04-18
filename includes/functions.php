<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function check_login() {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}

function check_admin() {
    check_login();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: /index.php');
        exit;
    }
}

function is_logged_in() { return !empty($_SESSION['user_id']); }
function is_admin()     { return ($_SESSION['role'] ?? '') === 'admin'; }
function current_user() {
    return [
        'id'   => $_SESSION['user_id']   ?? null,
        'name' => $_SESSION['full_name'] ?? null,
        'role' => $_SESSION['role']      ?? null,
    ];
}

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function slugify($s) {
    $s = strtolower(trim($s));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    return trim($s, '-');
}

function flash_set($k, $v) { $_SESSION['_flash'][$k] = $v; }
function flash_get($k) {
    $v = $_SESSION['_flash'][$k] ?? null;
    unset($_SESSION['_flash'][$k]);
    return $v;
}

/** YouTube URL → embed URL helper */
function youtube_embed($url) {
    if (!$url) return null;
    if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{6,})~', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    return null;
}
