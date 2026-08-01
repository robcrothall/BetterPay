<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/login.php');
}

$token = trim($_POST['token'] ?? '');
$password = $_POST['password'] ?? '';

if ($token === '' || $password === '') {
    set_flash('Invalid request', 'error');
    redirect('/v4/page/login.php');
}

$stmt = db()->prepare('SELECT id, password_reset_expires FROM users WHERE password_reset_token = :t LIMIT 1');
$stmt->execute([':t' => $token]);
$row = $stmt->fetch();
if (!$row || strtotime($row['password_reset_expires']) < time()) {
    set_flash('Password reset token is invalid or expired', 'error');
    redirect('/v4/page/login.php');
}

$password_hash = hash_password($password);
$upd = db()->prepare('UPDATE users SET password_hash = :ph, password_reset_token = NULL, password_reset_expires = NULL, updated_at = NOW() WHERE id = :id');
$upd->execute([':ph' => $password_hash, ':id' => $row['id']]);

log_audit_event('users', $row['id'], 'update', 'password_hash', null, '***REDACTED***', $row['id']);
archive_change('users', $row['id'], 'password_hash', null, '***REDACTED***', $row['id']);

set_flash('Password updated — please log in', 'success');
redirect('/v4/page/login.php');
