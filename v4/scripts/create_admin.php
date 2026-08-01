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
    'created_by' => 0,
];

$id = create_user($data);
// Add role entry if roles table exists
try {
    $stmt = db()->prepare('INSERT IGNORE INTO roles (name) VALUES ("administrator")');
    $stmt->execute();
    $roleStmt = db()->prepare('SELECT id FROM roles WHERE name = "administrator" LIMIT 1');
    $roleStmt->execute();
    $role = $roleStmt->fetch();
    if ($role) {
        $stmt = db()->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)');
        $stmt->execute([':user_id' => $id, ':role_id' => $role['id']]);
    }
} catch (Exception $e) {
    // ignore if roles not present
}

echo "Created admin user id: $id\n";
