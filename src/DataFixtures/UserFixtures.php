<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['superadmin', 'superadminpass', ['ROLE_SUPERADMIN'], 'superadmin@example.com'],
            ['admin', 'admin123', ['ROLE_ADMIN'], 'admin@example.com'],
            ['gestor', 'gestorpass', ['ROLE_GESTOR'], 'gestor@example.com'],
            ['empleado', 'empleadopass', ['ROLE_EMPLEADO'], 'empleado@example.com'],
        ];

        foreach ($users as [$username, $plainPassword, $roles, $email]) {
            $user = new User();
            $user->setNombre(ucfirst($username));
            $user->setUsuarioID($username);
            $user->setEmail($email);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $user->setRoles($roles);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
