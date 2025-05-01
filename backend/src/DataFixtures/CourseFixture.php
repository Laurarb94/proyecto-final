<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Obtener todas las categorías
        $categories = $manager->getRepository(Category::class)->findAll();

        //Crear cusos para cada categoría y subcategoría
        foreach($categories as $category){
            $subcategories = $category->getSubcategories();

            foreach($subcategories as $subcategory){
                $course = new Course();
                $course->setTitle('Curso de ' . $subcategory->getName())
                       ->setContent('Contenido del curso sobre ' . $subcategory->getName())
                       ->setCategory($category)
                       ->setSubcategory($subcategory);

                $manager->persist($course);
            }
        }
        
        $manager->flush();
    }
}
