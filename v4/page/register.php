<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
$pageTitle = 'Register';
include __DIR__ . '/../inc/header.php';
?>
  <h2>Register</h2>
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
<?php include __DIR__ . '/../inc/footer.php'; ?>
