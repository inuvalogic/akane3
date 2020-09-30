<?php

namespace Akane\Helper;

use Mailgun\Mailgun;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailHelper extends \Akane\Core\Base
{
    public function mailgun($to, $subject, $html)
    {
        if (!empty(MAILGUN_KEY) && !empty(MAILGUN_DOMAIN))
        {
            try {
                $mg = Mailgun::create(MAILGUN_KEY);
                $domain = MAILGUN_DOMAIN;
                $params = array(
                    'from'    => MAIL_FROM_NAME.'<'.MAIL_FROM.'>',
                    'to'      => $to,
                    'subject' => $subject,
                    'html'    => $html
                );

                $sent = $mg->messages()->send($domain, $params);
                
                if (MAIL_LOGS == true)
                {
                    $email_logs = array(
                        'sent_time' => date("Y-m-d H:i:s"),
                        'sent_to' => $to,
                        'subject' => $subject,
                        'message' => $html,
                        'sent_response' => serialize($sent),
                    );

                    $this->emaillogsModel->insert($email_logs);
                }
                return true;
            } catch (Exception $e) {
                if (MAIL_DEBUG == true){
                    return array('status' => false, 'error' => "Message could not be sent", 'mailgun_response' => $sent);
                } else {
                    return false;
                }
            }
        } else {
            if (MAIL_DEBUG == true){
                return array('status' => false,'message' => 'MAILGUN_KEY & MAILGUN_DOMAIN need to be configured');
            } else {
                return false;
            }
        }
    }

    public function smtp($to, $subject, $html)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();                        
            $mail->Host       = MAIL_SMTP_HOSTNAME;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_SMTP_USERNAME;
            $mail->Password   = MAIL_SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_SMTP_PORT;

            //Recipients
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = strip_tags($html);

            $sent = $mail->send();
            
            if (MAIL_LOGS == true)
            {
                $email_logs = array(
                    'sent_time' => date("Y-m-d H:i:s"),
                    'sent_to' => $to,
                    'subject' => $subject,
                    'message' => $html,
                    'sent_response' => serialize($sent),
                );

                $this->emaillogsModel->insert($email_logs);
            }

            return true;
        } catch (Exception $e) {
            if (MAIL_DEBUG == true){
                return array('status' => false, 'error' => "Message could not be sent. Mailer Error: ".$mail->ErrorInfo);
            } else {
                return false;
            }
        }
    }

    public function makeinline($html, $css)
    {
        $cssToInlineStyles = new CssToInlineStyles();

        return $cssToInlineStyles->convert(
            $html,
            $css
        );
    }
}