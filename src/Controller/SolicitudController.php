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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;

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
    public function new(Request $request, EntityManagerInterface $entityManager, $idPublicacion, $idBien, PublicacionRepository $pr, BienRepository $br,MailerInterface $mailer): Response
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
            $solicitud->setPublicacion($publicacion);
            $solicitud->setBien($bien);
            $solicitud->setAceptada(false);  // cambiar a false, lo puse solo para mostrar algo 


            $email = (new Email())
            ->from('GSQinteractive1@yopmail.com')
            ->to($solicitado->getEmail())
            ->subject('Información de Intercambios de YateMate!')
            ->text('Has recibido una solicitud de intercambio de embarcación, por favor revisa la pestaña de solicitudes');
            $mailer->send($email);


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
            'publicacion' => $solicitud->getPublicacion(),
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

    #[Route('/cancelar/{id}', name: 'app_solicitud_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Solicitud $solicitud, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        
       // if ($this->isCsrfTokenValid('delete'.$solicitud->getId(), $request->request->get('_token'))) {
            
            $this->addFlash('success', 'Acabas de cancelar una solicitud');
            
            $solicitado=$solicitud->getSolicitado();
            $solicitante=$solicitud->getSolicitante();
            $embarcacion=$solicitud->getPublicacion()->getEmbarcacion();


            $mensaje='La solicitud de intercambio de la embarcacion'.' '.$embarcacion->getNombre().' ha sido cancelada';
            $email = (new Email())
            ->from('GSQInteractive1@yopmail.com')
            ->to($solicitado->getEmail())
            ->subject('Informe de solicitudes!')
            ->text($mensaje);
            $mailer->send($email);



            $email = (new Email())
            ->from('GSQInteractive1@yopmail.com')
            ->to($solicitante->getEmail())
            ->subject('Informe de solicitudes!')
            ->text($mensaje);
            $mailer->send($email);





            $entityManager->remove($solicitud);
            $entityManager->flush();
      //  }

        return $this->redirectToRoute('app_solicitud_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/aceptar/{id}', name: 'app_solicitud_accept')]
    public function accept($id, SolicitudRepository $sr, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {
        $solicitud = $sr->find($id);

        $solicitud->setAceptada(true);

        $solicitante=$solicitud->getSolicitante();
        $solicitado=$solicitud->getSolicitado();
        $embarcacion=$solicitud->getEmbarcacion();
        $date = new \DateTime();
        $date->modify('+2 days');
        $dateString = $date->format('d/m/Y');
        $mensaje = 'La solicitud del intercambio de la embarcacion ' . $embarcacion->getNombre() . ' ha sido aceptada.  Por favor asistan a la empresa el día ' . $dateString . ' a las 17:00';
        $email = (new Email())
        ->from('GSQInteractive1@yopmail.com')
        ->to($solicitante->getEmail())
        ->subject('Informe de solicitudes!')
        ->text($mensaje);
        $mailer->send($email);

        $email = (new Email())
        ->from('GSQInteractive1@yopmail.com')
        ->to($solicitado->getEmail())
        ->subject('Informe de solicitudes!')
        ->text($mensaje);
        $mailer->send($email);









        $entityManager->flush();

        $this->addFlash('success', 'Acabas de aceptar una solicitud, el usuario dueño del bien ya fue notificado');

        /*
        Aca mandar mail a ambos users 
        */

        return $this->redirectToRoute('app_solicitud_index');
    }
}

