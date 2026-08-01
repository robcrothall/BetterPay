<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/register.php');
}

$first = trim($_POST['first_name'] ?? '');
$surname = trim($_POST['surname'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['phone_mobile'] ?? '');
$password = $_POST['password'] ?? '';

if ($first === '' || $surname === '' || $username === '' || $password === '') {
    set_flash('Please complete required fields', 'error');
    redirect('/v4/page/register.php');
}

// Simple uniqueness check
$stmt = db()->prepare('SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1');
$stmt->execute([':u' => $username, ':e' => $email]);
if ($stmt->fetch()) {
    set_flash('Username or email already in use', 'error');
    redirect('/v4/page/register.php');
}

$data = [
    'username' => $username,
    'email' => $email,
    'password_hash' => hash_password($password),
    'first_name' => $first,
    'surname' => $surname,
    'mobile' => $mobile,
    'landline' => null,
    'created_by' => 0,
];

try {
    $id = create_user($data);
    set_flash('Registration successful. Please log in.', 'success');
    redirect('/v4/page/login.php');
} catch (PDOException $e) {
    $message = $e->getMessage();
    error_log('Registration failed: ' . $message);
    if (stripos($message, 'duplicate') !== false || stripos($message, 'unique') !== false) {
        set_flash('Registration failed: username or email already exists.', 'error');
    } elseif (stripos($message, 'foreign key') !== false) {
        set_flash('Registration failed: related data is missing or invalid.', 'error');
    } else {
        set_flash('Registration failed: a database error occurred. Please try again later.', 'error');
    }
    redirect('/v4/page/register.php');
}
