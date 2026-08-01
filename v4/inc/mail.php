<?php
/**
 * Mail helper. Uses PHPMailer if installed via composer; otherwise falls back to mail().
 */
require_once __DIR__ . '/constants.php';

function send_mail(string $to, string $subject, string $body, string $from = null): bool
{
    // Prefer PHPMailer if available
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require_once __DIR__ . '/../vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            // Attempt to use SMTP if configured in constants
            if (defined('SMTP_HOST') && SMTP_HOST) {
                $mail->isSMTP();
                $mail->Host = SMTP_HOST;
                $mail->SMTPAuth = defined('SMTP_AUTH') ? SMTP_AUTH : true;
                if (defined('SMTP_USERNAME')) $mail->Username = SMTP_USERNAME;
                if (defined('SMTP_PASSWORD')) $mail->Password = SMTP_PASSWORD;
                if (defined('SMTP_SECURE') && SMTP_SECURE) $mail->SMTPSecure = SMTP_SECURE;
                if (defined('SMTP_PORT') && SMTP_PORT) $mail->Port = SMTP_PORT;
            }
            $mail->setFrom($from ?? INFO_EMAIL, CLIENT_NAME);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mail error: ' . $e->getMessage());
            return false;
        }
    }

    // Fallback to PHP mail()
    $headers = "MIME-Version: 1.0\r\n" .
               "Content-type: text/html; charset=UTF-8\r\n";
    if ($from) {
        $headers .= 'From: ' . $from . "\r\n";
    }
    return mail($to, $subject, $body, $headers);
}
