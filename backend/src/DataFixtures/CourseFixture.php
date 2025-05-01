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
                       ->setContent($this->generateRandomContent($subcategory->getName())) // Generar contenido aleatorio
                       ->setCategory($category)
                       ->setSubcategory($subcategory);

                $manager->persist($course);
            }
        }
        
        $manager->flush();
    }

    private function generateRandomContent($subcategoryName)
    {
        $contents = [
            'Este curso proporciona una comprensión profunda sobre ' . $subcategoryName . '.',
            'Aprende los conceptos más importantes de ' . $subcategoryName . ' a través de ejemplos prácticos.',
            'Curso avanzado en ' . $subcategoryName . ', dirigido a profesionales del área.',
            'En este curso, explorarás las técnicas más actuales en ' . $subcategoryName . '.',
            'Contenido de alto nivel sobre ' . $subcategoryName . ' para mejorar tus habilidades.'
        ];

        return $contents[array_rand($contents)];
    }

    public function getOrder(): int
    {
        return 4;
    }
}
