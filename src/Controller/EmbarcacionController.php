<?php

namespace App\Controller;

use App\Entity\Embarcacion;
use App\Form\EmbarcacionType;
use App\Repository\EmbarcacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/embarcacion')]
class EmbarcacionController extends AbstractController
{
    #[Route('/', name: 'app_embarcacion_index', methods: ['GET'])]
    public function index(EmbarcacionRepository $embarcacionRepository): Response
    {
        return $this->render('embarcacion/index.html.twig', [
            'embarcacions' => $embarcacionRepository->findAll(),
        ]);
    }

    
    #[Route('/new', name: 'app_embarcacion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $embarcacion = new Embarcacion();
        $form = $this->createForm(EmbarcacionType::class, $embarcacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($embarcacion);
            $entityManager->flush();

            return $this->redirectToRoute('app_embarcacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('embarcacion/new.html.twig', [
            'embarcacion' => $embarcacion,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}', name: 'app_embarcacion_show', methods: ['GET'])]
    public function show(Embarcacion $embarcacion): Response
    {
        return $this->render('embarcacion/show.html.twig', [
            'embarcacion' => $embarcacion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_embarcacion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Embarcacion $embarcacion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmbarcacionType::class, $embarcacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_embarcacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('embarcacion/edit.html.twig', [
            'embarcacion' => $embarcacion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_embarcacion_delete', methods: ['POST'])]
    public function delete(Request $request, Embarcacion $embarcacion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$embarcacion->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($embarcacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_embarcacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
