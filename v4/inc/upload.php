<?php
/**
 * Simple file upload helper. Files are stored in /uploads and a DB record is created in documents.
 */
function save_uploaded_file(string $inputName, int $profileId, string $docType = 'generic', int $uploadedBy = 0): ?int
{
    if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $file = $_FILES[$inputName];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safe = bin2hex(random_bytes(8)) . '.' . $ext;
    $target = __DIR__ . '/../uploads/' . $safe;
    if (!move_uploaded_file($file['tmp_name'], $target)) {
        return null;
    }

    $stmt = db()->prepare('INSERT INTO documents (profile_id, uploaded_by, filename, mime_type, doc_type) VALUES (:profile_id, :uploaded_by, :filename, :mime_type, :doc_type)');
    $stmt->execute([
        ':profile_id' => $profileId,
        ':uploaded_by' => $uploadedBy,
        ':filename' => $safe,
        ':mime_type' => $file['type'] ?? 'application/octet-stream',
        ':doc_type' => $docType,
    ]);
    return (int) db()->lastInsertId();
}
