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
  <title>Business profile - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">
  <h2>Business Client Profile</h2>
  <form method="post" action="/v4/page/profile_business_handler.php" enctype="multipart/form-data" class="w3-container w3-card-4 w3-padding">
    <label>Business name</label>
    <input class="w3-input" name="business_name" required>
    <label>Registration number</label>
    <input class="w3-input" name="registration_number">
    <label>Physical address</label>
    <textarea class="w3-input" name="address"></textarea>
    <label>Company phone</label>
    <input class="w3-input" name="company_phone">
    <label>Tax number</label>
    <input class="w3-input" name="tax_number">
    <label>Logo / documents</label>
    <input type="file" name="document0">
    <p><button class="w3-button w3-green">Create profile</button></p>
  </form>
</body>
</html>
