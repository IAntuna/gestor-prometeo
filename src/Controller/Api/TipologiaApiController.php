<?php

namespace App\Controller\Api;

use App\Entity\Tipologia;
use App\Repository\TipologiaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Tipologías')]
#[IsGranted('ROLE_ADMIN')]
#[Route('/api/tipologias')]
class TipologiaApiController extends AbstractController
{
    #[Route('', name: 'api_tipologia_index', methods: ['GET'])]
    #[OA\Get(
        summary: 'Listar todas las tipologías',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de tipologías',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'nombre', type: 'string'),
                            new OA\Property(property: 'descripcion', type: 'string'),
                        ]
                    )
                )
            )
        ]
    )]
    public function index(TipologiaRepository $tipologiaRepository): JsonResponse
    {
        $tipologias = $tipologiaRepository->findAll();

        $data = array_map(function (Tipologia $tipologia) {
            return [
                'id' => $tipologia->getId(),
                'nombre' => $tipologia->getNombre(),
                'descripcion' => $tipologia->getDescripcion(),
            ];
        }, $tipologias);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'api_tipologia_show', methods: ['GET'])]
    #[OA\Get(
        summary: 'Obtener una tipología por ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Detalle de la tipología',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'nombre', type: 'string'),
                        new OA\Property(property: 'descripcion', type: 'string'),
                    ]
                )
            )
        ]
    )]
    public function show(Tipologia $tipologia): JsonResponse
    {
        $data = [
            'id' => $tipologia->getId(),
            'nombre' => $tipologia->getNombre(),
            'descripcion' => $tipologia->getDescripcion(),
        ];

        return $this->json($data);
    }

    #[Route('', name: 'api_tipologia_create', methods: ['POST'])]
    #[OA\Post(
        summary: 'Crear una nueva tipología',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nombre', 'descripcion'],
                properties: [
                    new OA\Property(property: 'nombre', type: 'string'),
                    new OA\Property(property: 'descripcion', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tipología creada',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string'),
                        new OA\Property(property: 'id', type: 'integer'),
                    ]
                )
            )
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $tipologia = new Tipologia();
        $tipologia->setNombre($data['nombre'] ?? '');
        $tipologia->setDescripcion($data['descripcion'] ?? '');

        $em->persist($tipologia);
        $em->flush();

        return $this->json(['status' => 'Tipología creada', 'id' => $tipologia->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_tipologia_update', methods: ['PUT', 'PATCH'])]
    #[OA\Put(
        summary: 'Actualizar una tipología existente',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'nombre', type: 'string'),
                    new OA\Property(property: 'descripcion', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tipología actualizada',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'status', type: 'string')]
                )
            )
        ]
    )]
    public function update(Request $request, Tipologia $tipologia, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['nombre'])) {
            $tipologia->setNombre($data['nombre']);
        }
        if (isset($data['descripcion'])) {
            $tipologia->setDescripcion($data['descripcion']);
        }

        $em->flush();

        return $this->json(['status' => 'Tipología actualizada']);
    }

    #[Route('/{id}', name: 'api_tipologia_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Eliminar una tipología',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tipología eliminada',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'status', type: 'string')]
                )
            )
        ]
    )]
    public function delete(Tipologia $tipologia, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($tipologia);
        $em->flush();

        return $this->json(['status' => 'Tipología eliminada']);
    }
}

