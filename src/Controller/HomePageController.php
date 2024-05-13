<?php

namespace App\Controller;

use App\Form\BusquedaType;
use App\Repository\PublicacionRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomePageController extends AbstractController
{


  
    #[Route('/', name: 'app_home_page')]
    public function index(PublicacionRepository $repositorioPublicaciones, Request $request): Response
    {
         
       
     
   
        $form = $this->createForm(BusquedaType::class);

        $form->handleRequest($request);
        
           if ($form->isSubmitted() && $form->isValid()) {

                    $data=$form->getData();
                    $publicaciones=$repositorioPublicaciones->buscarPorTitulo($data['titulo']);

           }

           else{
            $publicaciones=$repositorioPublicaciones->findAll();
           }
    


          
    return $this->render('dashboard/index.html.twig',[
        'publicaciones'=> $publicaciones,
        'form'=> $form,
    ]);

    }

    #[IsGranted('ROLE_CLIENT')]
    #[Route('/contacto', name: 'contacto')]
    public function action(): Response
    {
        return $this->render('dashboard/contacto.html.twig');
    }





}
