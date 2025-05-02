<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


final class CategoryController extends AbstractController{

    #[Route('/api/categories', name: 'api_categories', methods: ['GET'])]
    public function getCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        
        $data = [];
        
        foreach ($categories as $category) {
            $subcategories = [];
            
            foreach ($category->getSubcategories() as $sub) {
                $subcategories[] = [
                    'id' => $sub->getId(),
                    'name' => $sub->getName(),
                ];
            }
            
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'subcategories' => $subcategories,
            ];
        }
        
        return $this->json($data);
    }
}
