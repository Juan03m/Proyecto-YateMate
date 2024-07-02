<?php

namespace App\Controller;

use App\Entity\PublicacionAmarra;
use App\Form\OfertasTemporalesType;
use App\Form\PublicacionAmarraType;
use App\Repository\AmarraRepository;
use App\Repository\PublicacionAmarraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/publicacion/amarra')]
class PublicacionAmarraController extends AbstractController
{
    #[Route('/', name: 'app_publicacion_amarra_index', methods: ['GET','POST'])]
    public function index(PublicacionAmarraRepository $publicacionAmarraRepository,PublicacionAmarraRepository $ofertasTemporales,Request $request): Response
    {

        
        $marinas=['Norte','Centro','Sur','Este','Oeste','Delta','Bahia','Atlantico'];

        $tamaños=['Chica','Mediana','Grande'];

        $form = $this->createForm(OfertasTemporalesType::class);
        $form->handleRequest($request);
        $user=$this->getUser();
        $publicacionesDelUser=$ofertasTemporales->findPublicacionesPorUsuario($user->getId());

        
        $publicaciones=[];
        if($form->isSubmitted() && ($form->isValid())){

            $data=$form->getData();
           // dd($data);
            $desde=$data['desde'];
            $hasta=$data['hasta'];
            $tamaño=$data['tamano'];
            $marina=$data['marinas'];


            $ofertas=$ofertasTemporales->findPublicacionesDisponiblesEnPeriodo($desde,$hasta,$marina,$tamaño);

            $publicaciones=$ofertas;
         

           return  $this->render('publicacion_amarra/listado.html.twig',[
                'publicacion_amarras' => $publicaciones,
                'fecha_desde'=>$desde,
                'fecha_hasta'=>$hasta,
           ]);
            

        } 
        return $this->render('publicacion_amarra/index.html.twig', [
            'publicacion_amarras' => $publicaciones,
            'publicacionesDelUser'=>$publicacionesDelUser,
            'filtrado'=>$form,
        ]);
    }


    #[Route('/listado', name: 'app_publicacion_amarra_filtro')]
    public function action(): Response
    {
        return $this->render('publicacion_amarra/listado.html.twig');
    }



    #[Route('/new', name: 'app_publicacion_amarra_new', methods: ['GET', 'POST'])]
    public function new(AmarraRepository $amarraRepository, Request $request, EntityManagerInterface $entityManager, PublicacionAmarraRepository $publicacionAmarraRepository): Response
    {
        $usuario = $this->getUser();
        $amarras = $amarraRepository->findBy(['usuario' => $usuario]);
    
        if (empty($amarras)) {
            $this->addFlash('failed', 'No tienes amarras registradas a tu nombre. Si crees que esto es un error, comunícate con nosotros en nuestra página de contacto.');
            return $this->redirectToRoute('app_publicacion_amarra_index');
        }
    
        $publicacionAmarra = new PublicacionAmarra();
        $form = $this->createForm(PublicacionAmarraType::class, $publicacionAmarra,[
            'user'=>$usuario
        ]);
        $publicacionAmarra->setUsuario($usuario);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $amarra=$data->getAmarra();
            if ($amarra) {
                $existingPublicaciones = $publicacionAmarraRepository->findBy(['amarra' => $amarra]);

                foreach ($existingPublicaciones as $existingPublicacion) {
                    if (
                        ($data->getFechaDesde() >= $existingPublicacion->getFechaDesde() && $data->getFechaDesde() <= $existingPublicacion->getFechaHasta()) ||
                        ($data->getFechaHasta() >= $existingPublicacion->getFechaDesde() && $data->getFechaHasta() <= $existingPublicacion->getFechaHasta()) ||
                        ($data->getFechaDesde() <= $existingPublicacion->getFechaDesde() && $data->getFechaHasta() >= $existingPublicacion->getFechaHasta())
                    ) {
                        $this->addFlash('failed', 'La amarra ya tiene una publicación en el período seleccionado.');
                        return $this->redirectToRoute('app_publicacion_amarra_new');
                    }
                }
                $rutaImagen = match ($amarra->getMarina()) {
                    'Norte' => 'images/amarra_norte.png',
                    'Sur' => 'images/amarra_sur.png',
                    'Este' => 'images/amarra_este.png',
                    'Oeste' => 'images/amarra_oeste.png',
                    'Centro' => 'images/amarra_centro.png',
                    'Delta' => 'images/amarra_delta.png',
                    'Bahia' => 'images/amarra_bahia.png',
                    'Atlantico' => 'images/amarra_atlantico.png',
                };
            
                $publicacionAmarra->setImagen($rutaImagen);
                $publicacionAmarra->setEstaAlquilada(false);
                $publicacionAmarra->setEstaVigente(false);
                $publicacionAmarra->setNumero($amarra->getNumero());
                $publicacionAmarra->setMarina($amarra->getMarina());
                $publicacionAmarra->setSector($amarra->getSector());
                $publicacionAmarra->setTamano($amarra->getTamano());
                $publicacionAmarra->setUsuario($amarra->getUsuario());
                $publicacionAmarra->setAsistio(null);
            }
            $entityManager->persist($publicacionAmarra);
            $entityManager->flush();
    
            $this->addFlash('success', 'Publicación creada con éxito.');
    
            return $this->redirectToRoute('app_publicacion_amarra_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('publicacion_amarra/new.html.twig', [
            'publicacion_amarra' => $publicacionAmarra,
            'form' => $form->createView(),
        ]);
    }




    #[Route('/{id}', name: 'app_publicacion_amarra_show', methods: ['GET'])]
    public function show(PublicacionAmarra $publicacionAmarra, PublicacionAmarraRepository $publicacionAmarraRepository): Response
    {

        $fechasOcupadas = [];
        if (!$publicacionAmarra->getReservaAmarra()->isEmpty()){
            $fechasOcupadas = $publicacionAmarraRepository->getFechasOcupadas($publicacionAmarra->getId());
        }
        

        return $this->render('publicacion_amarra/show.html.twig', [
            'publicacion_amarra' => $publicacionAmarra,
            'fechas_ocupadas' => $fechasOcupadas,
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
        $this->addFlash('success', 'Publicación eliminada con éxito.');
        return $this->redirectToRoute('app_publicacion_amarra_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('verMisPublicaciones/{id}', name: 'app_publicacion_amarra_verMisPublicaciones')]
    public function publicacionesPorUsuario($id,PublicacionAmarraRepository $pr): Response
    {

        $publicaciones=$pr->findPublicacionesPorUsuario($id);



        return $this->render('template.html.twig',[
            'publicaciones'=>$publicaciones
        ]);
    }
}
