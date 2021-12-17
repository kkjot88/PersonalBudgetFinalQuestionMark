<?php

namespace App;

use App\Config;
use Mailgun\Mailgun;

class Mail {
    public static function send($to, $subject, $text, $html) {
        $mg = Mailgun::create(Config::MAILGUN_API_KEY);
        $mg->messages()->send(Config::MAILGUN_DOMAIN, array(
            'from'	=> 'your-sender@example.com',
            'to'	=> $to,
            'subject' => $subject,
            'text'	=> $text,
            'html' => $html
        ));
    }
}