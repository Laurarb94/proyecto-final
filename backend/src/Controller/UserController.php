<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use ContainerH34GUZX\getApiPlatform_JsonSchema_BackwardCompatibleSchemaFactoryService;
use Doctrine\Migrations\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;


final class UserController extends AbstractController{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    //-------------------------------------------Listar usuarios--------------------------------------------------------------------------------

    #[Route('/api/user', name: 'user_list', methods:['GET'])]
    public function list(SerializerInterface $serializer): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        $data = $serializer->serialize($users, 'json', ['groups' => ['user:read']]);
        
        return new JsonResponse($data, Response::HTTP_OK, [], true);
        
    }
    
    //----------------------------------------Obtener usuario por su id---------------------------------------------------------------------------
    #[Route('api/user/{id}', name: 'user_get', methods:['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->em->getRepository(User::class)->find($id);

        if(!$user){
            return new JsonResponse(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id'=> $user->getId(),
            'name'=>$user->getName(),
            'last_name1' => $user->getLastName1(),
            'last_name2'=>$user->getLastName2(),
            'email'=>$user->getEmail(),
            'password'=>$user->getPassword(),
            'country'=>$user->getCountry(),
            'city'=>$user->getCity(),
            'phone'=>$user->getPhone(),
            'photo'=>$user->getPhoto(),
            'biography'=>$user->getBiography(),
            'roles'=>$user->getRoles(),
            'cv'=>$user->getCV(),
        ], Response::HTTP_OK);
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
            'last_name1' => $user->getLastName1(),
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

    //-----------------------------------------Editar usuarios----------------------------------------------------------------------------------------
    #[Route('api/user/{id}', name:'user_edit', methods:['PUT'])]
    public function edit(int $id, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse
    {
        //obtienes el usuario por su id. Con find(id)->te va a devolver un solo objeto, un solo usuario con ese id
        $user = $em->getRepository(User::class)->find($id);

        if(!$user){
            return new JsonResponse(['error'=>'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        //Actualizas los datos del usuario:
        if(isset($data['name'])){
            $user->setName($data['name']);
        }

        if(isset($data['last_name1'])){
            $user->setLastName1($data['last_name1']);
        }

        if(isset($data['last_name2'])){
            $user->setLastName2($data['last_name2']);
        }

        if(isset($data['email'])){
            $user->setEmail($data['email']);
        }

        if(isset($data['password'])){
            $user->setPassword($data['password']);
        }

        if(isset($data['country'])){
            $user->setCountry($data['country']);
        }

        if(isset($data['city'])){
            $user->setCity($data['city']);
        }

        if(isset($data['phone'])){
            $user->setPhone($data['phone']);
        }

        if(isset($data['photo'])){
            $user->setPhoto($data['photo']);
        }

        if(isset($data['biography'])){
            $user->setBiography($data['biography']);
        }

        if(isset($data['roles'])){
            $user->setRoles($data['roles']);
        }

        //Guardar los datos cambiados en la bbdd
        $em->flush();

        //Responder con los datos del usuario actualizado
        return $this->json([
            'id'=> $user->getId(),
            'name'=>$user->getName(),
            'last_name1' => $user->getLastName1(),
            'last_name2'=>$user->getLastName2(),
            'email'=>$user->getEmail(),
            'password'=>$user->getPassword(),
            'country'=>$user->getCountry(),
            'city'=>$user->getCity(),
            'phone'=>$user->getPhone(),
            'photo'=>$user->getPhoto(),
            'biography'=>$user->getBiography(),
            'roles'=>$user->getRoles(),
        ], Response::HTTP_OK);
    }

    //-------------------------------------------Eliminar usuarios--------------------------------------------------------------------------------------
    #[Route('api/user/{id}', name:'user_delete', methods:['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

       if(!$user){
        return new JsonResponse(['error'=>'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
       }

       //Eliminas al usuario
       $em->remove($user);
       $em->flush();

       //Respondes con mensaje de éxito
       return new JsonResponse(['message'=>'Usuario eliminado correctamente'], Response::HTTP_OK);

    }


    /*----------------------------Métodos obtener curso por usuario, para inscribirse y desisncribrise a un curso. Está en este controller porque
    la acción recae sobre el usuario!!..------------------------- */

    #[Route('api/user/{userId}/courses', name: 'user_get_courses', methods: ['GET'])]
    public function getCoursesForUser(int $userId, UserRepository $userRepo): JsonResponse
    {
        $user = $userRepo->find($userId);
    
        if (!$user) {
            return new JsonResponse(['message' => 'Usuario no encontrado'], 404);
        }
    
        // Obtener los cursos del usuario
        $courses = $user->getCourses(); // Método que devuelve los cursos asociados al usuario
    
        $courseData = [];
    
        foreach ($courses as $course) {
            $courseData[] = [
                'id' => $course->getId(),
                'title' => $course->getTitle(),
                'content' => $course->getContent(),
                'category' => $course->getCategory()?->getName(),
                'subcategory' => $course->getSubcategory()?->getName(),
            ];
        }
    
        return new JsonResponse($courseData);
    }
    
    
    #[Route('api/user/{userId}/courses/{courseId}', name: 'user_add_course', methods: ['POST'])]
    public function addCourseToUser(int $userId, int $courseId, EntityManagerInterface $em, UserRepository $userRepo, CourseRepository $courseRepo ): JsonResponse
    {
        $user = $userRepo->find($userId);
        $course = $courseRepo->find($courseId);

        if(!$user || !$course){
            return new JsonResponse(['message' => 'User o curso no encontrado'], 404);
        }

        $user->addCourse($course);
        $em->flush();

        return new JsonResponse(['message'=>'Curso añadido al usuario']);
    }

    #[Route('api/user/{userId}/courses/{courseId}', name: 'user_remove_course', methods: ['DELETE'])]
    public function removeCourseFromUser(int $userId, int $courseId, EntityManagerInterface $em, UserRepository $userRepo, CourseRepository $courseRepo): JsonResponse
    {
        $user = $userRepo->find($userId);
        $course = $courseRepo->find($courseId);

        if(!$user || !$course){
            return new JsonResponse(['message' => 'User o curso no encontrado'], 404);
        }

        $user->removeCourse($course);
        $em->flush();

        return new JsonResponse(['message' => 'Curso eliminado del usuario']);
    }

    /*---------------------Método para que devuelva los usuarios y poder comunicarse por mensaje------------------------ */
    #[Route('/api/users-message', name: 'users_message_list', methods: ['GET'])]
    public function listUsers(SerializerInterface $serializer): JsonResponse
    {
        $users = $this->em->getRepository(User::class)->findAll();
        $data = $serializer->serialize($users, 'json', ['groups' => ['user:read']]);
        
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }







} //cierre controlador
