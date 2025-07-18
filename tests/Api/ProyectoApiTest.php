<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProyectoApiTest extends WebTestCase
{
    public function testGetProyectosSinAutenticacion(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/proyectos');

        $this->assertEquals(401, $client->getResponse()->getStatusCode(), 'Debe devolver 401 sin autenticación');
    }

    /**
     * @dataProvider usuariosProvider
     */
    public function testGetProyectosConAutenticacion(string $usuario, string $password, int $expectedStatusCode): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'usuarioId' => $usuario,
            'password' => $password
        ]));

        $this->assertResponseIsSuccessful('El login debería ser exitoso');

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data, 'El token JWT debe existir en la respuesta');

        $token = $data['token'];

        $client->request('GET', '/api/proyectos', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);

        $this->assertEquals(
            $expectedStatusCode,
            $client->getResponse()->getStatusCode(),
            sprintf('El usuario "%s" debería recibir HTTP %d', $usuario, $expectedStatusCode)
        );
    }

    public static function usuariosProvider(): array
    {
        return [
            ['superadmin', 'superadminpass', 200],
            ['admin', 'admin123', 200],
            ['gestor', 'gestorpass', 200],
            ['empleado', 'empleadopass', 403],
        ];
    }
}
