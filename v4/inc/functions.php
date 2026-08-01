<?php
/**
 * Program: functions.php
 *
 * Shared helper functions for the BetterPay site.
 */
require_once __DIR__ . '/constants.php';

/*
 * BetterPay - Shared helper functions
 *
 * Licensed under the MIT license. See ../LICENSE for details.
 */

function auth_required(): void
{
    if (!is_logged_in()) {
        // Redirect to the v4 login page when authentication is required
        redirect('/v4/page/login.php');
    }
}

function clean_input(string $data): string
{
    if ($data === null) {
        return '';
    }
    $value = trim((string) $data);
    $value = stripslashes($value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function clear_flash(): void
{
    unset($_SESSION['flash']);
}

function create_or_update_id_type(string $name, ?string $description = null, ?int $id = null): int
{
    if ($id !== null) {
        $stmt = db()->prepare('UPDATE id_type SET name = :name, description = :description WHERE id = :id');
        $stmt->execute([':name' => $name, ':description' => $description, ':id' => $id]);
        return $id;
    }

    $stmt = db()->prepare('INSERT INTO id_type (name, description) VALUES (:name, :description)');
    $stmt->execute([':name' => $name, ':description' => $description]);
    return (int) db()->lastInsertId();
}

function create_or_update_title(string $name, ?string $description = null, ?int $id = null): int
{
    if ($id !== null) {
        $stmt = db()->prepare('UPDATE title SET name = :name, description = :description WHERE id = :id');
        $stmt->execute([':name' => $name, ':description' => $description, ':id' => $id]);
        return $id;
    }

    $stmt = db()->prepare('INSERT INTO title (name, description) VALUES (:name, :description)');
    $stmt->execute([':name' => $name, ':description' => $description]);
    return (int) db()->lastInsertId();
}

function get_or_create_id_type(string $name): int
{
    $stmt = db()->prepare('SELECT id FROM id_type WHERE name = :name LIMIT 1');
    $stmt->execute([':name' => $name]);
    $row = $stmt->fetch();
    if ($row) {
        return (int) $row['id'];
    }

    return create_or_update_id_type($name);
}

function create_or_update_user_identity(int $userId, int $idTypeId, string $idNumber): void
{
    $stmt = db()->prepare('INSERT INTO user_identity (user_id, id_type_id, id_number) VALUES (:user_id, :id_type_id, :id_number) ON DUPLICATE KEY UPDATE id_number = VALUES(id_number)');
    $stmt->execute([
        ':user_id' => $userId,
        ':id_type_id' => $idTypeId,
        ':id_number' => $idNumber,
    ]);
}

function current_user_record(): array
{
    return get_user_by_id(current_user_id());
}

function get_user_by_id(int $userId): array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $userId]);
    return $stmt->fetch() ?: [];
}

function current_user_id(): int
{
    return (int) ($_SESSION['user']['id'] ?? 0);
}

function is_user_in_role(int $userId, string $roleName): bool
{
    $stmt = db()->prepare('SELECT 1 FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :user_id AND r.name = :role_name LIMIT 1');
    $stmt->execute([':user_id' => $userId, ':role_name' => $roleName]);
    return (bool) $stmt->fetchColumn();
}

function is_administrator(): bool
{
    if (!is_logged_in()) {
        return false;
    }
    return is_user_in_role(current_user_id(), 'administrator');
}

