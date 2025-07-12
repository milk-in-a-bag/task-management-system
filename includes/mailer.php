<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';
require_once __DIR__ . '/config.php';

function sendTaskAssignedEmail($to_email, $user_name, $task_title, $deadline) {
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Sender and recipient
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to_email, $user_name);

        // Message content
        $mail->isHTML(false);
        $mail->Subject = "New Task Assigned: $task_title";
        $mail->Body    = "Hi $user_name,\n\n"
                       . "You have been assigned a new task: \"$task_title\".\n"
                       . "Deadline: $deadline\n\n"
                       . "Please log in to your dashboard to view and update the task.\n\n"
                       . "Regards,\nTask Management System";

        $mail->send();
        file_put_contents('../mail_log.txt', "Email sent to $to_email - Task: $task_title\n", FILE_APPEND);
    } catch (Exception $e) {
        file_put_contents('../mail_log.txt', "Email error for $to_email: {$mail->ErrorInfo}\n", FILE_APPEND);
    }
}
