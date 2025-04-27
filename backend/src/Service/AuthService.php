<?php

namespace App\Service;

use App\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\key;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthService
{
    private $em;
    private $jwtSecret;

    public function __construct(EntityManagerInterface $em, string $jwtSecret)
    {
        $this->em = $em;
        $this->jwtSecret = $jwtSecret;
    }

    public function getUserFromToken(string $token): ?User
    {
        try{
            //Decodificar el token jwt
            $decode = JWT::decode($token, new Key($this->jwtSecret, 'HS512'));
            $userId = $decode->aud;

            var_dump($decode);die();

            return $this->em->getRepository(User::class)->find($userId);
            
        }catch(\Exception $e){
            throw new UnauthorizedHttpException('Invalid token');
        }
    }





} //cierre del servicio
