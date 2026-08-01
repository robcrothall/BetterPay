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
  <title>Availability - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">
  <h2>Add Availability</h2>
  <form method="post" action="/v4/page/availability_handler.php" class="w3-container w3-card-4 w3-padding">
    <label>Profile ID</label>
    <input class="w3-input" name="profile_id" required>
    <label>Type</label>
    <select class="w3-select" name="availability_type">
      <option value="daily">Daily</option>
      <option value="period">Period</option>
    </select>
    <label>Day of week (0=Sun..6=Sat)</label>
    <input class="w3-input" name="day_of_week" type="number" min="0" max="6">
    <label>Start time</label>
    <input class="w3-input" name="start_time" type="time">
    <label>End time</label>
    <input class="w3-input" name="end_time" type="time">
    <label>Period from</label>
    <input class="w3-input" name="period_from" type="date">
    <label>Period to</label>
    <input class="w3-input" name="period_to" type="date">
    <p><button class="w3-button w3-green">Save availability</button></p>
  </form>
</body>
</html>
