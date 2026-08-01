<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
auth_required();
$userId = current_user_id();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/v4/page/availability.php');
}

$profileId = (int) ($_POST['profile_id'] ?? 0);
$type = $_POST['availability_type'] ?? 'daily';
$day = isset($_POST['day_of_week']) ? (int) $_POST['day_of_week'] : null;
$start = $_POST['start_time'] ?? null;
$end = $_POST['end_time'] ?? null;
$from = $_POST['period_from'] ?? null;
$to = $_POST['period_to'] ?? null;

$stmt = db()->prepare('INSERT INTO availability (profile_id, availability_type, day_of_week, start_time, end_time, period_from, period_to, notes) VALUES (:profile_id, :type, :day, :start, :end, :from, :to, NULL)');
$stmt->execute([':profile_id' => $profileId, ':type' => $type, ':day' => $day, ':start' => $start, ':end' => $end, ':from' => $from, ':to' => $to]);
$id = (int) db()->lastInsertId();

log_audit_event('availability', $id, 'insert', '', null, json_encode(['type' => $type, 'day' => $day, 'start' => $start, 'end' => $end, 'from' => $from, 'to' => $to]), $userId);
archive_change('availability', $id, '__full_record__', null, json_encode(['type' => $type, 'day' => $day, 'start' => $start, 'end' => $end, 'from' => $from, 'to' => $to]), $userId);

set_flash('Availability saved', 'success');
redirect('/v4/page/profile.php');
