<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/upload.php';
session_start();
auth_required();
$userId = current_user_id();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/profile_work_seeker.php');
}

$dob = trim($_POST['dob'] ?? null);
$address = trim($_POST['address'] ?? null);
$job_title = trim($_POST['job_title'] ?? null);
$account_number = trim($_POST['account_number'] ?? null);

// create profile
$stmt = db()->prepare('INSERT INTO profiles (user_id, profile_type, is_published, created_by, updated_by) VALUES (:user_id, :type, 0, :created_by, :updated_by)');
$stmt->execute([':user_id' => $userId, ':type' => 'work_seeker', ':created_by' => $userId, ':updated_by' => $userId]);
$profileId = (int) db()->lastInsertId();

// insert work_seeker details
$stmt = db()->prepare('INSERT INTO work_seeker (profile_id, dob, address, job_title, job_title_other, created_at) VALUES (:profile_id, :dob, :address, :job_title, :job_title_other, NOW())');
$stmt->execute([':profile_id' => $profileId, ':dob' => $dob ?: null, ':address' => $address, ':job_title' => $job_title, ':job_title_other' => null]);

// bank account
if ($account_number) {
    $stmt = db()->prepare('INSERT INTO bank_accounts (profile_id, account_number) VALUES (:profile_id, :account_number)');
    $stmt->execute([':profile_id' => $profileId, ':account_number' => $account_number]);
}

// handle documents
if (!empty($_FILES['id_image']) && $_FILES['id_image']['error'] === UPLOAD_ERR_OK) {
    save_uploaded_file('id_image', $profileId, 'id_image', $userId);
}
if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    save_uploaded_file('photo', $profileId, 'photo', $userId);
}
if (!empty($_FILES['police_clearance']) && $_FILES['police_clearance']['error'] === UPLOAD_ERR_OK) {
    save_uploaded_file('police_clearance', $profileId, 'police_clearance', $userId);
}

// audit and archive
log_audit_event('profiles', $profileId, 'insert', '', null, json_encode(['type' => 'work_seeker', 'job_title' => $job_title]), $userId);
archive_change('profiles', $profileId, '__full_record__', null, json_encode(['type' => 'work_seeker', 'job_title' => $job_title]), $userId);

log_audit_event('work_seeker', $profileId, 'insert', '', null, json_encode(['dob' => $dob, 'job_title' => $job_title]), $userId);
archive_change('work_seeker', $profileId, '__full_record__', null, json_encode(['dob' => $dob, 'job_title' => $job_title]), $userId);

set_flash('Work seeker profile created', 'success');
redirect('/v4/page/profile.php');
