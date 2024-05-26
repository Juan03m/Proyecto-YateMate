<?php

namespace App\Controller;

use App\Entity\Solicitud;
use App\Form\SolicitudType;
use App\Repository\PublicacionRepository;
use App\Repository\SolicitudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/solicitud')]
class SolicitudController extends AbstractController
{
    #[Route('/', name: 'app_solicitud_index', methods: ['GET'])]
    public function index(SolicitudRepository $solicitudRepository): Response
    {
        return $this->render('solicitud/index.html.twig', [
            'solicituds' => $solicitudRepository->findAll(),
        ]);
    }

    #[Route('/new/{idPublicacion}', name: 'app_solicitud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,$idPublicacion,PublicacionRepository $pr): Response
    {

        $solicitud = new Solicitud();
        $usuario=$this->getUser();

        $form = $this->createForm(SolicitudType::class, $solicitud,[
            'user'=>$usuario
        ]);
        $form->handleRequest($request);

        $publicacion=$pr->find($idPublicacion);
       




        if ($form->isSubmitted() && $form->isValid()) {

            $solicitado=$publicacion->getUsuario();

            $solicitante=$usuario;

            $embarcacion=$publicacion->getEmbarcacion();
            
            $solicitud->setSolicitado($solicitado);
            $solicitud->setSolicitante($solicitante);
            $solicitud->setEmbarcacion($embarcacion);
            $solicitud->setAceptada(false);


            $this->addFlash('success','Acabas de solicitar un intercambio de embarcacion!, el dueño de la embarcacion ya fue notificado');
            
            $entityManager->persist($solicitud);
            $entityManager->flush();

            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('solicitud/new.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_solicitud_show', methods: ['GET'])]
    public function show(Solicitud $solicitud): Response
    {
        
        return $this->render('solicitud/show.html.twig', [
            'solicitud' => $solicitud,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_solicitud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Solicitud $solicitud, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SolicitudType::class, $solicitud);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('solicitud/edit.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_solicitud_delete', methods: ['POST'])]
    public function delete(Request $request, Solicitud $solicitud, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solicitud->getId(), $request->getPayload()->get('_token'))) {

            $entityManager->remove($solicitud);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/aceptar/{id}', name: 'app_solicitud_accept')]
    public function action($id,SolicitudRepository $sr): Response
    {
        $solicitud=$sr->find($id);

        $solicitud->setAceptada(true);

        $solicitado=$solicitud->getUsuario();

        $solicitante=$this->getUser();


        $this->addFlash('success','Acabas de aceptar una solicitud');

        
        /*

        Aca mandar mail a ambos users 
        */

        


        return $this->render('template.html.twig');
    }
}
