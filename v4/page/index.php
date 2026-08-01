<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars(CLIENT_NAME); ?> - Home</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="w3-light-grey">
  <div class="w3-container w3-padding-16">
    <div class="w3-card-4 w3-white w3-padding-32" style="max-width:920px; margin:auto;">
      <h1><?php echo htmlspecialchars(CLIENT_NAME); ?></h1>
      <p class="w3-text-grey"><?php echo htmlspecialchars(SYSTEM_NAME); ?></p>
      <?php if (!empty($_SESSION['flash'])): ?>
        <div class="w3-panel w3-pale-blue w3-leftbar w3-border-blue">
          <?php echo htmlspecialchars($_SESSION['flash']['message']); unset($_SESSION['flash']); ?>
        </div>
      <?php endif; ?>
      <?php if (is_logged_in()): ?>
        <div class="w3-panel w3-pale-green w3-leftbar w3-border-green">
          Welcome back, <?php echo htmlspecialchars($_SESSION['user_full_name'] ?? 'member'); ?>.
          <a href="/v4/page/dashboard.php">Go to your dashboard</a>
        </div>
      <?php endif; ?>
      <div class="w3-section">
        <a class="w3-button w3-green w3-margin-right" href="/v4/page/login.php">Logon</a>
        <a class="w3-button w3-blue" href="/v4/page/register.php">Register</a>
      </div>
      <div class="w3-panel w3-light-grey w3-padding-24">
        <h4>BetterPay Services</h4>
        <p>Domestic worker registration, client matching, payroll support and availability tracking for employers and workers.</p>
      </div>
    </div>
  </div>
</body>
</html>
