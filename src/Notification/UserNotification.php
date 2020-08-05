<?php

namespace App\Notification;

use App\Entity\User;
use Twig\Environment;
use App\Entity\Contact;

class UserNotification
{
    /**
     * @var Environnement
     */
    private $renderer;
    
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }
    public function notify(User $user)
    {
        $message = (new \Swift_Message('Email de confirmation d\'inscription'))
        ->setFrom('admin@jeveuxpermuter.com')
        ->setTo($user->getEmail())
        ->setBody(
            $this->renderer->render(
                'emails/registration.html.twig',
                ['user' => $user]
            ),
            'text/html'
        );

        $this->mailer->send($message);

    }
    public function notifyAlert($userMatch, User $user)
    {
        $message = (new \Swift_Message('Email d\'alerte : correspondance de mutation'))
        ->setFrom('admin@jeveuxpermuter.com')
        ->setTo($userMatch->getEmail())
        ->setBody(
            $this->renderer->render(
                'emails/alertMutation.html.twig',
                ['user' => $userMatch,
                'newUser' => $user
                ]
            ),
            'text/html'
        );

        $this->mailer->send($message);
    }
    
    public function resetPassword($userEmail, $token)
    {
        $message = (new \Swift_Message('Email d\'alerte : correspondance de mutation'))
        ->setFrom('admin@jeveuxpermuter.com')
        ->setTo($userEmail)
        ->setBody(
            $this->renderer->render(
                'emails/reset_password.html.twig',
                ['emailUSer' => $userEmail,
                'token' => $token
                ]
            ),
            'text/html'
        );

        $this->mailer->send($message);
    }
    public function contact(Contact $contact)
    {
        $message = (new \Swift_Message('Email de contact : '.$contact->getSubject()))
        ->setFrom($contact->getEmail())
        ->setTo('contact@jeveuxpermuter.com')
        ->setBody(
            $this->renderer->render(
                'emails/contact.html.twig',
                ['contact' => $contact]
            ),
            'text/html'
        );

        $this->mailer->send($message);

    }
}