<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Persistence;

use Base\Mail as BaseMail;
#use Sas\Billing\Laravel\Mail as BaseMail;

class Mail
{
    protected $mail;

    public function __construct(BaseMail $mail)
    {
        $this->mail = $mail;
    }

    public function send(string $subject, string $email, string $name, string $view_html, string $view_raw) :void
    {
       $this->mail->send([
            'subject' => $subject, 
            'alias' => env('MAIL_FROM_NAME'),
            'from' => env('MAIL_FROM_ADDRESS'),
            'to' => $email,
            'name' => $name,
            'view' => [
                'html' => $view_html,
                'raw'  => $view_raw
            ]
        ]);
    }
}