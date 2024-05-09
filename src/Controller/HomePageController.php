<?php

namespace App\Controller;

use App\Repository\PublicacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomePageController extends AbstractController
{


  
    #[Route('/', name: 'app_home_page')]
    public function index(PublicacionRepository $repositorioPublicaciones): Response
    {
            $publicaciones=$repositorioPublicaciones->findAll();


    
    return $this->render('dashboard/index.html.twig',[
        'publicaciones'=> $publicaciones
    ]);

    }

    #[IsGranted('ROLE_CLIENT')]
    #[Route('/contacto', name: 'contacto')]
    public function action(): Response
    {
        return $this->render('dashboard/contacto.html.twig');
    }





}
