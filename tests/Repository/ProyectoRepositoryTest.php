<?php

namespace App\Tests\Repository;

use App\Entity\Proyecto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProyectoRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    public function testFindAllProyectos(): void
    {
        $repository = $this->entityManager->getRepository(Proyecto::class);

        if (count($repository->findAll()) === 0) {
            $proyecto = new Proyecto();
            $proyecto->setNombre('Proyecto Test');
            $proyecto->setDescripcion('Proyecto de prueba para test');
            $proyecto->setFechaInicio(new \DateTimeImmutable());
            $proyecto->setFechaFin((new \DateTimeImmutable())->modify('+30 days'));

            $this->entityManager->persist($proyecto);
            $this->entityManager->flush();
        }

        $proyectos = $repository->findAll();

        $this->assertGreaterThan(
            0,
            count($proyectos),
            'Debe haber al menos un proyecto en la base de datos'
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
