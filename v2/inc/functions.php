<?php
/**
 * Program: functions.php
 *
 * Shared helper functions for the BetterPay site.
 */
require_once __DIR__ . '/constants.php';

function auth_required(): void
{
    if (!is_logged_in()) {
        redirect('/v1/page/login.php');
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
    if (!is_logged_in()) {
        return [];
    }
    $stmt = db()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => current_user_id()]);
    return $stmt->fetch() ?: [];
}

function current_user_id(): int
{
    return (int) ($_SESSION['user']['id'] ?? 0);
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
    header('Location: ' . $path);
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

/*
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

function clean_input(string $data): string
{
    if ($data === null) {
        return '';
    }
    $value = trim((string) $data);
    $value = stripslashes($value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function hash_password(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user']['id']);
}

function current_user_id(): int
{
    return (int) ($_SESSION['user']['id'] ?? 0);
}

function auth_required(): void
{
    if (!is_logged_in()) {
        redirect('/v1/page/login.php');
    }
}

function set_flash(string $message, string $type = 'info'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function clear_flash(): void
{
    unset($_SESSION['flash']);
}
*/
