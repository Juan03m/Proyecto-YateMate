<?php

namespace App\Controller;

use App\Entity\Publicacion;
use App\Form\PublicacionType;
use App\Repository\PublicacionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Symfony\Component\Clock\now;

#[Route('/publicacion')]
class PublicacionController extends AbstractController
{
    #[Route('/', name: 'app_publicacion_index', methods: ['GET'])]
    public function index(PublicacionRepository $publicacionRepository): Response
    {





        return $this->render('publicacion/index.html.twig', [
            'publicaciones' => $publicacionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_publicacion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicacion = new Publicacion();  
        $user = $this->getUser();

        $form = $this->createForm(PublicacionType::class, $publicacion,['user'=>$user]);
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {

            $publicacion->setFecha(new \DateTime('now'));
            //agregar tambien que el id del usuario sea el que esta usando el form
            $publicacion->setUsuario($user);

            $entityManager->persist($publicacion);
            $entityManager->flush();


            return $this->redirectToRoute('app_publicacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publicacion/new.html.twig', [
            'publicacion' => $publicacion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publicacion_show', methods: ['GET'])]
    public function show(Publicacion $publicacion): Response
    {
        return $this->render('publicacion/show.html.twig', [
            'publicacion' => $publicacion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_publicacion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publicacion $publicacion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicacionType::class, $publicacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_publicacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publicacion/edit.html.twig', [
            'publicacion' => $publicacion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publicacion_delete', methods: ['POST'])]
    public function delete(Request $request, Publicacion $publicacion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publicacion->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($publicacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publicacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
