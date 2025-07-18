<?php

namespace App\Controller\Api;

use App\Entity\Tarea;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

#[IsGranted('ROLE_EMPLEADO')]
#[Route('/api/tareas')]
#[OA\Tag(name: 'Tareas')]
class TareaApiController extends AbstractController
{
    #[Route('', name: 'api_tarea_index', methods: ['GET'])]
    #[OA\Get(
        summary: 'Listar todas las tareas',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de tareas',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'nombre', type: 'string'),
                            new OA\Property(property: 'descripcion', type: 'string'),
                            new OA\Property(property: 'fechaInicio', type: 'string', format: 'date'),
                            new OA\Property(property: 'fechaFin', type: 'string', format: 'date', nullable: true),
                            new OA\Property(property: 'estado', type: 'string'),
                            new OA\Property(property: 'proyecto', type: 'integer', nullable: true),
                            new OA\Property(property: 'tipologia', type: 'integer', nullable: true),
                        ]
                    )
                )
            )
        ]
    )]
    public function index(TareaRepository $tareaRepository): JsonResponse
    {
        $tareas = $tareaRepository->findAll();

        $data = array_map(function (Tarea $tarea) {
            return [
                'id' => $tarea->getId(),
                'nombre' => $tarea->getNombre(),
                'descripcion' => $tarea->getDescripcion(),
                'fechaInicio' => $tarea->getFechaInicio()?->format('Y-m-d'),
                'fechaFin' => $tarea->getFechaFin()?->format('Y-m-d'),
                'estado' => $tarea->getEstado(),
                'proyecto' => $tarea->getProyecto()?->getId(),
                'tipologia' => $tarea->getTipologia()?->getId(),
            ];
        }, $tareas);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_tarea_show', methods: ['GET'])]
    #[OA\Get(
        summary: 'Obtener una tarea por ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detalles de la tarea',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'nombre', type: 'string'),
                        new OA\Property(property: 'descripcion', type: 'string'),
                        new OA\Property(property: 'fechaInicio', type: 'string', format: 'date'),
                        new OA\Property(property: 'fechaFin', type: 'string', format: 'date', nullable: true),
                        new OA\Property(property: 'estado', type: 'string'),
                        new OA\Property(property: 'proyecto', type: 'integer', nullable: true),
                        new OA\Property(property: 'tipologia', type: 'integer', nullable: true),
                    ]
                )
            )
        ]
    )]
    public function show(Tarea $tarea): JsonResponse
    {
        $data = [
            'id' => $tarea->getId(),
            'nombre' => $tarea->getNombre(),
            'descripcion' => $tarea->getDescripcion(),
            'fechaInicio' => $tarea->getFechaInicio()?->format('Y-m-d'),
            'fechaFin' => $tarea->getFechaFin()?->format('Y-m-d'),
            'estado' => $tarea->getEstado(),
            'proyecto' => $tarea->getProyecto()?->getId(),
            'tipologia' => $tarea->getTipologia()?->getId(),
        ];

        return $this->json($data);
    }

    #[Route('', name: 'api_tarea_create', methods: ['POST'])]
    #[OA\Post(
        summary: 'Crear una nueva tarea',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nombre', 'descripcion', 'fechaInicio'],
                properties: [
                    new OA\Property(property: 'nombre', type: 'string'),
                    new OA\Property(property: 'descripcion', type: 'string'),
                    new OA\Property(property: 'fechaInicio', type: 'string', format: 'date'),
                    new OA\Property(property: 'fechaFin', type: 'string', format: 'date', nullable: true),
                    new OA\Property(property: 'estado', type: 'string'),
                    new OA\Property(property: 'proyecto', type: 'integer', nullable: true),
                    new OA\Property(property: 'tipologia', type: 'integer', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tarea creada',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'status', type: 'string'),
                        new OA\Property(property: 'id', type: 'integer')
                    ]
                )
            )
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $tarea = new Tarea();
        $tarea->setNombre($data['nombre'] ?? '');
        $tarea->setDescripcion($data['descripcion'] ?? '');
        $tarea->setFechaInicio(new \DateTime($data['fechaInicio'] ?? 'now'));
        $tarea->setFechaFin(isset($data['fechaFin']) ? new \DateTime($data['fechaFin']) : null);
        $tarea->setEstado($data['estado'] ?? 'pendiente');

        if (isset($data['proyecto'])) {
            $proyecto = $em->getRepository(\App\Entity\Proyecto::class)->find($data['proyecto']);
            $tarea->setProyecto($proyecto);
        }

        if (isset($data['tipologia'])) {
            $tipologia = $em->getRepository(\App\Entity\Tipologia::class)->find($data['tipologia']);
            $tarea->setTipologia($tipologia);
        }

        $em->persist($tarea);
        $em->flush();

        return $this->json(['status' => 'Tarea creada', 'id' => $tarea->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_tarea_update', methods: ['PUT', 'PATCH'])]
    #[OA\Put(
        summary: 'Actualizar una tarea existente',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nombre', type: 'string'),
                    new OA\Property(property: 'descripcion', type: 'string'),
                    new OA\Property(property: 'fechaInicio', type: 'string', format: 'date'),
                    new OA\Property(property: 'fechaFin', type: 'string', format: 'date'),
                    new OA\Property(property: 'estado', type: 'string'),
                    new OA\Property(property: 'proyecto', type: 'integer'),
                    new OA\Property(property: 'tipologia', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tarea actualizada',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'status', type: 'string')]
                )
            )
        ]
    )]
    public function update(Request $request, Tarea $tarea, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nombre'])) $tarea->setNombre($data['nombre']);
        if (isset($data['descripcion'])) $tarea->setDescripcion($data['descripcion']);
        if (isset($data['fechaInicio'])) $tarea->setFechaInicio(new \DateTime($data['fechaInicio']));
        if (isset($data['fechaFin'])) $tarea->setFechaFin(new \DateTime($data['fechaFin']));
        if (isset($data['estado'])) $tarea->setEstado($data['estado']);

        if (isset($data['proyecto'])) {
            $proyecto = $em->getRepository(\App\Entity\Proyecto::class)->find($data['proyecto']);
            $tarea->setProyecto($proyecto);
        }

        if (isset($data['tipologia'])) {
            $tipologia = $em->getRepository(\App\Entity\Tipologia::class)->find($data['tipologia']);
            $tarea->setTipologia($tipologia);
        }

        $em->flush();

        return $this->json(['status' => 'Tarea actualizada']);
    }

    #[Route('/{id}', name: 'api_tarea_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Eliminar una tarea',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tarea eliminada',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'status', type: 'string')]
                )
            )
        ]
    )]
    public function delete(Tarea $tarea, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($tarea);
        $em->flush();

        return $this->json(['status' => 'Tarea eliminada']);
    }
}

