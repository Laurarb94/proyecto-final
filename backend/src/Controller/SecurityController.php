<?php

namespace App\Controller;

use ApiPlatform\OpenApi\Model\Response;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


final class SecurityController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!isset($data['email'])){
            return $this->json([
                'estado' => 'error', 
                'message' => 'El campo email es obligatorio'
            ], 200);
        }

        if(!isset($data['password'])){
            return $this->json([
                'estado' => 'error', 
                'message' => 'El campo de la contraseña es obligatorio'
            ], 200);
        }

        //Verificar que el correo existe
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data ['email']]);

        if(!$user){
            return $this->json([
                'estado' => 'error',
                'email' => 'Las credenciales ingresadas no son válidas'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if($passwordHasher->isPasswordValid($user, $data['password'])) {
            $payload = [
                'iss' => 'http://127.0.0.1:8000',
                'aud' => $user->getId(),
                'iat' => time(),
                'exp' => time() + (30 * 24 * 60 * 60)
            ];

            //construyes el token

            try{
                $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS512');
            }catch(\Exception $e){
                return $this->json([
                    'estado'=>'error',
                    'message'=>'Error al generar el token.'
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->json([
                'name' => $user->getName(),
                'token' => $jwt
            ]);


        }else{
            return $this->json([
                'estado' => 'error',
                'password' => 'Las credenciales ingresadas no son válidas'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

    }
    

    #[Route('/api/logout', name: 'api_logout', methods: ['POST'])]
    public function apiLogout(): JsonResponse
    {
        return new JsonResponse([ 'message' => 'Logout exitoso'], JsonResponse::HTTP_OK);
    }




} //fin controlador
