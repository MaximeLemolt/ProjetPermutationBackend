<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\Validator;
use App\Form\ResetPasswordType;
use App\Form\ForgotPasswordType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Notification\UserNotification;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\HandleMutationDestination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="api_register", methods={"POST"})
     */
    public function register(SerializerInterface $serializer, Request $request, Validator $validator, EntityManagerInterface $em, HandleMutationDestination $handleMutationDestination, UserPasswordEncoderInterface $passwordEncoder, UserNotification $notification, UserRepository $userRepository, RoleRepository $roleRepository)
    {
        //1. On désérialize la requette du l'utilisateur 
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        // 2.1 On recupère les données concernant l'annonce depuis la requête
        $data = json_decode($request->getContent());

        if (!empty((array)$data->annonce)) {
            $handleMutationDestination->handleCity($data->annonce, $user);
            $mutationCity = $handleMutationDestination->handleMutationCity($data->annonce, $user);
            if ($mutationCity !== true) { return $mutationCity; }
        }

        // On passe le statut de l'annonce à 2 (non publiée)
        $user->getAnnonce()->setStatus(2);

        // gestion des erreurs
        $errorsUser = $validator->validate($user);
        $errorsAnnonce = $validator->validate($user->getAnnonce());
        // Si la validation des deux entités à relevé des erreurs
        // on merge les deux tableaux et on renvoie les erreurs
        if ($errorsUser !== null && $errorsAnnonce !== null) {
            $errors = array_merge($errorsUser, $errorsAnnonce);
            if ($errors !== null) return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($errorsUser !== null) return $this->json(['errors' => $errorsUser], Response::HTTP_UNPROCESSABLE_ENTITY);
        if ($errorsAnnonce !== null) return $this->json(['errors' => $errorsAnnonce], Response::HTTP_UNPROCESSABLE_ENTITY);

        // On encode le mot de passe
        $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        // On récupère le role User
        $roleUser = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
        $user->addUserRole($roleUser);

        $em->persist($user);
        $em->flush();
        $notification->notify($user);

        // FEAT : Envoie de mail aux utilisateurs dont les critères de mutation matche avec le new user inscrit
        // Je récupère la ville et la ville de mutation de l'utilsateur qui s'inscrit
        if (!empty($user->getAnnonce()->getCity()) && !empty($user->getAnnonce()->getMutationCity())) {
            $currentCity = $user->getAnnonce()->getCity()->getName();
            $cityDestination = $user->getAnnonce()->getMutationCity()->getName();
            // Je fais une recherche en bdd :
            // Ville courant du nouvelle utilisateur === ville de destination des users en bdd
            // Vilel de destination du nouvelle utilisateur === ville courante des users en bdd
            $usersMatches = $userRepository->findUserByCityAndMutationCity($cityDestination, $currentCity);
            // J'envoie le mail de notification a chaque utilisateur dont les critères matche
            foreach($usersMatches as $userMatch){
            $notification->notifyAlert($userMatch, $user);
            }
        }
        

        return $this->json(['message' => 'Utilisateur créé.'], Response::HTTP_OK);
    }

    /**
     * Supprimer un user 
     * 
     * @Route("/api/users/{id<\d+>}", name="api_delete_users", methods={"DELETE"})
     */
    public function delete(User $user = null)
    {
        // On vérifie si l'utilisateur est autorisé a modifié l'annonce
        if(!$this->isGranted('REMOVE', $user)) {
            return $this->json(['message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
        }

        if($user === null) {
            // On renvoie un message d'erreur en json pour qu'elle soit exploitable par le front
            return $this->json([
                'message' => 'User inexistant.'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['message' => 'User supprimé.'], Response::HTTP_OK);       
    }

    /**
     * Edit user
     * 
     * @Route("/api/users/{id<\d+>}", name="api_edit_users", methods={"PUT"})
     */
    public function edit(User $user = null, SerializerInterface $serializer, Request $request, Validator $validator, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        if($user === null) {
            // On renvoie un message d'erreur en json pour qu'elle soit exploitable par le front
            return $this->json([
                'message' => 'User inexistant.'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        // On vérifie si l'utilisateur est autorisé a modifié son profil
        if(!$this->isGranted('EDIT', $user)) {
            return $this->json(['message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
        }

        // Mot de passe avant déserialization
        $currentPassword = $user->getPassword();
        
        $user = $serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        // gestion des erreurs
        $errors = $validator->validate($user);
        if ($errors !== null) return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);

        // recupere le nouveau de mot de passe après deserialization
        $newPassword = $user->getPassword();

        if (empty($newPassword) || ($newPassword == $currentPassword)) {
            $user->setPassword($currentPassword);
        } else {
            // On encode le mot de passe
            $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setPassword($encodedPassword);
        }

        $em->flush();
        
        return $this->json(['message' => 'Utilisateur modifié.'], Response::HTTP_OK); 
    }

    /**
     * Afficher un user
     * 
     * @Route("/api/users/{id<\d+>}", name="_show_user", methods={"GET"})
     */
    public function show(User $user = null)
    {
        if($user === null) {
            // On renvoie un message d'erreur en json pour qu'elle soit exploitable par le front
            return $this->json([
                'message' => 'Utilisateur inexistant.'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        // On vérifie que le user est bien le propriétaire de l'user
        if(!$this->isGranted('SHOW', $user)) {
            return $this->json(['message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
        }

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'users_get']);
    }

    /**
     * Récupèrer la liste des utilisateurs avec lesquels le user connecté est entré en contact
     * 
     * @Route("/api/users/{id<\d+>}/contacts", name="api_users_contacts", methods={"GET"})
     */
    public function getContacts(UserRepository $userRepository,  User $user = null)
    {
        if($user === null) {
            return $this->json(['message' => 'Utilisateur inexistant.'], Response::HTTP_NOT_FOUND);
        }
        
        // On recupère les User contacté par l'utilisateur connecté
        $usersContacted = $userRepository->getUsersContactedByUser($user);
        // On récupére les User qui ont contacté l'utilisateur connecté
        $usersWhoContactMe = $userRepository->getUsersWhoContatedTheUser($user);
        // On fusionne les 2 tableaux, on dédoublonne et on retourne toutes les valeurs pour ne pas avoir les index
        $contacts = array_values(array_unique(array_merge($usersContacted, $usersWhoContactMe)));

        return $this->json(['contacts' => $contacts], Response::HTTP_OK, [], ['groups' => 'users_get']);
    }

    /**
     * Vérification du token
     * 
     * @Route("/api/tokenVerify", name="api_token_verify", methods={"GET"})
     */
    public function verifyToken(Request $request)
    {
        return $this->json(['message' => 'Token valid'], Response::HTTP_OK);
    }

    /**
     * Forgotten password
     * 
     * @Route("/forgotpassword", name="forgot_password", methods={"GET", "POST"})
     */
    public function forgottenPassword(Request $request, UserRepository $userRepository, UserNotification $notification, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $em)
    {
        $form = $this->createForm(ForgotPasswordType::class);

        // handleRequest() met à jour l'objet User avec les infos du form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données du formulaire
            $data = $form->getData();

            // On vérifie si l'email saisi correspond bien à un user en BDD
            $user = $userRepository->findOneBy(['email' => $data['email']]);

            if($user !== null) {
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $em->flush();

                $notification->resetPassword($data['email'], $token);

                $this->addFlash('success', 'Un email vous a été envoyé afin de réinitialiser votre mot de passe.');
                
            } else {
                $this->addFlash('warning', 'L\'email saisie ne correspond pas un compte utilisateur.');
            }
        }

        return $this->render('users/forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Reset password
     * 
     * @Route("/resetpassword", name="reset_password", methods={"GET", "POST"})
     */
    public function resetPassword(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $form = $this->createForm(ResetPasswordType::class);

        // handleRequest() met à jour l'objet User avec les infos du form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données du formulaire
            $data = $form->getData();

            // On récupère le user
            $user = $userRepository->findOneBy(['resetToken' => $request->query->get('token')]);

            if ($user !== null) {
                // On encode le mot de passe
                $encodedPassword = $passwordEncoder->encodePassword($user, $data['password']);
                $user->setPassword($encodedPassword);
                $user->setResetToken(null);

                $em->flush();

                $this->addFlash('success', 'Votre mot de passe a bien été modifié.');

                // Si tout est bon on redirige
                return $this->render('users/reset_password.html.twig', [
                    'form' => $form->createView(),
                    'reset' => true
                ]);

            } else {
                $this->addFlash('warning', 'Token invalide');
            }
        }

        return $this->render('users/reset_password.html.twig', [
            'form' => $form->createView(),
            'reset' => false
        ]);
    }
}
