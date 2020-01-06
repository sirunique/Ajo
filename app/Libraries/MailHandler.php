<?php
namespace App\Libraries;

use Mail;

class MailHandler
{
    public function sendMail($mail)
    {
        $mailer = Mail::send($mail->template, ['mail' => $mail], function ($m) use ($mail) {
            $m->from($mail->from_email, $mail->from_name);
            $m->to($mail->to_email, $mail->to_name)->subject($mail->subject);

            if (isset($mail->bcc)) {
                $m->bcc($mail->bcc['email'], $mail->bcc['name']);
            }
        });

        return $mailer;
    }

    public function sendMailAttach($mail)
    {
        $mailer = Mail::send($mail->template, ['mail' => $mail], function ($m) use ($mail) {
            $m->from($mail->from_email, $mail->from_name);
            $m->to($mail->to_email, ucwords(strtolower($mail->to_name)))->subject($mail->subject);

            if (isset($mail->bcc)) {
                $m->bcc($mail->bcc['email'], $mail->bcc['name']);
            }

            $m->attach($mail->attachment);
        });

        return $mailer;
    }
}
