<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use App\Service\Validator;
use App\Notification\UserNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="api_contact", methods={"POST"})
     */
    public function contactMessage(Request $request, UserNotification $notification, SerializerInterface $serializer, Validator $validator)
    {
        //1. On désérialize la requette du l'utilisateur 
        $contact = $serializer->deserialize($request->getContent(), Contact::class, 'json');
        
        // On vérifie la validité des données
        $errors = $validator->validate($contact);
        if ($errors !== null) return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);

        // Envoyer le message
        $notification->contact($contact);

        return $this->json(['message' => 'Message envoyé.'], Response::HTTP_OK);
        
    }
}