function update_user_profile(int $userId, array $data): void
{
    $fields = [
        'first_name' => $data['first_name'] ?? null,
        'surname' => $data['surname'] ?? null,
        'given_name' => $data['given_name'] ?? null,
        'username' => $data['username'] ?? null,
        'email' => $data['email'] ?? null,
        'mobile' => $data['mobile'] ?? null,
        'landline' => $data['landline'] ?? null,
        'updated_by' => $data['updated_by'] ?? current_user_id(),
    ];

    $sql = 'UPDATE users SET first_name = :first_name, surname = :surname, given_name = :given_name, username = :username, email = :email, mobile = :mobile, landline = :landline, updated_by = :updated_by, updated_at = NOW()';
    if (!empty($data['password_hash'])) {
        $sql .= ', password_hash = :password_hash';
        $fields['password_hash'] = $data['password_hash'];
    }
    $sql .= ' WHERE id = :id';
    $fields['id'] = $userId;

    $stmt = db()->prepare($sql);
    $stmt->execute($fields);
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('mysql:host=' . SERVER . ';dbname=' . DATABASE . ';charset=utf8mb4', USERNAME, PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function delete_id_type(int $id): void
{
    $stmt = db()->prepare('DELETE FROM id_type WHERE id = :id');
    $stmt->execute([':id' => $id]);
}

function delete_title(int $id): void
{
    $stmt = db()->prepare('DELETE FROM title WHERE id = :id');
    $stmt->execute([':id' => $id]);
}

function delete_user_identity(int $userId, int $idTypeId): void
{
    $stmt = db()->prepare('DELETE FROM user_identity WHERE user_id = :user_id AND id_type_id = :id_type_id');
    $stmt->execute([
        ':user_id' => $userId,
        ':id_type_id' => $idTypeId,
    ]);
}

function format_datetime(?string $value): string
{
    if (empty($value)) {
        return '';
    }
    return date('Y-m-d H:i', strtotime($value));
}

function get_id_types(): array
{
    $stmt = db()->prepare('SELECT id, name FROM id_type ORDER BY name');
    $stmt->execute();
    return $stmt->fetchAll() ?: [];
}

function get_titles(): array
{
    $stmt = db()->prepare('SELECT id, name FROM title ORDER BY name');
    $stmt->execute();
    return $stmt->fetchAll() ?: [];
}

function get_user_identities(int $userId): array
{
    $stmt = db()->prepare('SELECT ui.id, ui.id_type_id, ui.id_number, it.name AS id_type_name FROM user_identity ui LEFT JOIN id_type it ON it.id = ui.id_type_id WHERE ui.user_id = :user_id ORDER BY it.name');
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetchAll() ?: [];
}

function get_user_identity(int $userId): array
{
    $stmt = db()->prepare('SELECT ui.id, ui.id_type_id, ui.id_number, it.name AS id_type_name FROM user_identity ui LEFT JOIN id_type it ON it.id = ui.id_type_id WHERE ui.user_id = :user_id LIMIT 1');
    $stmt->execute([':user_id' => $userId]);
    return $stmt->fetch() ?: [];
}

function get_all_users(): array
{
    $stmt = db()->prepare('SELECT u.id, u.username, u.email, u.first_name, u.surname, u.given_name, u.mobile, u.landline, u.created_at, u.updated_at, (SELECT CONCAT(it.name, ": ", ui.id_number) FROM user_identity ui JOIN id_type it ON ui.id_type_id = it.id WHERE ui.user_id = u.id LIMIT 1) AS identity FROM users u ORDER BY u.username');
    $stmt->execute();
    return $stmt->fetchAll() ?: [];
}

function hash_password(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user']['id']);
}

function log_audit_event(string $table_name, int $row_id, string $action, string $field_name, ?string $old_value, ?string $new_value, int $user_id = 0): void
{
    $stmt = db()->prepare('INSERT INTO audit_log (table_name, row_id, action, field_name, old_value, new_value, user_id) VALUES (:table_name, :row_id, :action, :field_name, :old_value, :new_value, :user_id)');
    $stmt->execute([
        ':table_name' => $table_name,
        ':row_id' => $row_id,
        ':action' => $action,
        ':field_name' => $field_name,
        ':old_value' => $old_value,
        ':new_value' => $new_value,
        ':user_id' => $user_id,
    ]);
}

function redirect(string $path): void
{
    if (!headers_sent()) {
        header('Location: ' . $path);
        exit;
    }

    echo '<!doctype html><html><head>';
    echo '<meta http-equiv="refresh" content="0;url=' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '">';
    echo '<title>Redirecting</title></head><body>';
    echo 'Redirecting to <a href="' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($path, ENT_QUOTES, 'UTF-8') . '</a>.';
    echo '</body></html>';
    exit;
}

function set_flash(string $message, string $type = 'info'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function set_user_title(int $userId, ?int $titleId): void
{
    $stmt = db()->prepare('UPDATE users SET title_id = :title_id WHERE id = :id');
    $stmt->execute([':title_id' => $titleId, ':id' => $userId]);
}

function validate_date(string $value): bool
{
    if ($value === '') {
        return false;
    }
    $date = DateTime::createFromFormat('Y-m-d', $value);
    return $date && $date->format('Y-m-d') === $value;
}

function verify_password(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}
/**
 * Generate a secure random token (hex encoded)
 */
function generate_token(int $length = 64): string
{
    return bin2hex(random_bytes((int)($length / 2)));
}

/**
 * Archive a change for diagnostics (archive_changes table)
 */
function archive_change(string $table_name, int $row_id, string $field_name, ?string $old_value, ?string $new_value, int $user_id = 0): void
{
    $stmt = db()->prepare('INSERT INTO archive_changes (table_name, row_id, field_name, old_value, new_value, changed_by) VALUES (:table_name, :row_id, :field_name, :old_value, :new_value, :changed_by)');
    $stmt->execute([
        ':table_name' => $table_name,
        ':row_id' => $row_id,
        ':field_name' => $field_name,
        ':old_value' => $old_value,
        ':new_value' => $new_value,
        ':changed_by' => $user_id,
    ]);
}

/**
 * Create a new user and record audit/archive entries.
 * Expects: username, password_hash, first_name, surname, email, mobile, landline, given_name
 */
function create_user(array $data): int
{
    $sql = 'INSERT INTO users (username, email, password_hash, first_name, surname, given_name, mobile, landline, title_id, status, unsubscribe_email, role, created_by, updated_by) VALUES (:username, :email, :password_hash, :first_name, :surname, :given_name, :mobile, :landline, :title_id, :status, :unsubscribe_email, :role, :created_by, :updated_by)';
    $stmt = db()->prepare($sql);
    $stmt->execute([
        ':username' => $data['username'],
        ':email' => $data['email'] ?? null,
        ':password_hash' => $data['password_hash'],
        ':first_name' => $data['first_name'] ?? null,
        ':surname' => $data['surname'] ?? null,
        ':given_name' => $data['given_name'] ?? null,
        ':mobile' => $data['mobile'] ?? $data['phone_mobile'] ?? null,
        ':landline' => $data['landline'] ?? $data['phone_landline'] ?? null,
        ':title_id' => $data['title_id'] ?? null,
        ':status' => $data['status'] ?? 'active',
        ':unsubscribe_email' => $data['unsubscribe_email'] ?? 0,
        ':role' => $data['role'] ?? null,
        ':created_by' => $data['created_by'] ?? 0,
        ':updated_by' => $data['updated_by'] ?? 0,
    ]);
    $id = (int) db()->lastInsertId();

    try {
        // Write an audit entry and archive the initial record for diagnostics
        log_audit_event('users', $id, 'insert', '', null, json_encode($data), $data['created_by'] ?? 0);
        archive_change('users', $id, '__full_record__', null, json_encode($data), $data['created_by'] ?? 0);
    } catch (PDOException $e) {
        error_log('User audit/archive failure: ' . $e->getMessage());
    }

    return $id;
}

// End of file - helper functions added
