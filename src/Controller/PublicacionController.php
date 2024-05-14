<?php

namespace App\Controller;

use App\Entity\Publicacion;
use App\Form\FiltradoPublicacionType;
use App\Form\PublicacionType;
use App\Repository\PublicacionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function Symfony\Component\Clock\now;

#[Route('/publicacion')]
class PublicacionController extends AbstractController
{   
    #[Route('/', name: 'app_publicacion_index', methods: ['GET'])]
    public function index(PublicacionRepository $publicacionRepository): Response
    {

            


            $marinas=['Punta Lara','Tigre','Concepcion del Uruguay'];
            

            $form = $this->createForm(FiltradoPublicacionType::class,null,[
                'marinas'=>array_flip($marinas)
            ]);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $publicaciones=$publicacionRepository;
          
       }
       else{
            $publicaciones=$publicacionRepository->findAll();
       }


        return $this->render('publicacion/index.html.twig', [
            'publicaciones' => $publicaciones,
            'form'=>$form
        ]);
    }

    #[Route('/new', name: 'app_publicacion_new', methods: ['GET', 'POST'])]
    #(i)
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicacion = new Publicacion();  
        $user = $this->getUser();
   

        $form = $this->createForm(PublicacionType::class, $publicacion);
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
        //dd($publicacion);

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
