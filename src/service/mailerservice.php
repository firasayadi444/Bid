<?php


namespace App\service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class mailerservice
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(
        $to ,
        $content = 'winner ',
        $subject = 'Time for Symfony Mailer! '
    ): void {
        $email = (new Email())
            ->from('saiedeya1@gmail.com')
            ->to($to)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }
}
