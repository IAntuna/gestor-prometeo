<?php

namespace App\Controller;

use App\Entity\RegistroDeHoras;
use App\Form\RegistroDeHorasForm;
use App\Repository\RegistroDeHorasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/registro/de/horas')]
final class RegistroDeHorasController extends AbstractController
{
    #[Route(name: 'app_registro_de_horas_index', methods: ['GET'])]
    public function index(RegistroDeHorasRepository $registroDeHorasRepository): Response
    {
        return $this->render('registro_de_horas/index.html.twig', [
            'registro_de_horas' => $registroDeHorasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_registro_de_horas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $registroDeHora = new RegistroDeHoras();
        $form = $this->createForm(RegistroDeHorasForm::class, $registroDeHora);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($registroDeHora);
            $entityManager->flush();

            return $this->redirectToRoute('app_registro_de_horas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registro_de_horas/new.html.twig', [
            'registro_de_hora' => $registroDeHora,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_registro_de_horas_show', methods: ['GET'])]
    public function show(RegistroDeHoras $registroDeHora): Response
    {
        return $this->render('registro_de_horas/show.html.twig', [
            'registro_de_hora' => $registroDeHora,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_registro_de_horas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RegistroDeHoras $registroDeHora, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistroDeHorasForm::class, $registroDeHora);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_registro_de_horas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registro_de_horas/edit.html.twig', [
            'registro_de_hora' => $registroDeHora,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_registro_de_horas_delete', methods: ['POST'])]
    public function delete(Request $request, RegistroDeHoras $registroDeHora, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$registroDeHora->getId(), $request->request->get('_token'))) {
            $entityManager->remove($registroDeHora);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_registro_de_horas_index', [], Response::HTTP_SEE_OTHER);
    }
}
