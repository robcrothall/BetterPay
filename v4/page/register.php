<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body class="w3-container">
  <h2>Register</h2>
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="w3-panel w3-pale-blue"><?php echo htmlspecialchars($_SESSION['flash']['message']); unset($_SESSION['flash']); ?></div>
  <?php endif; ?>
  <form method="post" action="/v4/page/register_handler.php" class="w3-container w3-card-4 w3-padding">
    <label>First name</label>
    <input class="w3-input" type="text" name="first_name" required>
    <label>Surname</label>
    <input class="w3-input" type="text" name="surname" required>
    <label>Username (email recommended)</label>
    <input class="w3-input" type="text" name="username" required>
    <label>Password</label>
    <input class="w3-input" type="password" name="password" required>
    <label>Email</label>
    <input class="w3-input" type="email" name="email">
    <label>Mobile phone</label>
    <input class="w3-input" type="text" name="phone_mobile">
    <p>
      <button class="w3-button w3-green" type="submit">Register</button>
      <a class="w3-button w3-light-grey" href="/v4/page/login.php">Back to login</a>
    </p>
  </form>
</body>
</html>
