<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars(CLIENT_NAME); ?><?php echo isset($pageTitle) ? ' - ' . htmlspecialchars($pageTitle) : ''; ?></title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <style>
    body { font-family: Arial, sans-serif; }
    .page-wrap { max-width: 1100px; margin: 0 auto; padding: 16px; }
    .topbar { background: #0d47a1; color: white; padding: 12px 16px; }
    .nav a { margin-right: 12px; }
  </style>
</head>
<?php
require_once __DIR__ . '/functions.php';
?>
<body>
<div class="page-wrap">
  <div class="w3-card-4">
    <div class="topbar">
      <h2 style="margin:0;">BetterPay Services</h2>
      <p style="margin:4px 0 0;">Domestic worker registration, client matching, and payroll support</p>
    </div>
    <div class="w3-bar w3-light-grey nav" style="padding:8px 12px;">
      <a class="w3-bar-item" href="/v1/index.php">Home</a>
      <a class="w3-bar-item" href="/v1/page/login.php">Logon</a>
      <a class="w3-bar-item" href="/v1/page/register.php">Register</a>
      <?php if (is_logged_in()): ?>
        <a class="w3-bar-item" href="/v1/page/dashboard.php">Dashboard</a>
        <a class="w3-bar-item" href="/v1/page/profile.php">Profile</a>
        <a class="w3-bar-item" href="/v1/page/availability.php">Availability</a>
        <a class="w3-bar-item" href="/v1/page/logout.php">Logout</a>
      <?php endif; ?>
    </div>
    <div class="w3-container w3-padding-large">
      <?php if (!empty($_SESSION['flash'])): ?>
        <div class="w3-panel w3-<?php echo htmlspecialchars($_SESSION['flash']['type']); ?> w3-padding">
          <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
        </div>
      <?php endif; ?>
