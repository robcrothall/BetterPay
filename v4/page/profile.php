<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
auth_required();
$user = current_user_record();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Profile - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">
  <h2>Your profile</h2>
  <p>Username: <?php echo htmlspecialchars($user['username'] ?? ''); ?></p>
  <p>Name: <?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['surname'] ?? '')); ?></p>
  <p><a class="w3-button w3-blue" href="/v4/page/login.php">Logon page</a></p>
</body>
</html>
