<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\JobOffer;
use App\Repository\ApplicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

final class ApplicationController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    //Método para registrar una postulación a una oferta de empleo
    #[Route('api/{id}/apply', methods: ['POST'])]
    public function applyToOffer(JobOffer $offer, Request $request, EntityManagerInterface $em, UserRepository $userRepo): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'] ?? null;
        
        if (!$userId) {
            return $this->json(['error' => 'Falta userId'], 400);
        }
        
        $user = $userRepo->find($userId);
        if (!$user) {
            return $this->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Verificar si ya existe una aplicación
        foreach ($user->getApplications() as $app) {
            if ($app->getJobOffer()->getId() === $offer->getId()) {
                return $this->json(['message' => 'Ya has aplicado a esta oferta'], 200);
            }
        }
        
        $application = new Application();
        $application->setUser($user);
        $application->setJobOffer($offer);
        $application->setCreatedAt(new \DateTimeImmutable());
        
        $em->persist($application);
        $em->flush();
        
        return $this->json(['message' => 'Postulación registrada']);
    
    }

    //Endpoint que devuelve las postulaciones del usuario
    #[Route('api/user/{userId}/applications', methods: ['GET'])]
    public function getUserApplications($userId, ApplicationRepository $appRepo): JsonResponse
    {
        $applications = $appRepo->findBy(['user' => $userId]);
        
        //Usar los grupos que se definen en las entidades para evitra la referencia circular:
        $data = $this->serializer->serialize($applications, 'json', ['groups'=>['application_read']]);

        //return $this->json($applications);
        return new JsonResponse($data, 200, [], true);
    }

    //Eliminar ofertas del usuario
    #[Route('api/user/{userId}/applications/{jobOfferId}', methods:['DELETE'])]
    public function delete(int $userId, int $jobOfferId ,EntityManagerInterface $em, ApplicationRepository $appRepo, LoggerInterface $logger): JsonResponse
    {
        $application = $appRepo->findOneBy(['user'=>$userId, 'jobOffer'=>$jobOfferId]);

        if (!$application) {
            $logger->error('Postulación no encontrada', ['userId' => $userId, 'jobOfferId' => $jobOfferId]);
            return new JsonResponse(['message' => 'Postulación no encontrada'], 404);
        }

        $em->remove($application);
        $em->flush();

        $logger->info('Postulación eliminada con éxito', ['userId' => $userId, 'jobOfferId' => $jobOfferId]);

        return new JsonResponse(['message' => 'Aplicación eliminada con éxito']);
    }



} //cierre controller
