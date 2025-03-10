<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         // Crear categorías
         $informatica = new Category();
         $informatica->setName('Informática');
         $manager->persist($informatica);
 
         $salud = new Category();
         $salud->setName('Salud');
         $manager->persist($salud);
 
         $marketing = new Category();
         $marketing->setName('Marketing');
         $manager->persist($marketing);

         $cienciasSociales = new Category();
         $cienciasSociales->setName('Ciencias Sociales');
         $manager->persist($cienciasSociales);

         $humanidades = new Category();
         $humanidades->setName('Humanidades');
         $manager->persist($humanidades);
 
         $hosteleria = new Category();
         $hosteleria->setName('Hostelería y Turismo');
         $manager->persist($hosteleria);
 
         $matematicas = new Category();
         $matematicas->setName('Matemáticas y Estadística');
         $manager->persist($matematicas);
 
         $ingenierias = new Category();
         $ingenierias->setName('Ingenierías');
         $manager->persist($ingenierias);
 
         // Guardar las categorías en la base de datos
         $manager->flush();
    }
}
