<?php

namespace App\Controller\Api;

use App\Repository\RegionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="api")
 */
class RegionController extends AbstractController
{
    /**
     * @Route("/regions", name="_regions", methods={"GET"})
     */
    public function list(RegionRepository $regionRepository, Request $request)
    {
        // On récupère les requettes de tris des region et ont les stockes dans des variables
        $search = $request->query->get('search');
        
        // On récupères les annonces qui sont publiées
        $regions = $regionRepository->findByNameAndCode($search);

        return $this->json(['regions' => $regions], Response::HTTP_OK, [], ['groups' => 'regions_get']);
    }
}
