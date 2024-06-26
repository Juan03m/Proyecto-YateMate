<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicacionAmarraRepository;
use App\Repository\ReservaAmarraRepository;

#[AsCommand(
    name: 'app:actualizar-publicaciones',
    description: 'Elimina las publicaciones que han finalizado y están marcadas como no alquiladas.',
)]
class ActualizarPublicacionesCommand extends Command
{
    private $publicacionAmarraRepository;
    private $entityManager;
    private $reservaAmarraRepository;

    public function __construct(PublicacionAmarraRepository $publicacionAmarraRepository, EntityManagerInterface $entityManager, ReservaAmarraRepository $reservaAmarraRepository)
    {
        parent::__construct();
        $this->publicacionAmarraRepository = $publicacionAmarraRepository;
        $this->entityManager = $entityManager;
        $this->reservaAmarraRepository = $reservaAmarraRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hoy = new \DateTime();

        // Obtener todas las publicaciones que han finalizado y están marcadas como no alquiladas
        $publicaciones = $this->publicacionAmarraRepository->findPublicacionesTerminadas($hoy);
        $reservasViejas = $this->reservaAmarraRepository->findReservasTerminadas($hoy);
        
        foreach ($reservasViejas as $reserva){
            $this->entityManager->remove($reserva);
        }
        foreach ($publicaciones as $publicacion) {
            $this->entityManager->remove($publicacion);
        }
        

        $this->entityManager->flush();

        $output->writeln('Publicaciones actualizadas correctamente.');

        return Command::SUCCESS;
    }
}
