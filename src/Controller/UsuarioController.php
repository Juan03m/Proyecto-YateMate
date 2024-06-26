<?php

namespace App\Controller;

use App\Entity\Amarra;
use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\AmarraRepository;
use App\Repository\BienRepository;
use App\Repository\EmbarcacionRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/usuario')]
class UsuarioController extends AbstractController
{
    #[Route('/', name: 'app_usuario_index', methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_usuario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_usuario_show', methods: ['GET'])]
    public function show(Usuario $usuario,EmbarcacionRepository $er, AmarraRepository $ar,BienRepository $br): Response
    {

        $embarcaciones=$er->buscarPorUsuario($usuario);
        $bienes=$br->buscarPorUsuario($usuario);
        $amarras=$ar->buscarPorUsuario($usuario);
        
        

      // dd($embarcaciones);
        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
            'embarcaciones' => $embarcaciones,
            'bienes'=>$bienes,
            'amarras'=>$amarras
        ]);
    }


    #[Route('/{id}/edit', name: 'app_usuario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Usuario $usuario, EntityManagerInterface $entityManager): Response
    {
        
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $this->addFlash('success','Modificaste tu informacion correctamente');


            $entityManager->flush();
            

            return $this->redirectToRoute('app_home_page', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_usuario_delete', methods: ['POST'])]
    public function delete(Request $request, Usuario $usuario, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($usuario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/embarcaciones/{id}', name: 'app_usuario_embarcaciones')]
    public function action($id, EmbarcacionRepository $er ): Response
    {
        $embarcaciones=$er->buscarPorUsuario($id);

        return $this->render('template.html.twig');
    }


    






}
