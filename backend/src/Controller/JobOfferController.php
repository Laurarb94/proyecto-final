<?php

namespace App\Controller;
 
 use App\Entity\JobOffer;
 use App\Repository\JobOfferRepository;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Attribute\Route;
 
 #[Route('api/job-offers')] //así estableces ruta base
 final class JobOfferController extends AbstractController
 {
     //Obtener todas las ofertas de emplo
     #[Route('', methods: ['GET'])]
     public function index(JobOfferRepository $repo): JsonResponse
     {
         $offers = $repo->findAll();
         $data = [];
 
         foreach($offers as $offer){
            $subcategory = $offer->getSubcategory(); //añades la subcategoría por que si no, no la filtra!!

             $data[] = [
                 'id' =>$offer->getId(),
                 'title'=>$offer->getTitle(),
                 'description'=>$offer->getDescription(),
                 'location'=>$offer->getLoation(),
                 'createdAt'=>$offer->getCreatedAt()->format('Y-m-d'),
                 'salary'=>$offer->getSalary(),
                 'subcategory' => $subcategory ? [
                    'id' => $subcategory->getId(),
                    'name' => $subcategory->getName(),
                 ] : null,
             ];
         }
 
         return $this->json($data);
     }
 
     //Ofertas por su id
     #[Route('/{id}', methods: ['GET'])]
     public function show(JobOffer $offer): JsonResponse
     {
         return $this->json([
             'id' =>$offer->getId(),
             'title'=>$offer->getTitle(),
             'description'=>$offer->getDescription(),
             'location'=>$offer->getLoation(),
             'createdAt'=>$offer->getCreatedAt()->format('Y-m-d'),
             'salary'=>$offer->getSalary(),
         ]);
     }
 
 
    //Crear una oferta
    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SubcategoryRepository $subcategoryRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $offer = new JobOffer();
        $offer->setTitle($data['title']);
        $offer->setDescription($data['description']);
        $offer->setLoation($data['loation']);
        $offer->setCreatedAt(new \DateTimeImmutable());
        $offer->setSalary($data['salary']);

        //Añado esta opción por si en algún momento se crean ofertas, no por fixtures sino por formulario 
        if(isset($data['subcategoryId'])){
            $subcategory = $subcategoryRepo->find($data['subactegoryId']);
            if($subcategory){
                $offer->setSubcategory($subcategory);
            }
        }
          
        $em->persist($offer);
        $em->flush();

        return $this->json(['message'=>'Oferta creada con éxito'], 201);        
    }
 
     //Actualizar oferta
     #[Route('/{id}', methods: ['PUT'])]
     public function update(Request $request, EntityManagerInterface $em, JobOffer $offer): JsonResponse
     {
         $data = json_decode($request->getContent(), true);
 
         $offer->setTitle($data['title']);
         $offer->setDescription($data['description']);
         $offer->setLoation($data['loation']);
         $offer->setSalary($data['salary']);
 
         $em->flush();
 
         return $this->json(['message'=>'Oferta actualizada con éxito']);
     }
 
     //Eliminar oferta
     #[Route('/{id}', methods: ['DELETE'])]
     public function delete(JobOffer $offer, EntityManagerInterface $em): JsonResponse
     {
         $em->remove($offer);
         $em->flush();
 
         return $this->json(['message'=>'Oferta eliminada con éxito']);
     }
 
}
