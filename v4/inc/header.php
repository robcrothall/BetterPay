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
    .header-bar { background: #0d47a1; color: white; padding: 12px 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; }
    .header-brand { display: flex; align-items: center; gap: 14px; }
    .header-logo img { display: block; height: 48px; width: auto; }
    .header-title { margin: 0; font-size: 1.4rem; }
    .header-subtitle { margin: 4px 0 0; font-size: 0.95rem; opacity: 0.9; }
    .header-nav { display: flex; flex-wrap: wrap; gap: 10px; }
    .header-nav a { color: white; text-decoration: none; padding: 8px 10px; border-radius: 4px; transition: background 0.2s ease; }
    .header-nav a:hover { background: rgba(255,255,255,0.15); }
    .w3-card-4 { overflow: hidden; }
  </style>
</head>
<?php
require_once __DIR__ . '/functions.php';
?>
<body>
<div class="page-wrap">
  <div class="w3-card-4">
    <div class="header-bar">
      <div class="header-brand">
        <a class="header-logo" href="/v4/index.php">
          <img src="/v4/img/BetterPay_logo.png" alt="BetterPay Services">
        </a>
        <div>
          <h2 class="header-title"><?php echo htmlspecialchars(CLIENT_NAME); ?></h2>
          <p class="header-subtitle"><?php echo htmlspecialchars(SYSTEM_NAME); ?></p>
        </div>
      </div>
      <div class="header-nav">
        <a href="/v4/index.php">Home</a>
        <a href="/v4/page/login.php">Logon</a>
        <a href="/v4/page/register.php">Register</a>
        <?php if (is_logged_in()): ?>
          <a href="/v4/page/dashboard.php">Dashboard</a>
          <a href="/v4/page/profile.php">Profile</a>
          <a href="/v4/page/availability.php">Availability</a>
          <a href="/v4/page/logout.php">Logout</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="w3-container w3-padding-large">
      <?php if (!empty($_SESSION['flash'])): ?>
        <div class="w3-panel w3-<?php echo htmlspecialchars($_SESSION['flash']['type']); ?> w3-padding">
          <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
        </div>
      <?php endif; ?>
