<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/register.php');
}

$first = trim($_POST['first_name'] ?? '');
$givenName = trim($_POST['given_name'] ?? '');
$surname = trim($_POST['surname'] ?? '');
$username = trim($_POST['username'] ?? '');
$idType = trim($_POST['id_type'] ?? '');
$idNumber = trim($_POST['id_number'] ?? '');
$email = trim($_POST['email'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$landline = trim($_POST['landline'] ?? '');
$password = $_POST['password'] ?? '';

if ($first === '' || $surname === '' || $username === '' || $idType === '' || $idNumber === '' || $password === '') {
    set_flash('Please complete required fields', 'error');
    redirect('/v4/page/register.php');
}

if ($password !== ($_POST['password_confirm'] ?? '')) {
    set_flash('Passwords do not match', 'error');
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
    'given_name' => $givenName,
    'mobile' => $mobile,
    'landline' => $landline,
    'created_by' => 0,
];

try {
    $id = create_user($data);
    $idTypeId = get_or_create_id_type($idType);
    create_or_update_user_identity($id, $idTypeId, $idNumber);
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
