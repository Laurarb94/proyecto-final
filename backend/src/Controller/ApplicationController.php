<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\JobOffer;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ApplicationController extends AbstractController{

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

}
