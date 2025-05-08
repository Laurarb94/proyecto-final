<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                'title' => $course->getTitle(),
                'content' => $course->getContent(),
                'category' => $course->getCategory()?->getName(),
                'subcategory' => $course->getSubcategory()?->getName(),
            ];
        }


        return $this->json($data);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Course $course): JsonResponse
    {
        return $this->json([
            'id' => $course->getId(),
            'title' => $course->getTitle(),
            'content' => $course->getContent(),
            'category' => $course->getCategory()?->getName(),
            'subcategory' => $course->getSubcategory()?->getName(),
        ]);
    }
}
