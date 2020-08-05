<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Message;
use App\Service\DateFormatter;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="api")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/messages/{author<\d+>}/{recipient<\d+>}", name="_messages", methods={"GET"})
     */
    public function getConversation(MessageRepository $messageRepository, User $author = null, User $recipient = null)
    {
        if($author === null || $recipient === null) {
            return $this->json(['message' => 'Utilisateur inexistant.'], Response::HTTP_NOT_FOUND);
        }

        $messages = $messageRepository->getMessagesBetweenTwoUsers($author, $recipient);

        foreach ($messages as $message) {
            DateFormatter::format($message, ['createdAt', 'updatedAt'], 'H:i d-m-Y');
        }
        
        return $this->json(['messages' => $messages], Response::HTTP_OK, [], ['groups' => 'messages_get']);
    }

    /**
     * Send a message
     * 
     * @Route("/messages/{author<\d+>}/{recipient<\d+>}", name="_send_messages", methods={"POST"})
     */
    public function send(SerializerInterface $serializer, Request $request, User $author = null, User $recipient = null, EntityManagerInterface $em)
    {
        $message = $serializer->deserialize($request->getContent(), Message::class, 'json');

        if($author === null || $recipient === null) {
            return $this->json(['message' => 'Utilisateur inexistant.'], Response::HTTP_NOT_FOUND);
        }

        $message->setAuthor($author);
        $message->setRecipient($recipient);

        $em->persist($message);
        $em->flush();
        
        return $this->json(['message' => 'Message envoyé'], Response::HTTP_OK);
    }
}

