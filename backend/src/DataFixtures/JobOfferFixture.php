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
                    ->setDescription('Descripción de la oferta para ' . $subcategory->getName())
                    ->setLoation('Ubicación de la oferta en ' . $subcategory->getName())
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setSalary('No especificado')
                    ->setCategory($category)
                    ->setSubcategory($subcategory);

                // Asignar un "empresa" aleatoria (puedes crear usuarios de tipo empresa en otro fixture o hacerlo dinámicamente)
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
}
