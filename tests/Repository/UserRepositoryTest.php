<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $passwordHasher;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->entityManager = $container->get('doctrine')->getManager();
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);
    }

    public function testFindOneByUsuarioID(): void
    {
        $repository = $this->entityManager->getRepository(User::class);

        $user = new User();
        $user->setUsuarioId('testuser');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'testpass'));
        $user->setEmail('testuser@example.com');
        $user->setNombre('Test User');
        $user->setRoles(['ROLE_ADMIN']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $fetchedUser = $repository->findOneBy(['usuarioId' => 'testuser']);

        $this->assertNotNull($fetchedUser);
        $this->assertEquals('testuser', $fetchedUser->getUsuarioId());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
