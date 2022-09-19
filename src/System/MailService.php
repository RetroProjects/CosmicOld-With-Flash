<?php
namespace Cosmic\System;

use Cosmic\App\Config;
use Cosmic\App\Models\Core;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;

class MailService
{
    public static function send($subject, $body, $to) {
        $transport = Transport::fromDsn(Core::settings()->mailer_dsn);
        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from(Core::settings()->mailer_from)
            ->to($to)
            ->subject('Mail from: ' . Config::site['sitename'])
            ->html($body);

        $mailer->send($email);
    }
}
