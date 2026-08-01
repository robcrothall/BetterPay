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
  <title>Work seeker profile - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">
  <h2>Work Seeker Profile</h2>
  <form method="post" action="/v4/page/profile_work_seeker_handler.php" enctype="multipart/form-data" class="w3-container w3-card-4 w3-padding">
    <label>Date of birth</label>
    <input class="w3-input" type="date" name="dob">
    <label>Physical address</label>
    <textarea class="w3-input" name="address"></textarea>
    <label>Job title</label>
    <input class="w3-input" name="job_title">
    <label>Bank account - account number</label>
    <input class="w3-input" name="account_number">
    <label>Upload ID image</label>
    <input type="file" name="id_image">
    <label>Upload photo</label>
    <input type="file" name="photo">
    <label>Upload police clearance</label>
    <input type="file" name="police_clearance">
    <p><button class="w3-button w3-green">Create profile</button></p>
  </form>
</body>
</html>
