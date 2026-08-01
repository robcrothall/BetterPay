<?php
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';
session_start();
auth_required();

if (!is_administrator()) {
    set_flash('You do not have permission to view this page.', 'error');
    redirect('/v4/page/profile.php');
}

$users = get_all_users();
$pageTitle = 'Manage users';
include __DIR__ . '/../inc/header.php';
?>
  <h2>Manage users</h2>
  <table class="w3-table w3-striped w3-bordered">
    <thead>
      <tr>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>ID</th>
        <th>Updated</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user): ?>
      <tr>
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td><?php echo htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['given_name'] ?? '') . ' ' . ($user['surname'] ?? ''))); ?></td>
        <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($user['mobile'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($user['identity'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($user['updated_at'] ?? $user['created_at'] ?? ''); ?></td>
        <td><a class="w3-button w3-small w3-blue" href="/v4/page/profile.php?user_id=<?php echo (int) $user['id']; ?>">Edit</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php include __DIR__ . '/../inc/footer.php'; ?>
