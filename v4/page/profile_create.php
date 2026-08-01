<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
auth_required();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Create profile - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">
  <h2>Create Profile</h2>
  <p>Select profile type:</p>
  <ul>
    <li><a href="/v4/page/profile_business.php">Business Client</a></li>
    <li><a href="/v4/page/profile_personal.php">Personal Client</a></li>
    <li><a href="/v4/page/profile_work_seeker.php">Work Seeker</a></li>
  </ul>
</body>
</html>
