<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProyectoCrudApiTest extends WebTestCase
{
    private function authenticate(KernelBrowser $client): string
    {
        $client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'usuarioId' => 'superadmin',
            'password' => 'superadminpass'
        ]));

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);

        return $data['token'];
    }

    public function testCrearYEliminarProyecto(): void
    {
        $client = static::createClient();
        $token = $this->authenticate($client);

        $client->request('POST', '/api/proyectos', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ], json_encode([
            'nombre' => 'Proyecto Test CRUD',
            'descripcion' => 'Proyecto creado en test funcional',
            'fechaInicio' => (new \DateTimeImmutable('+1 day'))->format(DATE_ATOM),
            'fechaFin' => (new \DateTimeImmutable('+10 days'))->format(DATE_ATOM),
        ]));

        $this->assertResponseStatusCodeSame(201);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $data);
        $proyectoId = $data['id'];

        $client->request('GET', '/api/proyectos', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertResponseIsSuccessful();
        $proyectos = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($proyectos);

        $client->request('DELETE', '/api/proyectos/' . $proyectoId, [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
        ]);
        $this->assertResponseStatusCodeSame(200);
    }
}

