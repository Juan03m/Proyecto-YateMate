<?php

namespace App\Controller;

use App\Entity\PublicacionAmarra;
use App\Form\PublicacionAmarraType;
use App\Repository\PublicacionAmarraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/publicacion/amarra')]
class PublicacionAmarraController extends AbstractController
{
    #[Route('/', name: 'app_publicacion_amarra_index', methods: ['GET'])]
    public function index(PublicacionAmarraRepository $publicacionAmarraRepository): Response
    {
        return $this->render('publicacion_amarra/index.html.twig', [
            'publicacion_amarras' => $publicacionAmarraRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_publicacion_amarra_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicacionAmarra = new PublicacionAmarra();
        $form = $this->createForm(PublicacionAmarraType::class, $publicacionAmarra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($publicacionAmarra);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_publicacion_amarra_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('publicacion_amarra/new.html.twig', [
            'publicacion_amarra' => $publicacionAmarra,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publicacion_amarra_show', methods: ['GET'])]
    public function show(PublicacionAmarra $publicacionAmarra): Response
    {
        return $this->render('publicacion_amarra/show.html.twig', [
            'publicacion_amarra' => $publicacionAmarra,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publicacion_amarra_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PublicacionAmarra $publicacionAmarra, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicacionAmarraType::class, $publicacionAmarra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publicacion_amarra_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publicacion_amarra/edit.html.twig', [
            'publicacion_amarra' => $publicacionAmarra,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publicacion_amarra_delete', methods: ['POST'])]
    public function delete(Request $request, PublicacionAmarra $publicacionAmarra, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publicacionAmarra->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($publicacionAmarra);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publicacion_amarra_index', [], Response::HTTP_SEE_OTHER);
    }
}
