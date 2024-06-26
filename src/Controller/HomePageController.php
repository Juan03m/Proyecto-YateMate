<?php

namespace App\Controller;

use App\Form\BusquedaType;
use App\Entity\Usuario;
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
   // #[IsGranted('ROLE_USER')]
    public function index(PublicacionRepository $repositorioPublicaciones, Request $request): Response
    {
        $opciones=['Opcion 1','Opcion 2','Opcion 3'];

        $user=new Usuario;
        
        $user=$this->getUser();

    


        $form = $this->createForm(BusquedaType::class);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data=$form->getData();
            $publicaciones=$repositorioPublicaciones->buscarPorTitulo($data['titulo']);
        } else {
            $publicaciones=$repositorioPublicaciones->findPublicacionesSinSolicitudAceptada();
        }

        return $this->render('dashboard/index.html.twig',[
            'publicaciones'=> $publicaciones,
            'form'=> $form,
        ]);
    }

    #[Route('/contacto', name: 'contacto')]
    public function action(): Response
    {
        return $this->render('dashboard/contacto.html.twig');
    }


    #[Route('/acercade', name: 'acercade')]
    public function action2(): Response
    {
        return $this->render('dashboard/acercade.html.twig');
    }



}
