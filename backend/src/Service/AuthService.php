<?php

namespace App\Service;

use App\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Psr\Log\LoggerInterface;

class AuthService
{
    private $em;
    private $jwtSecret;
    private $logger;

    public function __construct(EntityManagerInterface $em, string $jwtSecret, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->jwtSecret = $jwtSecret;
        $this->logger = $logger;
    }

    public function getUserFromToken(string $token): ?User
    {
        try{
            //Decodificar el token jwt
            $decode = JWT::decode($token, new Key($this->jwtSecret, 'HS512'));
            $this->logger->info('Token decodificado', ['decoded_token' => (array) $decode]);

            $userId = $decode->aud;

           // Verificar si el usuario existe en la base de datos
           $user = $this->em->getRepository(User::class)->find($userId);
           if ($user) {
               $this->logger->info('Usuario encontrado', ['user_id' => $userId]);
           } else {
               $this->logger->warning('Usuario no encontrado', ['user_id' => $userId]);
           }

           return $user;
            
        }catch(\Exception $e){
            $this->logger->error('Error al decodificar el token', ['exception' => $e]);
            throw new UnauthorizedHttpException('Invalid token');
        }
    }





} //cierre del servicio
