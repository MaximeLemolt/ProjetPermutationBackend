<?php

namespace App\EventListener;

use App\Entity\TokenBlacklist;
use App\Repository\TokenBlacklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTDecodedListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    private $em;

    private $tokenBlacklistRepository;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, TokenBlacklistRepository $tokenBlacklistRepository)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->tokenBlacklistRepository = $tokenBlacklistRepository;
    }

    /**
     * @param JWTDecodedEvent $event
     *
     * @return void
     */

    public function onJWTDecoded(JWTDecodedEvent $event)
    {

        $request = $this->requestStack->getCurrentRequest();

        $requestUri = $request->getRequestUri();

        // On récupère le token en retirant le Bearer
        $token = str_replace('Bearer ', '', $request->headers->get('authorization'));
        $blacklistedToken = $this->tokenBlacklistRepository->findOneBy(['token' => $token]);

        // On vérifie à chaque décodage du token qu'il n'est pas invalidé
        if($blacklistedToken !== null) {
            $event->markAsInvalid();
        }

        if($requestUri == '/api/logoutSuccess') {
            $tokenToBlacklist = new TokenBlacklist();
            $tokenToBlacklist->setToken($token);
            $expiration = new \Datetime();
            $expiration = $expiration->setTimestamp($event->getPayload()['exp']);
            $tokenToBlacklist->setExpiration($expiration);

            $this->em->persist($tokenToBlacklist);
            $this->em->flush();
        }
    }
}