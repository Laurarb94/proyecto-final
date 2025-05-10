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
use App\Service\AuthService;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class MessageController extends AbstractController
{
    private $authService;
    private $logger;

    public function __construct(AuthService $authService, LoggerInterface $logger)
    {
        $this->authService = $authService;
        $this->logger = $logger;
    }

    #[Route('/api/messages', name: 'send_message', methods: ['POST'])]
    public function sendMessage(Request $request, EntityManagerInterface $em, UserRepository $userRepo, Security $security, LoggerInterface $logger): JsonResponse
    {
        //Obtener token de authorization header
        $authHeader = $request->headers->get('Authorization');

        $logger->info('Token recibido: ' . $authHeader, []);

        if(!$authHeader || !str_starts_with($authHeader, 'Bearer')){
            $logger->error('No token proporcionado o formato incorrecto.');
            return new JsonResponse(['error'=>'No token provided'], 400);
        }

       $token = substr($authHeader, 7); //extraer token del encabezado

       try{
          //usar authservice para obtener al usuario autenticado
          $sender = $this->authService->getUserFromToken($token);
          $logger->info('Usuario autenticado: ' . $sender->getName(), []);
        }catch(\Exception $e){
            $logger->error('Error en la autenticación del token: ' . $e->getMessage());
          return new JsonResponse(['error'=>'Token inválido o expirado'], 401);
       }

       //Recuperar datos del mensaje
       $data = json_decode($request->getContent(), true);
       $logger->info('Datos del mensaje recibidos: ' . json_encode($data), []);

       $receiverId = $data['receiver_id'] ?? null;
       $content = $data['content'] ?? null;

       if(!$receiverId || !$content) {
            $logger->error('Datos incompletos en la solicitud: ' . json_encode($data));
            return new JsonResponse(['error' => 'Datos incompletos'], 400);
        }

       //Buscar al receptor
       $receiver = $userRepo->find($receiverId);
       if(!$receiver) {
            $logger->error('Receptor no encontrado con ID: ' . $receiverId); 
            return new JsonResponse(['error' => 'Receptor no encontrado'], 404);
        }

       //Crear el mensaje
       $message = new Message();
       $message->setSender($sender);
       $message->setReceiver($receiver);
       $message->setContent($content);
       $message->setSentAt(new \DateTimeImmutable());

       try {
            $em->persist($message);
            $em->flush();
            $logger->info('Mensaje enviado exitosamente a: ' . $receiver->getName(), []);  // Log para el mensaje enviado correctamente
        } catch (\Exception $e) {
            $logger->error('Error al guardar el mensaje: ' . $e->getMessage());  // Log para errores al guardar el mensaje
            return new JsonResponse(['error' => 'Error al enviar el mensaje'], 500);
        }

       return new JsonResponse(['status'=>'Mensaje enviado']);

    }  

    #[Route('/api/messages/conversation/{userId}', name: 'get_conversation', methods: ['GET'])]
    public function getConversation(int $userId, Request $request, LoggerInterface $logger, UserRepository $userRepository, MessageRepository $messageRepository): JsonResponse
    {
        
        // Obtener token de authorization header
        $authHeader = $request->headers->get('Authorization');
        
        // Log para el token recibido
        $logger->info('Token recibido: ' . $authHeader);
        
        // Verificar si el token existe y tiene el formato correcto
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer')) {
            $logger->error('No token proporcionado o formato incorrecto.');
            return new JsonResponse(['error' => 'No token provided'], 400);
        }
        
        // Extraer el token del encabezado
        $token = substr($authHeader, 7);
        
        try {
            // Usar el servicio AuthService para obtener al usuario autenticado
            $currentUser = $this->authService->getUserFromToken($token);
            $logger->info('Usuario autenticado: ' . $currentUser->getName());
        } catch (\Exception $e) {
            // Si ocurre un error, logueamos el error y retornamos un mensaje de token inválido
            $logger->error('Error en la autenticación del token: ' . $e->getMessage());
            return new JsonResponse(['error' => 'Token inválido o expirado'], 401);
        }
        
        // Buscar al otro usuario
        $otherUser = $userRepository->find($userId);
        
        if (!$otherUser) {
            $logger->warning('Usuario no encontardo con ID: $userId');
            return new JsonResponse(['error' => 'Usuario no encontrado'], 404);
        }
        
        // Obtener los mensajes entre el usuario actual y el otro usuario
        $messages = $messageRepository->createQueryBuilder('m')
            ->where('(m.sender = :user1_id AND m.receiver = :user2_id) OR (m.sender = :user2_id AND m.receiver = :user1_id)')
            ->setParameter('user1_id', $currentUser)
            ->setParameter('user2_id', $otherUser)
            ->orderBy('m.sentAt', 'ASC')
            ->getQuery()
            ->getResult();
        $logger->info('Mensajes encontrados: ' . count($messages));

        // Serializar los mensajes
        $serialized = array_map(function (Message $msg) {
            return [
                'id' => $msg->getId(),
                'content' => $msg->getContent(),
                'sentAt' => $msg->getSentAt()->format('Y-m-d H:i:s'),
                'sender' => [
                    'id' => $msg->getSender()->getId(), //hay que pasarle el id para el front!!
                    'name' => $msg->getSender()->getName()
                ],
                'receiver' => [
                    'id' => $msg->getSender()->getId(), //id para front!!
                    'name' => $msg->getSender()->getName()
                ],
            ];
        }, $messages);
        
        // Si no hay mensajes, devolvemos un mensaje vacío
        if (empty($serialized)) {
            return new JsonResponse(['status' => 'No hay mensajes en esta conversación']);
        }
        
        // Devolver los mensajes serializados
       // return new JsonResponse($serialized);

       return new JsonResponse([
            'user' => [
            'id' => $otherUser->getId(),
            'name' => $otherUser->getName()
        ], 'messages' => $serialized
    ]);

    }



}//cierre controller
