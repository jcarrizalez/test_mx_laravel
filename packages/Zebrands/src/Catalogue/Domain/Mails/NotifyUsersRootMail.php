<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain\Mails;

use Zebrands\Catalogue\Laravel\Mail;

class NotifyUsersRootMail
{
    protected $mail;

    public function __construct(
        Mail $mail
    )
    {
        $this->mail = $mail;
    }
    public function make(string $name, string $email, object $user, object $data) :void
    {   
        $template_raw  = file_get_contents(__DIR__.'/raw/template.txt');
        $template_html = file_get_contents(__DIR__.'/html/template.html');

        $html = __DIR__."/html/notify_users_root.html";
        $raw  = __DIR__."/raw/notify_users_root.txt";

        $html = file_get_contents($html);
        $raw  = file_get_contents($raw);

        $body_raw   = str_replace('{{CONTENT}}', $raw, $template_raw);
        $body_html  = str_replace('{{CONTENT}}', $html, $template_html);
        
        $body_raw   = str_replace('{{ANIO}}', date('Y'), $body_raw);
        $body_html  = str_replace('{{ANIO}}', date('Y'), $body_html);

        #REPLACE DATA
        $body_raw   = str_replace('{{USERNAME}}', $name, $body_raw);
        $body_html  = str_replace('{{USERNAME}}', $name, $body_html);
        $body_raw   = str_replace('{{USER_MODIFY}}', $user->name, $body_raw);
        $body_html  = str_replace('{{USER_MODIFY}}', $user->name, $body_html);
        $body_raw   = str_replace('{{INFO}}', json_encode($data), $body_raw);
        $body_html  = str_replace('{{INFO}}', json_encode($data), $body_html);

        #EVENT MAIL
        $this->mail->send([
            'subject' => 'Producto Modificado',
            'alias' => env('MAIL_FROM_NAME'),
            'from' => env('MAIL_FROM_ADDRESS'),
            'to' => $email,
            'name' => $name,
            'view' => [
                'html' => $body_html,
                'raw'  => $body_raw
            ]
        ]);
    }
}