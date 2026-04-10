<?php
require_once __DIR__ . '/functions.php';

function send_mail_message(string $to, string $toName, string $subject, string $html, string $altText = ''): bool
{
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoload)) {
        return false;
    }

    require_once $autoload;

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = env_value('MAIL_HOST', 'smtp.office365.com');
        $mail->SMTPAuth = true;
        $mail->Username = env_value('MAIL_USERNAME', ADMIN_EMAIL);
        $mail->Password = env_value('MAIL_PASSWORD', '');
        $mail->SMTPSecure = env_value('MAIL_ENCRYPTION', 'tls');
        $mail->Port = (int) env_value('MAIL_PORT', '587');
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(env_value('MAIL_FROM_ADDRESS', ADMIN_EMAIL), env_value('MAIL_FROM_NAME', APP_NAME));
        $mail->addAddress($to, $toName);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->AltBody = $altText ?: strip_tags($html);
        $mail->send();
        return true;
    } catch (Throwable $e) {
        return false;
    }
}
