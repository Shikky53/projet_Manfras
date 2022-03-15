<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendConfirmationEmail($user)
    {
        $email = (new TemplatedEmail())
            ->from('noreply@monsite.fr')
            ->to($user->getEmail())
            ->subject('Confirmation de votre compte')
            ->htmlTemplate('email/confirmation_account.html.twig')
            ->context([
                'user' => $user
            ]);
        
        $this->mailer->send($email);

    }

    public function sendContactMail($emailCustomer, $contenu)
    {
        $email = (new TemplatedEmail())
            ->from('contact@monsite.fr')
            ->to($emailCustomer)
            ->subject('Message de contact du client')
            ->htmlTemplate('email/contact.html.twig')
            ->context([
                'content' => $contenu,
                'emailCustomer' => $emailCustomer
            ])
            ;   

            $this->mailer->send($email);
    }
}

?>