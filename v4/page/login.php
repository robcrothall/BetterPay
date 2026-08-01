<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
$pageTitle = 'Login';
include __DIR__ . '/../inc/header.php';
?>
  <h2>Logon</h2>
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
<?php include __DIR__ . '/../inc/footer.php'; ?>
