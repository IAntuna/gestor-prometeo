<?php

namespace App\Controller\Api;

use App\Entity\Proyecto;
use App\Repository\ProyectoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

#[IsGranted('ROLE_GESTOR')]
#[Route('/api/proyectos')]
#[OA\Tag(name: 'Proyectos')]
class ProyectoApiController extends AbstractController
{
    #[Route('', name: 'api_proyecto_index', methods: ['GET'])]
    #[OA\Get(
        summary: 'Listar todos los proyectos',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Listado de proyectos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'nombre', type: 'string'),
                            new OA\Property(property: 'descripcion', type: 'string'),
                            new OA\Property(property: 'fechaInicio', type: 'string', format: 'date'),
                            new OA\Property(property: 'fechaFin', type: 'string', format: 'date', nullable: true),
                        ],
                        type: 'object'
                    )
                )
            )
        ]
    )]
    public function index(ProyectoRepository $proyectoRepository): JsonResponse
    {
        $proyectos = $proyectoRepository->findAll();

        $data = array_map(function (Proyecto $proyecto) {
            return [
                'id' => $proyecto->getId(),
                'nombre' => $proyecto->getNombre(),
                'descripcion' => $proyecto->getDescripcion(),
                'fechaInicio' => $proyecto->getFechaInicio()?->format('Y-m-d'),
                'fechaFin' => $proyecto->getFechaFin()?->format('Y-m-d'),
            ];
        }, $proyectos);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_proyecto_show', methods: ['GET'])]
    #[OA\Get(
        summary: 'Obtener un proyecto por ID',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detalles del proyecto',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'nombre', type: 'string'),
                        new OA\Property(property: 'descripcion', type: 'string'),
                        new OA\Property(property: 'fechaInicio', type: 'string', format: 'date'),
                        new OA\Property(property: 'fechaFin', type: 'string', format: 'date', nullable: true),
                    ],
                    type: 'object'
                )
            )
        ]
    )]
    public function show(Proyecto $proyecto): JsonResponse
    {
        $data = [
            'id' => $proyecto->getId(),
            'nombre' => $proyecto->getNombre(),
            'descripcion' => $proyecto->getDescripcion(),
            'fechaInicio' => $proyecto->getFechaInicio()?->format('Y-m-d'),
            'fechaFin' => $proyecto->getFechaFin()?->format('Y-m-d'),
        ];

        return $this->json($data);
    }

    #[Route('', name: 'api_proyecto_new', methods: ['POST'])]
    #[OA\Post(
        summary: 'Crear un nuevo proyecto',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nombre', 'descripcion', 'fechaInicio'],
                properties: [
                    new OA\Property(property: 'nombre', type: 'string'),
                    new OA\Property(property: 'descripcion', type: 'string'),
                    new OA\Property(property: 'fechaInicio', type: 'string', format: 'date'),
                    new OA\Property(property: 'fechaFin', type: 'string', format: 'date', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Proyecto creado correctamente',
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

        $proyecto = new Proyecto();
        $proyecto->setNombre($data['nombre'] ?? '');
        $proyecto->setDescripcion($data['descripcion'] ?? '');
        $proyecto->setFechaInicio(new \DateTimeImmutable($data['fechaInicio']));
        $proyecto->setFechaFin(isset($data['fechaFin']) ? new \DateTimeImmutable($data['fechaFin']) : null);

        $em->persist($proyecto);
        $em->flush();

        return $this->json(['status' => 'Proyecto creado', 'id' => $proyecto->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_proyecto_edit', methods: ['PUT', 'PATCH'])]
    #[OA\Put(
        summary: 'Actualizar un proyecto',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nombre', type: 'string'),
                    new OA\Property(property: 'descripcion', type: 'string'),
                    new OA\Property(property: 'fechaInicio', type: 'string', format: 'date'),
                    new OA\Property(property: 'fechaFin', type: 'string', format: 'date', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Proyecto actualizado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'status', type: 'string')],
                    type: 'object'
                )
            )
        ]
    )]
    public function update(Request $request, Proyecto $proyecto, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nombre'])) {
            $proyecto->setNombre($data['nombre']);
        }
        if (isset($data['descripcion'])) {
            $proyecto->setDescripcion($data['descripcion']);
        }
        if (isset($data['fechaInicio'])) {
            $proyecto->setFechaInicio(new \DateTime($data['fechaInicio']));
        }
        if (isset($data['fechaFin'])) {
            $proyecto->setFechaFin(new \DateTime($data['fechaFin']));
        }

        $em->flush();

        return $this->json(['status' => 'Proyecto actualizado']);
    }

    #[Route('/{id}', name: 'api_proyecto_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Eliminar un proyecto',
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Proyecto eliminado',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'status', type: 'string')],
                    type: 'object'
                )
            )
        ]
    )]
    public function delete(Proyecto $proyecto, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($proyecto);
        $em->flush();

        return $this->json(['status' => 'Proyecto eliminado']);
    }
}

