<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/bien')]
class BienController extends AbstractController
{
    #[Route('/', name: 'app_bien_index', methods: ['GET'])]
    public function index(BienRepository $bienRepository): Response
    {
        return $this->render('bien/index.html.twig', [
            'biens' => $bienRepository->findAll(),
        ]);
    }
    #[IsGranted("ROLE_USER")]
    #[Route('/new', name: 'app_bien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bien = new Bien();
        $opciones=['Vehiculo','Inmueble','Tecnologia'];
        $form = $this->createForm(BienType::class, $bien,[
            'bienes'=> array_flip($opciones)
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $archivo = $form->get('foto')->getData();
    
            if ($archivo) {
                $nombreArchivo = uniqid().'.'.$archivo->guessExtension();
                $archivo->move(
                    $this->getParameter('directorio_imagenes'), // Directorio destino
                    $nombreArchivo
                );
    
                $bien->setImage($nombreArchivo);
            }
    
            $user = $this->getUser();  // me llevo el usuario actual 
            $bien->setOwner($user); 
    
            $entityManager->persist($bien);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('bien/new.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_show', methods: ['GET'])]
    public function show(Bien $bien): Response
    {
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bien_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Bien $bien, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(BienType::class, $bien);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $archivo = $form->get('foto')->getData();

        if ($archivo) {
            $nombreArchivo = uniqid().'.'.$archivo->guessExtension();
            $archivo->move(
                $this->getParameter('directorio_imagenes'), // Directorio destino
                $nombreArchivo
            );

            $bien->setImage($nombreArchivo);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('bien/edit.html.twig', [
        'bien' => $bien,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_bien_delete', methods: ['POST'])]
    public function delete(Request $request, Bien $bien, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($bien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bien_index', [], Response::HTTP_SEE_OTHER);
    }
}
