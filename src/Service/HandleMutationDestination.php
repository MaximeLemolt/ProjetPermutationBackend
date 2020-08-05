<?php

namespace App\Service;

use App\Entity\City;
use App\Service\CityCoordinates;
use App\Repository\CityRepository;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

;

class HandleMutationDestination
{
    public function __construct(CityRepository $cityRepository, DepartmentRepository $departmentRepository, EntityManagerInterface $em, CityCoordinates $cityCoordinates)
    {
        $this->cityRepository = $cityRepository;
        $this->departmentRepository = $departmentRepository;
        $this->em = $em;
        $this->cityCoordinates = $cityCoordinates;
    }

    /**
     * Handle city data
     * 
     * @param Object $city City data to set in the User ad
     * @param User $user User
     * @return bool
     */
    public function handleCity($dataAnnonce, $user)
    {
        // On vérifie que des données ont été envoyées et que ce n'est pas vide
        if(!empty($dataAnnonce->city)) {
            // Si l'utilisateur ne rempli pas le champs un objet vide est envoyé dans le json
            // Si un objet vide est envoyé on set la City à null
            // (par défaut la ville contient un objet vide à cause du serializer)
            if(empty((array)$dataAnnonce->city)) {
                $user->getAnnonce()->setCity(null);
            // Sinon si des données sont envoyées
            } else {
                // On vérifie si la ville existe déjà en BDD
                if(!empty($dataAnnonce->city->nom)) {
                    $city = $this->cityRepository->findOneBy(['name' => $dataAnnonce->city->nom]);
                } else {
                    $city = $this->cityRepository->find($dataAnnonce->city);
                }
                // Si elle existe, on la set sur l'annonce
                if ($city !== null) {
                    $user->getAnnonce()->setCity($city);
                // sinon on l'ajoute en BDD et on la set sur l'annonce
                } else {
                    $newCity = new City();
                    $newCity->setName($dataAnnonce->city->nom);
                    $newCity->setCodeINSEE((int)$dataAnnonce->city->code);
                    $newCity->setDepartment($this->departmentRepository->findOneBy(['code' => $dataAnnonce->city->codeDepartement]));
                    // On va chercher les coordonnées de la ville sur l'api
                    $coordinates = $this->cityCoordinates->getCoordinates($newCity);
                    if ($coordinates !== null) {
                        $newCity->setLatitude((float)$coordinates['latitude']);
                        $newCity->setLongitude((float)$coordinates['longitude']);
                    }
                    $this->em->persist($newCity);
                    $user->getAnnonce()->setCity($newCity);
                }
            }
        }

        return true;
    }

    /**
     * Handle MutationCity data
     * 
     * @param Object $mutationCity City data to set in the User ad
     * @param User $user User
     * @return bool|JsonResponse
     */
    public function handleMutationCity($dataAnnonce, $user)
    {
        // On vérifie que des données ont été envoyées et que ce n'est pas vide
        if(!empty($dataAnnonce->mutationCity)) {
            // Si l'utilisateur ne rempli pas le champs un objet vide est envoyé dans le json
            // Si un objet vide est envoyé on set la mutationCity à null
            // (par défaut la ville contient un objet vide à cause du serializer)
            if(empty((array)$dataAnnonce->mutationCity)) {
                $user->getAnnonce()->setMutationCity(null);
                // Si mutationCity n'est pas rempli, on regarde mutationDepartment
                $mutationDepartment = $user->getAnnonce()->getMutationDepartment();
                if ($mutationDepartment !== null) {
                    if (empty((array)$dataAnnonce->mutationDepartment)) {
                        $user->getAnnonce()->setMutationDepartment(null);
                    } else {
                        $user->getAnnonce()->setMutationRegion($mutationDepartment->getRegion());
                    }
                }
                if (empty((array)$dataAnnonce->mutationRegion)) {
                    $user->getAnnonce()->setMutationRegion(null);
                }
            // Sinon si des données sont envoyées
            } else {
                // Si la ville de mutation saisie est la même que la ville d'origine
                // on envoie une erreur
                
                if ((isset($dataAnnonce->mutationCity->nom) && $dataAnnonce->mutationCity->nom == $user->getAnnonce()->getCity()->getName()) || (!isset($dataAnnonce->mutationCity->nom) && $dataAnnonce->mutationCity == $user->getAnnonce()->getCity()->getId())) {
                    return new JsonResponse(['error' => 'La ville d\'origine et la ville de mutation doivent être différente.'], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                // On vérifie si la ville existe déjà en BDD
                if(!empty($dataAnnonce->mutationCity->nom)) {
                    $mutationCity = $this->cityRepository->findOneBy(['name' => $dataAnnonce->mutationCity->nom]);
                } else {
                    $mutationCity = $this->cityRepository->find($dataAnnonce->mutationCity);
                }
                // Si elle existe, on la set sur l'annonce
                if ($mutationCity !== null) {
                    $user->getAnnonce()->setMutationCity($mutationCity);
                    $user->getAnnonce()->setMutationDepartment($mutationCity->getDepartment());
                    $user->getAnnonce()->setMutationRegion($mutationCity->getDepartment()->getRegion());
                // sinon on l'ajoute en BDD et on la set sur l'annonce
                } else {
                    $newCity = new City();
                    $newCity->setName($dataAnnonce->mutationCity->nom);
                    $newCity->setCodeINSEE((int)$dataAnnonce->mutationCity->code);
                    $newCity->setDepartment($this->departmentRepository->findOneBy(['code' => $dataAnnonce->mutationCity->codeDepartement]));
                    // On set sur l'annonce le département et la région
                    $user->getAnnonce()->setMutationDepartment($newCity->getDepartment());
                    $user->getAnnonce()->setMutationRegion($newCity->getDepartment()->getRegion());
                    $this->em->persist($newCity);
                    $user->getAnnonce()->setMutationCity($newCity);
                }
            }
        }
        // Cette partie du code est traité seulement si l'utilisation renvoie des chaines de caractères vides plutôt que des objets vides
        // Si mutationCity pas rempli, on regarde mutationDepartment
        else {
            $mutationDepartment = $user->getAnnonce()->getMutationDepartment();
            if ($mutationDepartment !== null) {
                if (empty((array)$dataAnnonce->mutationDepartment)) {
                    $user->getAnnonce()->setMutationDepartment(null);
                } else {
                    $user->getAnnonce()->setMutationRegion($mutationDepartment->getRegion());
                }
            }
            if (empty((array)$dataAnnonce->mutationRegion) && $dataAnnonce->mutationRegion != null) {
                $user->getAnnonce()->setMutationRegion(null);
            }
        }

        return true;
    }
}