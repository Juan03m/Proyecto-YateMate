<?php

namespace App\Controller\Admin;

use App\Entity\Amarra;
use App\Entity\Embarcacion;
use App\Entity\Usuario;
use App\Entity\Bien;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $embarcacionesCount = $this->entityManager->getRepository(Embarcacion::class)->count([]);
        $amarrasCount = $this->entityManager->getRepository(Amarra::class)->count([]);
        $usuariosCount = $this->entityManager->getRepository(Usuario::class)->count([]);
        $bienesCount = $this->entityManager->getRepository(Bien::class)->count([]);

        return $this->render('admin/index.html.twig', [
            'embarcaciones_count' => $embarcacionesCount,
            'amarras_count' => $amarrasCount,
            'usuarios_count' => $usuariosCount,
            'bienes_count' => $bienesCount,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Menu Administrador');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Embarcaciones','fas fa-list',Embarcacion::class);
        //yield MenuItem::linkToCrud('Amarras', 'fas fa-list', Amarra::class);
        yield MenuItem::linkToCrud('Usuarios', 'fas fa-list', Usuario::class);
        //yield MenuItem::linkToCrud('Bienes', 'fas fa-list', Bien::class);
    }
}
