<?php

namespace App\Controller;

use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\Routing\Attribute\Route;


final class UserController extends AbstractController{

    private $em;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params)
    {
        $this->em = $em;
    }

    //-------------------------------------------Listar usuarios--------------------------------------------------------------------------------

    #[Route('/api/user', name: 'user_list', methods:['GET'])]
    public function list(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        return $this->json($users, Response::HTTP_OK);
    }
    
    //------------------------------------------Crear usuarios----------------------------------------------------------------------------------
    #[Route('api/user', name:'user_create', methods: ['POST'])]
    public function create(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, 
    ParameterBagInterface $params): JsonResponse
    {
        //Decodificas el continido json que hay en la solicitud
        $data = json_decode($request->getContent(), true);
        
        //verificas que los datos sean correctos:
        if(!$data || !isset($data['email']) || !isset($data['password'])){
            return new JsonResponse(['error'=>'Datos incompletos'], Response::HTTP_BAD_REQUEST);
        }

        //Creas un nuevo objeto user:
        $user = new User();

        //Asignas los datos a la entidad user:
        $user->setName($data['name']);
        $user->setLastName1($data['last_name1']);
        $user->setLastName2($data['last_name2']);
        $user->setEmail($data['email']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->setCountry($data['country']);
        $user->setCity($data['city']);
        $user->setPhone($data['phone']);
        $user->setPhoto($data['photo']);
        $user->setBiography($data['biography']);
        $user->setRoles($data['roles']);

        //Obtener los correos configurados en services.yalm para los roles
        $adminEmails = $params->get('admin_emails');
        $companyEmails = $params->get('company_emails');

        //Asignas los roles según el email:
        if (in_array($user->getEmail(), $adminEmails)) {
            $user->setRoles(['ROLE_ADMIN']);
        } elseif (in_array($user->getEmail(), $companyEmails)) {
            $user->setRoles(['ROLE_COMPANY']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        //Guardas al usuario en la bbdd
        $em->persist($user);
        $em->flush();

        //Devolver respuesta con los datos del nuevo usu:
        return $this->json([
            'id'=> $user->getId(),
            'name'=>$user->getName(),
            'las_name1' => $user->getLastName1(),
            'last_name2'=>$user->getLastName2(),
            'email'=>$user->getEmail(),
            'password'=>$user->getPassword(),
            'country'=>$user->getCountry(),
            'city'=>$user->getCity(),
            'phone'=>$user->getPhone(),
            'photo'=>$user->getPhoto(),
            'biography'=>$user->getBiography(),
            'roles'=>$user->getRoles(),
        ], Response::HTTP_CREATED);


    }

    /*-----------------------------------------Editar usuarios----------------------------------------------------------------------------------------
    #[Route('api/user/{id}', name:'user_edit', methods:['PUT'])]
    public function edit(int $id, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        
    }

    //Eliminar usuarios
    #[Route('api/user/{id}', name:'user_delete', methods:['DELETE'])]
    public function delete(int $id): Response
    {
       
    }

    //Métodos para añadie para el perfil del usuario

    //Obtener ofertas de emplo del usuario
    
    //Mostrar foto de perfil del usuario

    //Cambio de fotos de perfil y cambio de contraseña
   

*/












} //cierre controlador
