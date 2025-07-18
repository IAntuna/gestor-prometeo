<?php

namespace App\Controller\Api;

use App\Entity\RegistroDeHoras;
use App\Repository\RegistroDeHorasRepository;
use App\Entity\Tarea;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

#[IsGranted('ROLE_USER')]
#[Route('/api/registro-de-horas')]
#[OA\Tag(name: 'Registro de Horas')]
class RegistroDeHorasApiController extends AbstractController
{
    #[Route('', name: 'api_registro_horas_index', methods: ['GET'])]
    #[OA\Get(
        summary: 'Listar todos los registros de horas',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de registros',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'tarea', type: 'integer', nullable: true),
                            new OA\Property(property: 'usuario', type: 'string'),
                            new OA\Property(property: 'fecha', type: 'string', format: 'date'),
                            new OA\Property(property: 'horas', type: 'number', format: 'float'),
                        ],
                        type: 'object'
                    )
                )
            )
        ]
    )]
    public function index(RegistroDeHorasRepository $repository): JsonResponse
    {
        $registros = $repository->findAll();

        $data = array_map(function (RegistroDeHoras $registro) {
            return [
                'id' => $registro->getId(),
                'tarea' => $registro->getTarea()?->getId(),
                'usuario' => $registro->getUsuario()?->getUserIdentifier(),
                'fecha' => $registro->getFecha()?->format('Y-m-d'),
                'horas' => $registro->getHoras(),
            ];
        }, $registros);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_registro_horas_show', methods: ['GET'])]
    #[OA\Get(
        summary: 'Obtener un registro por ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detalles del registro',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'tarea', type: 'integer', nullable: true),
                        new OA\Property(property: 'usuario', type: 'string'),
                        new OA\Property(property: 'fecha', type: 'string', format: 'date'),
                        new OA\Property(property: 'horas', type: 'number', format: 'float'),
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function show(RegistroDeHoras $registro): JsonResponse
    {
        $data = [
            'id' => $registro->getId(),
            'tarea' => $registro->getTarea()?->getId(),
            'usuario' => $registro->getUsuario()?->getUserIdentifier(),
            'fecha' => $registro->getFecha()?->format('Y-m-d'),
            'horas' => $registro->getHoras(),
        ];

        return $this->json($data);
    }

    #[Route('', name: 'api_registro_horas_create', methods: ['POST'])]
    #[OA\Post(
        summary: 'Crear un nuevo registro de horas',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['fecha', 'horas'],
                properties: [
                    new OA\Property(property: 'tarea', type: 'integer', nullable: true),
                    new OA\Property(property: 'fecha', type: 'string', format: 'date'),
                    new OA\Property(property: 'horas', type: 'number', format: 'float')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Registro creado',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string'),
                        new OA\Property(property: 'id', type: 'integer')
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $registro = new RegistroDeHoras();
        $registro->setFecha(new \DateTime($data['fecha'] ?? 'now'));
        $registro->setHoras($data['horas'] ?? 0);
        $registro->setUsuario($this->getUser());

        if (isset($data['tarea'])) {
            $tarea = $em->getRepository(Tarea::class)->find($data['tarea']);
            $registro->setTarea($tarea);
        }

        $em->persist($registro);
        $em->flush();

        return $this->json(['status' => 'Registro creado', 'id' => $registro->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_registro_horas_update', methods: ['PUT', 'PATCH'])]
    #[OA\Put(
        summary: 'Actualizar un registro de horas',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'fecha', type: 'string', format: 'date'),
                    new OA\Property(property: 'horas', type: 'number', format: 'float'),
                    new OA\Property(property: 'tarea', type: 'integer', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Registro actualizado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'status', type: 'string')],
                    type: 'object'
                )
            )
        ]
    )]
    public function update(Request $request, RegistroDeHoras $registro, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['fecha'])) {
            $registro->setFecha(new \DateTime($data['fecha']));
        }
        if (isset($data['horas'])) {
            $registro->setHoras($data['horas']);
        }
        if (isset($data['tarea'])) {
            $tarea = $em->getRepository(Tarea::class)->find($data['tarea']);
            $registro->setTarea($tarea);
        }

        $em->flush();

        return $this->json(['status' => 'Registro actualizado']);
    }

    #[Route('/{id}', name: 'api_registro_horas_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Eliminar un registro de horas',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Registro eliminado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'status', type: 'string')],
                    type: 'object'
                )
            )
        ]
    )]
    public function delete(RegistroDeHoras $registro, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($registro);
        $em->flush();

        return $this->json(['status' => 'Registro eliminado']);
    }
}

