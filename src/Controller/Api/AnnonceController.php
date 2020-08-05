<?php

namespace App\Controller\Api;

use App\Entity\Annonce;
use App\Service\Validator;
use App\Service\DateFormatter;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\HandleMutationDestination;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="api")
 */
class AnnonceController extends AbstractController
{
    /**
     * Récupération de la liste des annonces
     * 
     * @Route("/ads", name="_ads", methods={"GET"})
     */
    public function list(AnnonceRepository $annonceRepository, Request $request, PaginatorInterface $paginator)
    {
        //On récupère les requettes de tris des annonces et ont les stockes dans des variables
        $searchGrade = $request->query->get('grade');
        $searchCity = $request->query->get('city');
        $searchCityMutation = $request->query->get('cityMutation');
        $searchDepartmentMutation = $request->query->get('departmentMutation');
        $searchRegionMutation = $request->query->get('regionMutation');
        $pagination = $request->query->get('pagination');
        
        // On récupères les annonces qui sont publiées
        $ads = $annonceRepository->searchAds($searchGrade, $searchCity, $searchCityMutation, $searchDepartmentMutation, $searchRegionMutation);

        foreach ($ads as $ad) {
            DateFormatter::format($ad, ['createdAt', 'updatedAt']);
        }

        if ($pagination == 'true') {
            $adsWithPagination = $paginator->paginate(
                $ads, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                6
            );

            $dataInfos = $adsWithPagination->getPaginationData();
            $currentPage = $dataInfos['current'] ?? null;
            $prev = $dataInfos['previous'] ?? null;
            $next = $dataInfos['next'] ?? null;

            return $this->json(['ads' => $adsWithPagination, 'currentPage' => $currentPage, 'prev' => $prev, 'next' => $next], Response::HTTP_OK, [], ['groups' => 'ads_get']);
        }

        return $this->json(['ads' => $ads], Response::HTTP_OK, [], ['groups' => 'ads_get']);
    }

    /**
     * Ajouter une annonce
     * 
     * @Route("/ads", name="_new_ads", methods={"POST"})
     */
    public function new(Request $request, Validator $validator, EntityManagerInterface $em, SerializerInterface $serializer, HandleMutationDestination $handleMutationDestination)
    { 
        $annonce = $serializer->deserialize($request->getContent(), Annonce::class, 'json');

        // On récupère l'utilisateur
        $user = $annonce->getUser();

        // Si le User existe en BDD et possède déjà une annonce, on envoi un message d'erreur
        if($user !== null && $user->getAnnonce()) {
            return $this->json([
                'message' => 'Une seule annonce autorisé par utilisateur.'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user->setAnnonce($annonce);

        // 2.1 On recupère les données concernant l'annonce depuis la requête
        $data = json_decode($request->getContent());
        
        $handleMutationDestination->handleCity($data, $user);
        $mutationCity = $handleMutationDestination->handleMutationCity($data, $user);
        if ($mutationCity !== true) { return $mutationCity; }

        // L'entité est-elle valide ?
        $errors = $validator->validate($annonce);
        if ($errors !== null) return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);

        $em->persist($annonce);
        $em->flush();

        return $this->json(['message' => 'L\'annonce a bien été créee.'], Response::HTTP_CREATED);
    }

    /**
     * Modifier une annonce
     * 
     * @Route("/ads/{id<\d+>}", name="_edit_ads", methods={"PUT"})
     */
    public function edit(Annonce $annonce = null, Request $request, SerializerInterface $serializer, Validator $validator, EntityManagerInterface $em, HandleMutationDestination $handleMutationDestination)
    {
        // 1. On vérifie si l'annonce existe ?
        if($annonce === null) {
            // On renvoie un message d'erreur en json pour qu'elle soit exploitable par le front
            return $this->json([
                'message' => 'Annonce inexistante.'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        // On vérifie si l'utilisateur est autorisé a modifié l'annonce
        if(!$this->isGranted('EDIT', $annonce)) {
            return $this->json(['message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
        }

        // 2. On récupère le contenu JSON de la requete que l'on deserialize sur l'entité
        $annonce = $serializer->deserialize($request->getContent(), Annonce::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $annonce]);
        // 2.1 On recupère les données concernant l'annonce depuis la requête
        $data = json_decode($request->getContent());
        
        // Si la ville est définie on gère son contenu
        if(isset($data->city)) {
            $handleMutationDestination->handleCity($data, $annonce->getUser());
        }
        
        $mutationCity = $handleMutationDestination->handleMutationCity($data, $annonce->getUser());
        if ($mutationCity !== true) { return $mutationCity; }
        
        // 3. On vérifie la validité des données
        $errors = $validator->validate($annonce);
        if ($errors !== null) return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        // 5. On flush
        $em->flush();

        return $this->json(['message' => 'Modification effectuée.'], Response::HTTP_OK);
    }

    /**
     * Supprimer une annonce
     * 
     * @Route("/ads/{id<\d+>}", name="_delete_ads", methods={"DELETE"})
     */
    public function delete(Annonce $annonce = null)
    {
        if($annonce === null) {
            // On renvoie un message d'erreur en json pour qu'elle soit exploitable par le front
            return $this->json([
                'message' => 'Annonce inexistante.'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        // On vérifie si l'utilisateur est autorisé a modifié l'annonce
        if(!$this->isGranted('REMOVE', $annonce)) {
            return $this->json(['message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($annonce);
        $entityManager->flush();

        return $this->json(['message' => 'Annonce supprimée.'], Response::HTTP_OK);       
    }

    /**
     * Afficher une annonce
     * 
     * @Route("/ads/{id<\d+>}", name="_show_ads", methods={"GET"})
     */
    public function show(Annonce $annonce = null)
    {
        if($annonce === null) {
            // On renvoie un message d'erreur en json pour qu'elle soit exploitable par le front
            return $this->json([
                'message' => 'Annonce inexistante.'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        // Si l'annonce a le status non publiée
        if($annonce->getStatus() == 2) {
            // On vérifie que le user est bien le propriétaire de l'annonce
            if(!$this->isGranted('SHOW', $annonce)) {
                return $this->json(['message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
            }
        }

        DateFormatter::format($annonce, ['createdAt', 'updatedAt']);

        return $this->json($annonce, Response::HTTP_OK, [], ['groups' => 'ads_get']);
    }
}
