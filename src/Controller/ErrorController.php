<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ErrorController extends AbstractController
{




public function handleException(\Exception $exception): Response
{
    //|                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     dd($exception);
    if ($exception instanceof NotFoundHttpException) {
        return $this->render('error/error404.html.twig');
    }
    elseif($exception instanceof AccessDeniedHttpException){
        return $this->render('error/accesoDenegado.html.twig');
    }
    else{
        return $this->render('error/error500.html.twig');
    }
}

public function show404Action(): Response
{
    // Renderizar la plantilla para el error 404
    return $this->render('error/error404.html.twig');
}

public function show500Action(): Response
{
    // Renderizar la plantilla para el error 500
    return $this->render('error/error500.html.twig');
}
}
