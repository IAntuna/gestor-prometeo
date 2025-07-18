<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testRolesSetterGetter(): void
    {
        $user = new User();
        $roles = ['ROLE_ADMIN', 'ROLE_GESTOR'];
        $user->setRoles($roles);

        $resultRoles = $user->getRoles();

        $this->assertContains('ROLE_ADMIN', $resultRoles);
        $this->assertContains('ROLE_GESTOR', $resultRoles);
        $this->assertContains('ROLE_USER', $resultRoles);
        $this->assertCount(count(array_unique($resultRoles)), $resultRoles);
    }

    public function testUsuarioIDSetterGetter(): void
    {
        $user = new User();
        $user->setUsuarioId('testuser');
        $this->assertEquals('testuser', $user->getUsuarioId());
    }
}
