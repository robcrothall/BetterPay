<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
auth_required();
$user = current_user_record();
$pageTitle = 'Profile';
include __DIR__ . '/../inc/header.php';
?>
  <h2>Your profile</h2>
  <p>Username: <?php echo htmlspecialchars($user['username'] ?? ''); ?></p>
  <p>Name: <?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['surname'] ?? '')); ?></p>
  <p><a class="w3-button w3-blue" href="/v4/page/login.php">Logon page</a></p>
<?php include __DIR__ . '/../inc/footer.php'; ?>
