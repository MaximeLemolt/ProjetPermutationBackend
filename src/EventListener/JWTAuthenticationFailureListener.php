<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;

class JWTAuthenticationFailureListener
{

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse('Identifiants invalides.', 401);

        $event->setResponse($response);
    }
}