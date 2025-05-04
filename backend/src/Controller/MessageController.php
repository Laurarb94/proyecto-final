<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MessageController extends AbstractController
{
    #[Route('/api/messages', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request, EntityManagerInterface $em, UserRepository $userRepo, Security $security): JsonResponse
    {
       $data = json_decode($request->getContent(), true);

       $receiverId = $data['receiver_id'] ?? null;
       $content = $data['content'] ?? null;

       if(!$receiverId || !$content) return new JsonResponse(['error' => 'Datos incompletos'], 400);

       $sender = $security->getUser();
       $receiver = $userRepo->find($receiverId);

       if(!$receiver) return new JsonResponse(['error' => 'Receptor no encontrado'], 404);

       $message = new Message();
       $message->setSender($sender);
       $message->setReceiver($receiver);
       $message->setContent($content);
       $message->setSentAt(new \DateTimeImmutable());

       $em->persist($message);
       $em->flush();

       return new JsonResponse(['status'=>'Mensaje enviado']);

    }

    #[Route('/api/messages/conversation/{userId}', name: 'get_conversation', methods: ['GET'])]
    public function getConversation(int $userId, Security $security, UserRepository $userRepository, MessageRepository $messageRepository): JsonResponse
    {
        $currentUser = $security->getUser();
        $otherUser = $userRepository->find($userId);

        if(!$currentUser instanceof User) return new JsonResponse(['error'=>'usuario no válido'], 401);

        if(!$otherUser) return new JsonResponse(['error'=>'Usuario no encontardo'], 404);

        $messages = $messageRepository->createQueryBuilder('m')
            ->where('(m.sender = :user1 AND m.receiver = :user2) OR (m.sender = :user2 AND m.receiver = :user1)')
            ->setParameter('user1', $currentUser)
            ->setParameter('user2', $otherUser)
            ->orderBy('m.sentAt', 'ASC')
            ->getQuery()
            ->getResult();

        
        $seralized = array_map(function (Message $msg){
            return [
                'id' => $msg->getId(),
                'content' => $msg->getContent(),
                'sentAt' => $msg->getSentAt()->format('Y-m-d H:i:s'),
                'sender' => $msg->getSender()->getId(),
                'receiver' => $msg->getReceiver()->getId()
            ];
        }, $messages);

        return new JsonResponse($seralized);
    }


}//cierre controller
