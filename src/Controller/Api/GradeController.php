<?php

namespace App\Controller\Api;

use App\Repository\GradeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="api")
 */
class GradeController extends AbstractController
{
    /**
     * @Route("/grades", name="_grades", methods={"GET"})
     */
    public function list(GradeRepository $gradeRepository, Request $request)
    {
        // On récupère les requettes de tris des grade et ont les stockes dans des variables
        $search = $request->query->get('search');
        
        // On récupères les annonces qui sont publiées
        $grades = $gradeRepository->findByNameAndCode($search);

        return $this->json(['grades' => $grades], Response::HTTP_OK, [], ['groups' => 'grades_get']);
    }
}