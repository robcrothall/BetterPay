<?php
// Create an admin user from command-line: php create_admin.php admin@example.com password
require_once __DIR__ . '/../inc/constants.php';
require_once __DIR__ . '/../inc/functions.php';

if (PHP_SAPI !== 'cli') {
    echo "Run from command line\n";
    exit(1);
}

$email = $argv[1] ?? null;
$password = $argv[2] ?? null;
if (!$email || !$password) {
    echo "Usage: php create_admin.php email password\n";
    exit(1);
}

$data = [
    'username' => $email,
    'email' => $email,
    'password_hash' => hash_password($password),
    'first_name' => 'Admin',
    'surname' => 'User',
    'display_name' => 'Administrator',
    'phone_mobile' => '',
    'role' => 'administrator',
    'created_by' => 0,
];

$stmt = db()->prepare('SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1');
$stmt->execute([':username' => $email, ':email' => $email]);
$existing = $stmt->fetch();
if ($existing) {
    $stmt = db()->prepare('UPDATE users SET password_hash = :password_hash, role = :role, updated_at = NOW() WHERE id = :id');
    $stmt->execute([':password_hash' => hash_password($password), ':role' => 'administrator', ':id' => $existing['id']]);
    echo "Updated existing admin user id: {$existing['id']}\n";
    exit(0);
}

$id = create_user($data);
echo "Created admin user id: $id\n";
