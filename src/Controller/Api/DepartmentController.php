<?php

namespace App\Controller\Api;

use App\Repository\DepartmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="api")
 */
class DepartmentController extends AbstractController
{
    /**
     * @Route("/departments", name="_departments", methods={"GET"})
     */
    public function list(DepartmentRepository $departmentRepository, Request $request)
    {
        // On récupère les requettes de tris des department et ont les stockes dans des variables
        $search = $request->query->get('search');
        
        // On récupères les annonces qui sont publiées
        $departments = $departmentRepository->findByNameAndCode($search);

        return $this->json(['departments' => $departments], Response::HTTP_OK, [], ['groups' => 'departments_get']);
    }
}