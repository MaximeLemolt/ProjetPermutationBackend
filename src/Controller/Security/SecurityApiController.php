<?php

namespace App\Controller\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityApiController extends AbstractController
{
    /**
     * @Route("/api/login_check", name="api_login")
     * @return JsonResponse
     */
    public function api_login(): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse([
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * @Route("/api/logout", name="api_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/api/logoutSuccess", name="api_logout_success", methods={"GET"})
     */
    public function logoutSuccess()
    {
        return $this->json(['message' => 'Déconnexion réussie.'], Response::HTTP_OK);
    }
}
