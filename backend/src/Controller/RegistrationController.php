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
        //Obtener los datos del form multipart-formdata
        $name = $request->get('name');
        $lastName1 = $request->get('last_name1');
        $lastName2 = $request->get('last_name2');
        $email = $request->get('email');
        $password = $request->get('password');
        $phone = $request->get('phone');
        $city = $request->get('city');
        $country = $request->get('country');
        $biography = $request->get('biography');
        $photo = $request->files->get('photo');
        $cv = $request->files->get('cv');

        //Validación de campos:
        $missingFields = [];
        if (!$name) {
            $missingFields[] = 'name';
        }
        if (!$lastName1) {
            $missingFields[] = 'last_name1';
        }
        if (!$lastName2) {
            $missingFields[] = 'last_name2';
        }
        if (!$email) {
            $missingFields[] = 'email';
        }
        if (!$password) {
            $missingFields[] = 'password';
        }
        if (!$phone) {
            $missingFields[] = 'phone';
        }
        if (!$city) {
            $missingFields[] = 'city';
        }
        if (!$country) {
            $missingFields[] = 'country';
        }
        if (!$biography) {
            $missingFields[] = 'biography';
        }
        if (!$photo) {
            $missingFields[] = 'photo';
        }
        if (!$cv) {
            $missingFields[] = 'cv';
        }
        
        // Si hay campos faltantes, devolverlos en la respuesta
        if (!empty($missingFields)) {
            return new JsonResponse(['error' => 'Faltan los siguientes campos: ' . implode(', ', $missingFields)], JsonResponse::HTTP_BAD_REQUEST);
        }

        //Gestión de la subida de foto y cv
        $photoName = md5(uniqid()) . '.' . $photo->guessExtension();
        $photo->move($this->getParameter('kernel.project_dir') . '/public/uploads/profile_photos', $photoName);
    
        $cvName = md5(uniqid()) . '.' . $cv->guessExtension();
        $cv->move($this->getParameter('kernel.project_dir') . '/public/uploads/cvs', $cvName);

        if ($cv->getClientOriginalExtension() !== 'pdf') {
            return new JsonResponse(['error' => 'El CV debe estar en formato PDF'], 400);
        }

        //Crear al usuario
        $user = new User();
        $user->setName($name);
        $user->setLastName1($lastName1);
        $user->setLastName2($lastName2);
        $user->setEmail($email);

        //Encriptar la contraseña
        $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $user->setCity($city);
        $user->setPhone($phone);
        $user->setCountry($country);
        $user->setBiography($biography);
        $user->setPhoto($photoName);
        $user->setCv($cvName);

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
