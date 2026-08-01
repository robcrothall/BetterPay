<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/mail.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/password_reset_request.php');
}

$user = trim($_POST['user'] ?? '');
if ($user === '') {
    set_flash('Please enter your email or username', 'error');
    redirect('/v4/page/password_reset_request.php');
}

$stmt = db()->prepare('SELECT id, email, username FROM users WHERE username = :u OR email = :u LIMIT 1');
$stmt->execute([':u' => $user]);
$row = $stmt->fetch();

if (!$row) {
    // Don't reveal whether the user exists
    set_flash('If the account exists a reset link has been sent', 'info');
    redirect('/v4/page/login.php');
}

$token = generate_token(64);
$expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour

$upd = db()->prepare('UPDATE users SET password_reset_token = :token, password_reset_expires = :expires WHERE id = :id');
$upd->execute([':token' => $token, ':expires' => $expires, ':id' => $row['id']]);

$link = WEBSITE . '/v4/page/password_reset.php?token=' . urlencode($token);
$body = "<p>Hello,</p><p>Use this link to reset your password (valid 1 hour): <a href=\"{$link}\">Reset password</a></p>";

// send email if email present
if (!empty($row['email'])) {
    send_mail($row['email'], 'Password reset for BetterPay', $body, INFO_EMAIL);
}

set_flash('If the account exists a reset link has been sent', 'info');
redirect('/v4/page/login.php');
