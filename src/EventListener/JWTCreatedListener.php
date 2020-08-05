<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        // On récupère les données du token
        $payload = $event->getData();

        $user = $event->getUser();

        // On ajoute le pseudo dans les données
        $payload['id'] = $user->getId();
        $payload['pseudo'] = $user->getPseudo();
        if ($user->getAnnonce() !== null) {
            $payload['adId'] = $user->getAnnonce()->getId();
        }

        $event->setData($payload);
        
        $header = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}