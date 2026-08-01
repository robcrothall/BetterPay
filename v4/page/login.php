<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);
if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
    error_log('Unexpected login page path: ' . $_SERVER['PHP_SELF']);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="w3-container">
  <h2>Logon</h2>
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="w3-panel w3-pale-blue"><?php echo htmlspecialchars($_SESSION['flash']['message']); unset($_SESSION['flash']); ?></div>
  <?php endif; ?>
  <form method="post" action="/v4/page/login_handler.php" class="w3-container w3-card-4 w3-padding">
    <label>Username or email</label>
    <input class="w3-input" type="text" name="username" required>
    <label>Password</label>
    <input class="w3-input" type="password" name="password" required>
    <p>
      <button class="w3-button w3-blue" type="submit">Logon</button>
      <a class="w3-button w3-light-grey" href="/v4/page/register.php">Register</a>
    </p>
  </form>
</body>
</html>
