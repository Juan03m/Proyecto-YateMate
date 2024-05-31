<?php


namespace App\Controller;

use App\Entity\Solicitud;
use App\Repository\BienRepository;
use App\Form\SolicitudType;
use App\Repository\PublicacionRepository;
use App\Repository\SolicitudRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/solicitud')]
class SolicitudController extends AbstractController
{
    #[Route('/', name: 'app_solicitud_index', methods: ['GET'])]
    public function index(SolicitudRepository $solicitudRepository): Response
    {

        $user=$this->getUser();
        $solicitudesEnviadas = $solicitudRepository->findBy(['solicitante' => $user]);
        $solicitudes=$solicitudRepository->findBySolicitante($user);
        $solicitudesRecibidas=$solicitudRepository->findBySolicitado($user);
        
        return $this->render('solicitud/index.html.twig', [
            'solicituds' => $solicitudes,
            'solicitudesEnviadas' => $solicitudesEnviadas,
            'solicitudesRecibidas' => $solicitudesRecibidas,
        ]);
    }

    #[Route('/new/{idPublicacion}/{idBien}', name: 'app_solicitud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $idPublicacion, $idBien, PublicacionRepository $pr, BienRepository $br): Response
    {
        $solicitud = new Solicitud();
        $usuario = $this->getUser();
       

        $cancelButton = $request->query->get('cancel');
        if ($cancelButton) {
        return $this->redirectToRoute('app_seleccionar_bien', ['idPublicacion' => $idPublicacion]);
        }

        $publicacion = $pr->find($idPublicacion);
        $bien = $br->find($idBien);

        $form = $this->createForm(SolicitudType::class, $solicitud, [
            'user' => $usuario,
            'selectedBien' => $bien,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitado = $publicacion->getUsuario();
            $solicitante = $usuario;
            $embarcacion = $publicacion->getEmbarcacion();

            $solicitud->setSolicitado($solicitado);
            $solicitud->setSolicitante($solicitante);
            $solicitud->setEmbarcacion($embarcacion);
            $solicitud->setBien($bien);
            $solicitud->setAceptada(false);

            $this->addFlash('success', 'Acabas de solicitar un intercambio de embarcación!, el dueño de la embarcación ya fue notificado');

            $entityManager->persist($solicitud);
            $entityManager->flush();

            return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('solicitud/new.html.twig', [
            'solicitud' => $solicitud,
            'form' => $form,
            'publicacion' => $publicacion,
        ]);
    }

    #[Route('/{id}', name: 'app_solicitud_show', methods: ['GET'])]
    public function show(Solicitud $solicitud): Response
    {
        return $this->render('solicitud/show.html.twig', [
            'solicitud' => $solicitud,
            'publicacion' => $solicitud->getEmbarcacion()->getPublicacion(),
            'bien' => $solicitud->getBien()
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

    #[Route('/cancelar/{id}', name: 'app_solicitud_delete', methods: ['POST'])]
    public function delete(Request $request, Solicitud $solicitud, EntityManagerInterface $entityManager,$id): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solicitud->getId(), $request->request->get('_token'))) {
            $this->addFlash('success', 'Acabas de borrar una solicitud');
            $entityManager->remove($solicitud);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/aceptar/{id}', name: 'app_solicitud_accept')]
    public function accept($id, SolicitudRepository $sr, EntityManagerInterface $entityManager): Response
    {
        $solicitud = $sr->find($id);

        $solicitud->setAceptada(true);

        $entityManager->flush();

        $this->addFlash('success', 'Acabas de aceptar una solicitud');

        /*
        Aca mandar mail a ambos users 
        */

        return $this->redirectToRoute('app_solicitud_index');
    }
}

