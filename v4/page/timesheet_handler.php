<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
auth_required();
$userId = current_user_id();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/timesheet.php');
}

$profileId = (int) ($_POST['profile_id'] ?? 0);
$date = $_POST['date_worked'] ?? null;
$normal = (float) ($_POST['normal_hours'] ?? 0);
$overtime = (float) ($_POST['overtime_hours'] ?? 0);

$stmt = db()->prepare('INSERT INTO timesheets (profile_id, period_from, period_to, date_worked, normal_hours, overtime_hours, confirmed, locked_by_admin) VALUES (:profile_id, NULL, NULL, :date_worked, :normal_hours, :overtime_hours, 0, 0)');
$stmt->execute([':profile_id' => $profileId, ':date_worked' => $date, ':normal_hours' => $normal, ':overtime_hours' => $overtime]);
$id = (int) db()->lastInsertId();

log_audit_event('timesheets', $id, 'insert', '', null, json_encode(['date_worked' => $date, 'normal_hours' => $normal, 'overtime_hours' => $overtime]), $userId);
archive_change('timesheets', $id, '__full_record__', null, json_encode(['date_worked' => $date, 'normal_hours' => $normal, 'overtime_hours' => $overtime]), $userId);

set_flash('Timesheet entry saved', 'success');
redirect('/v4/page/profile.php');
