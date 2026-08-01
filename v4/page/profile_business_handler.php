<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/upload.php';
session_start();
auth_required();
$userId = current_user_id();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/profile_business.php');
}

$business_name = trim($_POST['business_name'] ?? '');
$reg = trim($_POST['registration_number'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['company_phone'] ?? '');
$tax = trim($_POST['tax_number'] ?? '');

if ($business_name === '') {
    set_flash('Business name required', 'error');
    redirect('/v4/page/profile_business.php');
}

// create profile
$stmt = db()->prepare('INSERT INTO profiles (user_id, profile_type, is_published, created_by, updated_by) VALUES (:user_id, :type, 0, :created_by, :updated_by)');
$stmt->execute([':user_id' => $userId, ':type' => 'business', ':created_by' => $userId, ':updated_by' => $userId]);
$profileId = (int) db()->lastInsertId();

// insert business details
$stmt = db()->prepare('INSERT INTO business_client (profile_id, business_name, registration_number, address, company_phone, tax_number, notes) VALUES (:profile_id, :business_name, :registration_number, :address, :company_phone, :tax_number, :notes)');
$stmt->execute([':profile_id' => $profileId, ':business_name' => $business_name, ':registration_number' => $reg, ':address' => $address, ':company_phone' => $phone, ':tax_number' => $tax, ':notes' => null]);

// handle uploaded docs
if (!empty($_FILES['document0']) && $_FILES['document0']['error'] === UPLOAD_ERR_OK) {
    save_uploaded_file('document0', $profileId, 'logo', $userId);
}

// audit and archive the profile and business details
log_audit_event('profiles', $profileId, 'insert', '', null, json_encode(['type' => 'business', 'business_name' => $business_name]), $userId);
archive_change('profiles', $profileId, '__full_record__', null, json_encode(['type' => 'business', 'business_name' => $business_name]), $userId);

log_audit_event('business_client', $profileId, 'insert', '', null, json_encode(['business_name' => $business_name, 'registration_number' => $reg]), $userId);
archive_change('business_client', $profileId, '__full_record__', null, json_encode(['business_name' => $business_name, 'registration_number' => $reg]), $userId);

set_flash('Business profile created', 'success');
redirect('/v4/page/profile.php');
