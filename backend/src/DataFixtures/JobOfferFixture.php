<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\JobOffer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobOfferFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Obtener todas las categorías
        $categories = $manager->getRepository(Category::class)->findAll();

        // Crear ofertas de empleo para cada categoría y subcategoría
        foreach ($categories as $category) {
            $subcategories = $category->getSubcategories();

            foreach ($subcategories as $subcategory) {
                $jobOffer = new JobOffer();
                $jobOffer->setTitle('Oferta de empleo para ' . $subcategory->getName())
                    ->setDescription($this->generateRandomDescription($subcategory->getName()))
                    ->setLoation($this->generateRandomLoation($subcategory->getName()))
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setSalary($this->generateRandomSalary())
                    ->setCategory($category)
                    ->setSubcategory($subcategory);

                // Asignar un "empresa" aleatoria
                $company = $manager->getRepository(User::class)->findOneBy(['roles' => 'ROLE_COMPANY']);
                if ($company) {
                    $jobOffer->setCompany($company);
                }

                $manager->persist($jobOffer);
            }
        }

        // Guardar las ofertas de empleo en la base de datos
        $manager->flush();
    }

    // Funciones de generación
    private function generateRandomDescription($subcategoryName)
    {
        $descriptions = [
            'Se busca un profesional para trabajar en el área de ' . $subcategoryName . '.',
            'Oferta de empleo para la gestión de proyectos en ' . $subcategoryName . '.',
            'Buscamos un experto en ' . $subcategoryName . ' para unirse a nuestro equipo.',
            'Vacante disponible para profesionales en ' . $subcategoryName . ' con experiencia.',
            'Vacante disponible en ' . $subcategoryName . ' no es necesaria experiencia, solo muchas ganas de trabajar',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function generateRandomLoation($subcategoryName)
    {
        $loations = [
            'Madrid',
            'Barcelona',
            'Valencia',
            'Sevilla',
            'Bilbao',
            'Zaragoza',
            'Granada',
            'Alicante',
        ];

        return $loations[array_rand($loations)];
    }

    private function generateRandomSalary()
    {
        $salaries = [
            '1500',
            '2000',
            '2500',
            '3000',
            '3500',
            '4000',
            'No especificado',
        ];

        return $salaries[array_rand($salaries)];
    }

    public function getOrder(): int
    {
        return 3;
    }
}
