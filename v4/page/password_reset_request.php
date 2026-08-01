<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Password reset - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">
  <h2>Request password reset</h2>
  <form method="post" action="/v4/page/password_reset_request_handler.php" class="w3-container w3-card-4 w3-padding">
    <label>Email or username</label>
    <input class="w3-input" type="text" name="user" required>
    <p><button class="w3-button w3-blue">Send reset link</button></p>
  </form>
</body>
</html>
