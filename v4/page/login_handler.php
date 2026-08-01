<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/login.php');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    set_flash('Please provide username and password', 'error');
    redirect('/v4/page/login.php');
}

$stmt = db()->prepare('SELECT * FROM users WHERE username = :u OR email = :u LIMIT 1');
$stmt->execute([':u' => $username]);
$user = $stmt->fetch();

if (!$user || !verify_password($password, $user['password_hash'])) {
    set_flash('Invalid credentials', 'error');
    redirect('/v4/page/login.php');
}

// Successful login
$_SESSION['user'] = ['id' => (int) $user['id'], 'username' => $user['username']];
set_flash('Logged in successfully', 'success');
redirect('/v4/page/profile.php');
