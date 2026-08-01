<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
auth_required();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/profile.php');
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
$passwordConfirm = $_POST['password_confirm'] ?? '';

if ($first === '' || $surname === '' || $username === '' || $idType === '' || $idNumber === '') {
    set_flash('Please complete required fields', 'error');
    redirect('/v4/page/profile.php');
}

if ($password !== '' && $password !== $passwordConfirm) {
    set_flash('Passwords do not match', 'error');
    redirect('/v4/page/profile.php');
}

$targetUserId = current_user_id();
if (!empty($_POST['user_id']) && is_administrator()) {
    $targetUserId = (int) $_POST['user_id'];
}

try {
    update_user_profile($targetUserId, [
        'first_name' => $first,
        'surname' => $surname,
        'given_name' => $givenName,
        'username' => $username,
        'email' => $email,
        'mobile' => $mobile,
        'landline' => $landline,
        'password_hash' => $password === '' ? null : hash_password($password),
        'updated_by' => current_user_id(),
    ]);

    $idTypeId = get_or_create_id_type($idType);
    create_or_update_user_identity($targetUserId, $idTypeId, $idNumber);

    set_flash('Profile updated successfully', 'success');
} catch (PDOException $e) {
    error_log('Profile update failed: ' . $e->getMessage());
    set_flash('Profile update failed. Please try again.', 'error');
}

redirect('/v4/page/profile.php');
