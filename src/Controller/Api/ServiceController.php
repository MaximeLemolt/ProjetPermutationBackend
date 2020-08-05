<?php

namespace App\Controller\Api;

use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="api")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/services", name="_services", methods={"GET"})
     */
    public function list(ServiceRepository $serviceRepository, Request $request)
    {
        // On récupère les requettes de tris des service et ont les stockes dans des variables
        $search = $request->query->get('search');
        
        // On récupères les annonces qui sont publiées
        $services = $serviceRepository->findByNameAndCode($search);

        return $this->json(['services' => $services], Response::HTTP_OK, [], ['groups' => 'services_get']);
    }
}