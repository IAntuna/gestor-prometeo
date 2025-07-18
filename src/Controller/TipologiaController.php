<?php

namespace App\Controller;

use App\Entity\Tipologia;
use App\Form\TipologiaForm;
use App\Repository\TipologiaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/tipologia')]
final class TipologiaController extends AbstractController
{
    #[Route(name: 'app_tipologia_index', methods: ['GET'])]
    public function index(TipologiaRepository $tipologiaRepository): Response
    {
        return $this->render('tipologia/index.html.twig', [
            'tipologias' => $tipologiaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tipologia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tipologium = new Tipologia();
        $form = $this->createForm(TipologiaForm::class, $tipologium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tipologium);
            $entityManager->flush();

            return $this->redirectToRoute('app_tipologia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tipologia/new.html.twig', [
            'tipologium' => $tipologium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tipologia_show', methods: ['GET'])]
    public function show(Tipologia $tipologium): Response
    {
        return $this->render('tipologia/show.html.twig', [
            'tipologium' => $tipologium,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tipologia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tipologia $tipologium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipologiaForm::class, $tipologium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tipologia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tipologia/edit.html.twig', [
            'tipologium' => $tipologium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tipologia_delete', methods: ['POST'])]
    public function delete(Request $request, Tipologia $tipologium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tipologium->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tipologium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tipologia_index', [], Response::HTTP_SEE_OTHER);
    }
}
