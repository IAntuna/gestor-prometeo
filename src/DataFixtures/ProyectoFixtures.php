<?php

namespace App\DataFixtures;

use App\Entity\Proyecto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProyectoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $proyecto = new Proyecto();
            $proyecto->setNombre("Proyecto $i");
            $proyecto->setDescripcion("Descripción del proyecto $i");
            $proyecto->setFechaInicio(new \DateTimeImmutable());
            $proyecto->setFechaFin((new \DateTimeImmutable())->modify('+30 days'));
            $manager->persist($proyecto);
        }

        $manager->flush();
    }
}

