<?php

namespace App\Controller;

use App\Repository\PublicacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomePageController extends AbstractController
{


  
    #[Route('/', name: 'app_home_page')]
    public function index(PublicacionRepository $repositorioPublicaciones): Response
    {

    
    return $this->render('dashboard/index.html.twig',[
    ]);

    }


    #[Route('/contacto', name: 'contacto')]
    public function action(): Response
    {
        return $this->render('dashboard/contacto.html.twig');
    }

}
