<?php
include 'time_settings.php';
include 'authenticator.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

function getMailerInstance()
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'information@dollar-ex.com';
        $mail->Password = 'M12345@@STAR'; // كلمة مرور البريد
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
    } catch (Exception $e) {
        throw new Exception("Failed to configure mailer: " . $e->getMessage());
    }
    return $mail;
}
