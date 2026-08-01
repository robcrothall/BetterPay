<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/upload.php';
session_start();
auth_required();
$userId = current_user_id();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/profile_personal.php');
}

$tax = trim($_POST['tax_number'] ?? '');
$notes = trim($_POST['notes'] ?? '');

// create profile
$stmt = db()->prepare('INSERT INTO profiles (user_id, profile_type, is_published, created_by, updated_by) VALUES (:user_id, :type, 0, :created_by, :updated_by)');
$stmt->execute([':user_id' => $userId, ':type' => 'personal', ':created_by' => $userId, ':updated_by' => $userId]);
$profileId = (int) db()->lastInsertId();

// insert personal client details
$stmt = db()->prepare('INSERT INTO personal_client (profile_id, tax_number, notes) VALUES (:profile_id, :tax_number, :notes)');
$stmt->execute([':profile_id' => $profileId, ':tax_number' => $tax, ':notes' => $notes]);

// handle uploaded doc
if (!empty($_FILES['document0']) && $_FILES['document0']['error'] === UPLOAD_ERR_OK) {
    save_uploaded_file('document0', $profileId, 'personal_doc', $userId);
}

// audit and archive
log_audit_event('profiles', $profileId, 'insert', '', null, json_encode(['type' => 'personal', 'tax_number' => $tax]), $userId);
archive_change('profiles', $profileId, '__full_record__', null, json_encode(['type' => 'personal', 'tax_number' => $tax]), $userId);

set_flash('Personal profile created', 'success');
redirect('/v4/page/profile.php');
