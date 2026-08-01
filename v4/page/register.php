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
    <label>Other names</label>
    <input class="w3-input" type="text" name="given_name">
    <label>Surname</label>
    <input class="w3-input" type="text" name="surname" required>
    <label>Username</label>
    <input class="w3-input" type="text" name="username" placeholder="Email address or mobile number" required>
    <label>ID Type</label>
    <select class="w3-select" name="id_type" required>
      <option value="">Select ID type</option>
      <option value="ZA ID">ZA ID</option>
      <option value="Other ID">Other ID</option>
      <option value="Passport No">Passport No</option>
    </select>
    <label>ID Number</label>
    <input class="w3-input" type="text" name="id_number" maxlength="50" required>
    <label>Email address</label>
    <input class="w3-input" type="email" name="email">
    <label>Mobile number</label>
    <input class="w3-input" type="text" name="mobile">
    <label>Landline number</label>
    <input class="w3-input" type="text" name="landline">
    <label>Password</label>
    <input class="w3-input" type="password" name="password" required>
    <label>Confirm Password</label>
    <input class="w3-input" type="password" name="password_confirm" required>
    <p>
      <button class="w3-button w3-green" type="submit">Register</button>
      <a class="w3-button w3-light-grey" href="/v4/page/login.php">Back to login</a>
    </p>
  </form>
<?php include __DIR__ . '/../inc/footer.php'; ?>
