<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubcategoryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Obtener las categorías ya existentes
        $informatica = $manager->getRepository(Category::class)->findOneBy(['name' => 'Informática']);
        $salud = $manager->getRepository(Category::class)->findOneBy(['name' => 'Salud']);
        $marketing = $manager->getRepository(Category::class)->findOneBy(['name' => 'Marketing']);
        $cienciasSociales = $manager->getRepository(Category::class)->findOneBy(['name' => 'Ciencias Sociales']);
        $humanidades = $manager->getRepository(Category::class)->findOneBy(['name' => 'Humanidades']);
        $hosteleria = $manager->getRepository(Category::class)->findOneBy(['name' => 'Hostelería y Turismo']);
        $matematicas = $manager->getRepository(Category::class)->findOneBy(['name' => 'Matemáticas y Estadística']);
        $ingenierias = $manager->getRepository(Category::class)->findOneBy(['name' => 'Ingenierías']);

        //Informática.
        $sub1 = new Subcategory();
        $sub1->setName('Desarrollador/a Web');
        $sub1->setCategory($informatica);
        $manager->persist($sub1);

        $sub2 = new Subcategory();
        $sub2->setName('Técnico/a Informático/a');
        $sub2->setCategory($informatica);
        $manager->persist($sub2);

        $sub3 = new Subcategory();
        $sub3->setName('Desarrollador/a BackEnd');
        $sub3->setCategory($informatica);
        $manager->persist($sub3);

        $sub4 = new Subcategory();
        $sub4->setName('Desarrollador/a Frontend');
        $sub4->setCategory($informatica);
        $manager->persist($sub4);

        $sub5 = new Subcategory();
        $sub5->setName('Técnico/a en Redes');
        $sub5->setCategory($informatica);
        $manager->persist($sub5);

        // Salud
        $sub6 = new Subcategory();
        $sub6->setName('Médico/a General');
        $sub6->setCategory($salud);
        $manager->persist($sub6);

        $sub7 = new Subcategory();
        $sub7->setName('Médico/a Especialista');
        $sub7->setCategory($salud);
        $manager->persist($sub7);

        $sub8 = new Subcategory();
        $sub8->setName('Pediatra');
        $sub8->setCategory($salud);
        $manager->persist($sub8);

        $sub9 = new Subcategory();
        $sub9->setName('Médico/a de urgencias');
        $sub9->setCategory($salud);
        $manager->persist($sub9);

        $sub10 = new Subcategory();
        $sub10->setName('Enfermero/a');
        $sub10->setCategory($salud);
        $manager->persist($sub10);

        $sub11 = new Subcategory();
        $sub11->setName('Farmaceútico/a');
        $sub11->setCategory($salud);
        $manager->persist($sub11);

        $sub12 = new Subcategory();
        $sub12->setName('Técnico/a de laboratorio');
        $sub12->setCategory($salud);
        $manager->persist($sub12);

        $sub13 = new Subcategory();
        $sub13->setName('Psicólogo/a');
        $sub13->setCategory($salud);
        $manager->persist($sub13);


        $sub14 = new Subcategory();
        $sub14->setName('Psicólogo/a Clínica');
        $sub14->setCategory($salud);
        $manager->persist($sub14);

        // Marketing
        $sub15 = new Subcategory();
        $sub15->setName('SEO Specialist');
        $sub15->setCategory($marketing);
        $manager->persist($sub15);

        $sub16 = new Subcategory();
        $sub16->setName('Community Manager');
        $sub16->setCategory($marketing);
        $manager->persist($sub16);

        //Sociales
        $sub17 = new Subcategory();
        $sub17->setName('Sociólogo/a');
        $sub17->setCategory($cienciasSociales);
        $manager->persist($sub17);

        $sub18 = new Subcategory();
        $sub18->setName('Trabajador/a social');
        $sub18->setCategory($cienciasSociales);
        $manager->persist($sub18);

        $sub19 = new Subcategory();
        $sub19->setName('Educador/a social');
        $sub19->setCategory($cienciasSociales);
        $manager->persist($sub19);

        $sub20 = new Subcategory();
        $sub20->setName('Jurista');
        $sub20->setCategory($cienciasSociales);
        $manager->persist($sub20);

        $sub21 = new Subcategory();
        $sub21->setName('Abogado/a');
        $sub21->setCategory($cienciasSociales);
        $manager->persist($sub21);

        //Humanidades
        $sub22 = new Subcategory();
        $sub22->setName('Filósofo/a');
        $sub22->setCategory($humanidades);
        $manager->persist($sub22);

        $sub23 = new Subcategory();
        $sub23->setName('Historiador/a');
        $sub23->setCategory($humanidades);
        $manager->persist($sub23);

        $sub24 = new Subcategory();
        $sub24->setName('Historiador/a del Arte');
        $sub24->setCategory($humanidades);
        $manager->persist($sub24);

        $sub25 = new Subcategory();
        $sub25->setName('Letras Clásicas');
        $sub25->setCategory($humanidades);
        $manager->persist($sub25);

        $sub26 = new Subcategory();
        $sub26->setName('Arqueolólogo/a');
        $sub26->setCategory($humanidades);
        $manager->persist($sub26);

        $sub27 = new Subcategory();
        $sub27->setName('Geógrafo/a');
        $sub27->setCategory($humanidades);
        $manager->persist($sub27);

        $sub28 = new Subcategory();
        $sub28->setName('Politólogo/a');
        $sub28->setCategory($humanidades);
        $manager->persist($sub28);

        //Hostelería y turismo
        $sub29 = new Subcategory();
        $sub29->setName('Chef');
        $sub29->setCategory($hosteleria);
        $manager->persist($sub29);

        $sub30 = new Subcategory();
        $sub30->setName('Camarero/a');
        $sub30->setCategory($hosteleria);
        $manager->persist($sub30);

        $sub31 = new Subcategory();
        $sub31->setName('Recepcionista');
        $sub31->setCategory($hosteleria);
        $manager->persist($sub31);

        $sub32 = new Subcategory();
        $sub32->setName('Barman');
        $sub32->setCategory($hosteleria);
        $manager->persist($sub32);

        $sub33 = new Subcategory();
        $sub33->setName('Director/a de Hotel');
        $sub33->setCategory($hosteleria);
        $manager->persist($sub33);

        $sub34 = new Subcategory();
        $sub34->setName('Event Planner');
        $sub34->setCategory($hosteleria);
        $manager->persist($sub34);

        $sub35 = new Subcategory();
        $sub35->setName('Guía Turístico/a');
        $sub35->setCategory($hosteleria);
        $manager->persist($sub35);

        $sub36 = new Subcategory();
        $sub36->setName('Turismo Rural');
        $sub36->setCategory($hosteleria);
        $manager->persist($sub36);

        // Matemáticas
        $sub37 = new Subcategory();
        $sub37->setName('Álgebra');
        $sub37->setCategory($matematicas);
        $manager->persist($sub37);

        $sub38 = new Subcategory();
        $sub38->setName('Cálculo');
        $sub38->setCategory($matematicas);
        $manager->persist($sub38);

        $sub39 = new Subcategory();
        $sub39->setName('Teoría de Juegos');
        $sub39->setCategory($matematicas);
        $manager->persist($sub39);

        $sub40 = new Subcategory();
        $sub40->setName('Matemáticas Financieras');
        $sub40->setCategory($matematicas);
        $manager->persist($sub40);

        $sub41 = new Subcategory();
        $sub41->setName('Modelos Estocásticos');
        $sub41->setCategory($matematicas);
        $manager->persist($sub41);

        $sub42 = new Subcategory();
        $sub42->setName('Criptografía');
        $sub42->setCategory($matematicas);
        $manager->persist($sub42);

        // Subcategorías de Ingenierías (continuación)
        $sub43 = new Subcategory();
        $sub43->setName('Ingeniería de Software');
        $sub43->setCategory($ingenierias);
        $manager->persist($sub43);

        $sub44 = new Subcategory();
        $sub44->setName('Ingeniería de Sistemas');
        $sub44->setCategory($ingenierias);
        $manager->persist($sub44);

        $sub45 = new Subcategory();
        $sub45->setName('Ingeniería de Redes');
        $sub45->setCategory($ingenierias);
        $manager->persist($sub45);

        $sub46 = new Subcategory();
        $sub46->setName('Ingeniería en Computación');
        $sub46->setCategory($ingenierias);
        $manager->persist($sub46);

        $sub47 = new Subcategory();
        $sub47->setName('Ingeniería Automotriz');
        $sub47->setCategory($ingenierias);
        $manager->persist($sub47);

        $sub48 = new Subcategory();
        $sub48->setName('Ingeniería en Energía');
        $sub48->setCategory($ingenierias);
        $manager->persist($sub48);

        $sub49 = new Subcategory();
        $sub49->setName('Ingeniería de Caminos');
        $sub49->setCategory($ingenierias);
        $manager->persist($sub49);

        $sub50 = new Subcategory();
        $sub50->setName('Ingeniería Naval');
        $sub50->setCategory($ingenierias);
        $manager->persist($sub50);




        // Guardar todos los cambios en la base de datos
        $manager->flush();
    }
}
