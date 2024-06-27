<?php

namespace App\Scheduler;

use App\Entity\Publicacion;
use App\Repository\PublicacionAmarraRepository;
use App\Repository\ReservaAmarraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Scheduler\Attribute\AsCronTask;
use Symfony\Component\Scheduler\Attribute\AsPeriodicTask;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCronTask('0 * * * *')]
 class ActualizarReservas {



    private $publicacionAmarraRepository;
    private $reservaAmarraRepository;
    private $entityManager;

    public function __construct(
        PublicacionAmarraRepository $publicacionAmarraRepository,
        ReservaAmarraRepository $reservaAmarraRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->publicacionAmarraRepository = $publicacionAmarraRepository;
        $this->reservaAmarraRepository = $reservaAmarraRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke()
    {
        $hoy = new \DateTime();

        // Obtener todas las publicaciones que han finalizado y están marcadas como no alquiladas
        $publicaciones = $this->publicacionAmarraRepository->findPublicacionesTerminadas($hoy);
        $reservasViejas = $this->reservaAmarraRepository->findReservasTerminadas($hoy);
        
        foreach ($reservasViejas as $reserva) {
            $this->entityManager->remove($reserva);
        }
        foreach ($publicaciones as $publicacion) {
            $this->entityManager->remove($publicacion);
        }
        
        $this->entityManager->flush();
 }




 }
