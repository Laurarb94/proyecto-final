<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Doctrine\ORM\EntityManagerInterface;

final class RegistrationController extends AbstractController{

    private $params;

    public function __construct(ParameterBagInterface $params, private Security $security)
    {
        $this->params = $params;
    }


    #[Route('api/registration', name: 'api_registration', methods:['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em, ParameterBagInterface $params): Response
    {
        //Lo primero decodificas el json que viene desde el front
        $data = json_decode($request->getContent(), true);

        //Validación de los datos
        if(!isset($data['name'], $data['last_name1'], $data['last_name2'], $data['email'], $data['password'], 
           $data['city'], $data['phone'], $data['country'], $data['biography'], $data['photo'])){

            return new JsonResponse (['error' => 'Faltan datos de usuario'], JsonResponse::HTTP_BAD_REQUEST);
        }

        //Crear al usuario
        $user = new User();
        $user->setName($data['name']);
        $user->setLastName1($data['last_name1']);
        $user->setLastName2($data['last_name2']);
        $user->setEmail($data['email']);

        //Encriptar la contraseña
        $hashedPassword = $userPasswordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $user->setCity($data['city']);
        $user->setPhone($data['phone']);
        $user->setCountry($data['country']);
        $user->setBiography($data['biography']);
        $user->setPhoto($data['photo']);

        //Asignar los roles
        $adminEmails = $params->get('admin_emails') ?? [];
        $companyEmails = $params->get('company_emails') ?? [];

        if(in_array($user->getEmail(), $adminEmails)) {
            $user->setRoles(['ROLE_ADMIN']);
        }elseif(in_array($user->getEmail(), $companyEmails)){
            $user->setRoles(['ROLE_COMPANY']);
        }else{
            $user->setRoles(['ROLE_USER']);
        }

        //guardarlo en la bbdd
        $em->persist($user);
        $em->flush();

        //Responder con el usuario registrado
        return new JsonResponse(['message'=>'Usuario registrado con éxito'], JsonResponse::HTTP_CREATED);

    }




}//fin conctrolador
