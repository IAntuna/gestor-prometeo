<?php

namespace App\Controller;

use App\Repository\ProyectoRepository;
use App\Repository\TareaRepository;
use App\Repository\RegistroDeHorasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        ProyectoRepository $proyectoRepository,
        TareaRepository $tareaRepository,
        RegistroDeHorasRepository $registroDeHorasRepository
    ): Response {
        $totalProyectos = $proyectoRepository->count([]);
        $totalTareas = $tareaRepository->count([]);
        $totalHoras = $registroDeHorasRepository->createQueryBuilder('r')
            ->select('SUM(r.horas)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('dashboard/index.html.twig', [
            'totalProyectos' => $totalProyectos,
            'totalTareas' => $totalTareas,
            'totalHoras' => $totalHoras,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Esta ruta de logout nunca debería ser alcanzada directamente.');
    }
}
