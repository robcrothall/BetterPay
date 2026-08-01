<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
auth_required();

$targetUserId = current_user_id();
if (!empty($_GET['user_id']) && is_administrator()) {
    $targetUserId = (int) $_GET['user_id'];
}

$user = get_user_by_id($targetUserId);
$idIdentity = get_user_identity($targetUserId);
$pageTitle = is_administrator() && $targetUserId !== current_user_id() ? 'Edit user profile' : 'Your profile';
include __DIR__ . '/../inc/header.php';
?>
  <h2><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h2>
  <form method="post" action="/v4/page/profile_update_handler.php" class="w3-container w3-card-4 w3-padding">
    <label>First name</label>
    <input class="w3-input" type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
    <label>Other names</label>
    <input class="w3-input" type="text" name="given_name" value="<?php echo htmlspecialchars($user['given_name'] ?? ''); ?>">
    <label>Surname</label>
    <input class="w3-input" type="text" name="surname" value="<?php echo htmlspecialchars($user['surname'] ?? ''); ?>" required>
    <label>Username</label>
    <input class="w3-input" type="text" name="username" placeholder="Email address or mobile number" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
    <label>ID Type</label>
    <select class="w3-select" name="id_type" required>
      <option value="">Select ID type</option>
      <option value="ZA ID" <?php echo (!empty($idIdentity['id_type_name']) && $idIdentity['id_type_name'] === 'ZA ID') ? 'selected' : ''; ?>>ZA ID</option>
      <option value="Other ID" <?php echo (!empty($idIdentity['id_type_name']) && $idIdentity['id_type_name'] === 'Other ID') ? 'selected' : ''; ?>>Other ID</option>
      <option value="Passport No" <?php echo (!empty($idIdentity['id_type_name']) && $idIdentity['id_type_name'] === 'Passport No') ? 'selected' : ''; ?>>Passport No</option>
    </select>
    <label>ID Number</label>
    <input class="w3-input" type="text" name="id_number" maxlength="50" value="<?php echo htmlspecialchars($idIdentity['id_number'] ?? ''); ?>" required>
    <label>Email address</label>
    <input class="w3-input" type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
    <label>Mobile number</label>
    <input class="w3-input" type="text" name="mobile" value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>">
    <label>Landline number</label>
    <input class="w3-input" type="text" name="landline" value="<?php echo htmlspecialchars($user['landline'] ?? ''); ?>">
    <label>Password</label>
    <input class="w3-input" type="password" name="password">
    <label>Confirm Password</label>
    <input class="w3-input" type="password" name="password_confirm">
    <p>
      <button class="w3-button w3-green" type="submit">Update profile</button>
      <a class="w3-button w3-light-grey" href="/v4/page/login.php">Logon page</a>
      <?php if (is_administrator()): ?>
        <a class="w3-button w3-blue" href="/v4/page/user_list.php">Manage users</a>
      <?php endif; ?>
    </p>
    <?php if (is_administrator() && $targetUserId !== current_user_id()): ?>
      <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($targetUserId, ENT_QUOTES, 'UTF-8'); ?>">
    <?php endif; ?>
  </form>
<?php include __DIR__ . '/../inc/footer.php'; ?>
