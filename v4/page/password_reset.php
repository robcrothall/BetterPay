<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();

$token = trim($_GET['token'] ?? '');
if ($token === '') {
    set_flash('Invalid password reset token', 'error');
    redirect('/v4/page/login.php');
}

$stmt = db()->prepare('SELECT id, password_reset_expires FROM users WHERE password_reset_token = :t LIMIT 1');
$stmt->execute([':t' => $token]);
$row = $stmt->fetch();
if (!$row || strtotime($row['password_reset_expires']) < time()) {
    set_flash('Password reset token is invalid or expired', 'error');
    redirect('/v4/page/login.php');
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reset password - BetterPay</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-container">
  <h2>Set a new password</h2>
  <form method="post" action="/v4/page/password_reset_handler.php" class="w3-container w3-card-4 w3-padding">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <label>New password</label>
    <input class="w3-input" type="password" name="password" required>
    <p><button class="w3-button w3-green">Set password</button></p>
  </form>
</body>
</html>
