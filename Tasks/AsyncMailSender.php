<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class AsyncMailSender{
    private $sendFrom;

    private $sendTo;

    private $body;

    private $subject;

    public function __construct(string $sendFrom, string $sendTo, string $body, string $subject)
    {
        $this->sendFrom = $sendFrom;
        $this->sendTo = $sendTo;
        $this->body = $body;
        $this->subject = $subject;
    }
    public function run()
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom($this->sendFrom, "TexturePackPE");
            $mail->addAddress($this->sendTo);     // Add a recipient

            // Content
            $mail->isHTML(false);                                  // Set email format to HTML
            $mail->Subject = $this->subject;
            $mail->Body    = $this->body;
            $mail->AltBody = htmlspecialchars($this->body);

            $mail->send();
        } catch (Exception $e) {
        }
    }
}