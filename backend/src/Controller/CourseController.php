<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/courses')]
final class CourseController extends AbstractController{

    #[Route('', methods: ['GET'])]
    public function index(CourseRepository $repo): Response
    {
        $courses = $repo->findAll();
        $data = [];

        foreach($courses as $course){
            $data[] = [
                'id' => $course->getId(),
                'name' => $course->getName(),
                'description' => $course->getDescription(),
                'category' => $course->getCategory()?->getName(),
                'sucgateory' => $course->getSubcategory()?->getName(),
            ];
        }


        return $this->json($data);
    }
}
