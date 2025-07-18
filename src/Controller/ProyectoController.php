<?php

namespace App\Controller;

use App\Entity\Proyecto;
use App\Form\ProyectoForm;
use App\Repository\ProyectoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_GESTOR')]
#[Route('/proyecto')]
final class ProyectoController extends AbstractController
{
    #[Route(name: 'app_proyecto_index', methods: ['GET'])]
    public function index(ProyectoRepository $proyectoRepository): Response
    {
        return $this->render('proyecto/index.html.twig', [
            'proyectos' => $proyectoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_proyecto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $proyecto = new Proyecto();
        $form = $this->createForm(ProyectoForm::class, $proyecto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($proyecto);
            $entityManager->flush();

            return $this->redirectToRoute('app_proyecto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('proyecto/new.html.twig', [
            'proyecto' => $proyecto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proyecto_show', methods: ['GET'])]
    public function show(Proyecto $proyecto): Response
    {
        return $this->render('proyecto/show.html.twig', [
            'proyecto' => $proyecto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_proyecto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Proyecto $proyecto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProyectoForm::class, $proyecto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_proyecto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('proyecto/edit.html.twig', [
            'proyecto' => $proyecto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proyecto_delete', methods: ['POST'])]
    public function delete(Request $request, Proyecto $proyecto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proyecto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($proyecto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_proyecto_index', [], Response::HTTP_SEE_OTHER);
    }
}