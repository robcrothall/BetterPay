<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
auth_required();
$userId = current_user_id();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Timesheet - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">
  <h2>Submit timesheet entry</h2>
  <form method="post" action="/v4/page/timesheet_handler.php" class="w3-container w3-card-4 w3-padding">
    <label>Profile ID (employee)</label>
    <input class="w3-input" name="profile_id" required>
    <label>Date worked</label>
    <input class="w3-input" name="date_worked" type="date" required>
    <label>Normal hours</label>
    <input class="w3-input" name="normal_hours" type="number" step="0.25">
    <label>Overtime hours</label>
    <input class="w3-input" name="overtime_hours" type="number" step="0.25">
    <p><button class="w3-button w3-green">Submit timesheet</button></p>
  </form>
</body>
</html>
