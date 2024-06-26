<?php

namespace App\Controller;

use App\Entity\Publicacion;
use App\Form\FiltradoPublicacionType;
use App\Form\PublicacionType;
use App\Form\BusquedaType;
use App\Form\PublicacionEditType;
use App\Repository\PublicacionRepository;
use DateTime;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BienRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function Symfony\Component\Clock\now;

#[Route('/publicacion')]
class PublicacionController extends AbstractController
{   
    #[Route('/', name: 'app_publicacion_index')]
    public function index(PublicacionRepository $repositorioPublicaciones, Request $request): Response
    {
        /*
            $marinas=['Punta Lara','Tigre','Concepcion del Uruguay'];

            $form = $this->createForm(FiltradoPublicacionType::class,null,[
                'marinas'=>array_flip($marinas)
            ]);
        */
        $opciones=['Opcion 1','Opcion 2','Opcion 3'];
        $form = $this->createForm(BusquedaType::class);//temporal hasta que pongamos filtros
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data=$form->getData();
                $publicaciones=$repositorioPublicaciones->buscarPorTitulo($data['titulo']);
          
       }
       else{
        $publicaciones=$repositorioPublicaciones->findPublicacionesSinSolicitudAceptada();
    }


        return $this->render('publicacion/index.html.twig', [
            'publicaciones' => $publicaciones,
            'form'=>$form
        ]);
    }

    #[IsGranted('ROLE_CLIENT')]
    #[Route('/new', name: 'app_publicacion_new', methods: ['GET', 'POST'])]
    #(i)
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicacion = new Publicacion();  
        $user = $this->getUser();
   

        $form = $this->createForm(PublicacionType::class, $publicacion,[
            'user'=>$user
        ]);
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();


            $archivo=$form->get('foto')->getData();

            if($archivo){
                $nombreArchivo = uniqid().'.'.$archivo->guessExtension();
                $archivo->move(
                    $this->getParameter('directorio_imagenes'), // Directorio destino
                    $nombreArchivo
                );

                $publicacion->setImage($nombreArchivo);
            }


            $publicacion->setFecha(new \DateTime('now'));
            //agregar tambien que el id del usuario sea el que esta usando el form
            $publicacion->setUsuario($user);

     
            $amarra=$data->getEmbarcacion()->getAmarra();
          
    
      


            if($amarra){
                    $publicacion->setMarina($amarra->getMarina());
                    $publicacion->setSector($amarra->getSector());
            }


            try {
                $entityManager->persist($publicacion);
                $entityManager->flush();
                $this->addFlash('success', 'Publicacion creada exitosamente!!');  
                      } 
            catch (UniqueConstraintViolationException){
                $this->addFlash('failed', 'No pudimos publicar la embarcacion!!');
                $this->redirectToRoute('app_publicacion_index', [], Response::HTTP_SEE_OTHER);
            }
    

           # $entityManager->persist($publicacion);
            #$entityManager->flush();

           
            return $this->redirectToRoute('app_publicacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('publicacion/new.html.twig', [
            'publicacion' => $publicacion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publicacion_show', methods: ['GET'])]
    public function show(Publicacion $publicacion,PublicacionRepository $publicacionRepository): Response
    {
 
    $tipoEmbarcacion = $publicacion->getEmbarcacion()->getTipo();
    $relatedPublications = $publicacionRepository->findRelatedByTipoEmbarcacion($tipoEmbarcacion, $publicacion->getId());

    return $this->render('publicacion/show.html.twig', [
        'publicacion' => $publicacion,
        'related_publications' => $relatedPublications, // Cambiar a 'related_publications'
    ]);
    
    
    }

    #[Route('/{id}/edit', name: 'app_publicacion_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Publicacion $publicacion, EntityManagerInterface $entityManager): Response
{

    




    $usuario=$this->getUser();
    $form = $this->createForm(PublicacionEditType::class, $publicacion,[
        'user'=>$usuario
    ]);
    $form->handleRequest($request);

  
    if ($form->isSubmitted() && $form->isValid()) {
        $archivo = $form->get('foto')->getData();
        

        if($archivo){
            $nombreArchivo = uniqid().'.'.$archivo->guessExtension();
            $archivo->move(
                $this->getParameter('directorio_imagenes'), // Directorio destino
                $nombreArchivo
            );

            $publicacion->setImage($nombreArchivo);
        }


        $entityManager->flush();
        
        $this->addFlash('success', 'Publicacion editada exitosamente!!');
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
        //dd($publicacion);
        if ($this->isCsrfTokenValid('delete'.$publicacion->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($publicacion);
            $entityManager->flush();
       }

        return $this->redirectToRoute('app_publicacion_index', [], Response::HTTP_SEE_OTHER);
    }



    public function buscarBienesParaOfrecer($bienes,$publicacion)
    {
        $resul=[];
        foreach($bienes as $bien){
            if (!$bien->hasAcceptedSolicitud()  && !$bien->fueOfrecido($publicacion->getId()) ){
                $resul[]=$bien;
            }
        }
        return $resul;
    }


    #[Route('/seleccionar-bien/{idPublicacion}', name: 'app_seleccionar_bien')]
    public function seleccionarBien($idPublicacion, PublicacionRepository $pr, BienRepository $br): Response
    {
        $publicacion = $pr->find($idPublicacion);
        $usuario = $this->getUser();

        // Obtener todas las solicitudes relacionadas con la publicación actual
        $solicitudes = $publicacion->getSolicitudes();
   

        // Obtener todos los bienes del usuario
        $bienes = $br->buscarPorUsuario($usuario);




       
        // Filtrar los bienes para mostrar solo aquellos que no han sido ofrecidos en las solicitudes de esta publicación
        $bienesDisponibles =$this->buscarBienesParaOfrecer($bienes,$publicacion);
        
        // si el bien no tiene solicitudes aceptadas y no se ofrecio a esta publicacion entra 



        return $this->render('publicacion/seleccionar_bien.html.twig', [
            'publicacion' => $publicacion,
            'bienes' => $bienesDisponibles,
        ]);
    }
    
}
