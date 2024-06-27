<?php

namespace App\Repository;

use App\Entity\PublicacionAmarra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PublicacionAmarra>
 */
class PublicacionAmarraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublicacionAmarra::class);
    }

    public function findPublicacionesTerminadas(\DateTime $hoy): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.fechaHasta <= :hoy')
            ->setParameter('hoy', $hoy) // Asegúrate de usar el formato correcto
            ->getQuery()
            ->getResult();
    }





    /*
    public function getFechasOcupadas($publicacionAmarraId): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('r.fechaDesde, r.fechaHasta')
            ->leftJoin('p.reservaAmarra', 'r')
            ->where('p.id = :id')
            ->setParameter('id', $publicacionAmarraId)
            ->getQuery();

        $reservas = $qb->getResult();

        $fechasOcupadas = [];
        foreach ($reservas as $reserva) {
            $period = new \DatePeriod(
                new \DateTime($reserva['fechaDesde']),
                new \DateInterval('P1D'),
                (new \DateTime($reserva['fechaHasta']))->modify('+1 day')
            );

            foreach ($period as $date) {
                $fechasOcupadas[] = $date->format('Y-m-d');
            }
        }

        return $fechasOcupadas;
    }
    */

    /**
     * @return PublicacionAmarra[] Returns an array of PublicacionAmarra objects
     */
    public function filtrar($desde, $hasta,$marina,$tamaño): array
    {
        $qb = $this->createQueryBuilder('p');
      
        if ($desde != null) {
            $qb->andWhere('p.fechaDesde >= :desde')
               ->setParameter('desde', $desde);
        }
    
        if ($hasta != null) {
            $qb->andWhere('p.fechaHasta <= :hasta')
               ->setParameter('hasta', $hasta);
        }

        if ($tamaño != null) {
            $qb->andWhere('p.tamano = :tamano')
               ->setParameter('tamano', $tamaño);
        }
        if ($marina != null) {
            $qb->andWhere('p.marina = :marina')
               ->setParameter('marina', $marina);
        }

        return $qb->orderBy('p.id', 'ASC')

                  ->setMaxResults(10)
                  ->getQuery()
                  ->getResult();
    }
/**
 * @param \DateTimeInterface $fechaDesde
 * @param \DateTimeInterface $fechaHasta
 * @return PublicacionAmarra[]
 */
public function findPublicacionesDisponiblesEnPeriodo(\DateTimeInterface $fechaDesde, \DateTimeInterface $fechaHasta): array
{
    $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.reservaAmarra', 'r')
        ->where('p.fechaDesde <= :fechaHasta')
        ->andWhere('p.fechaHasta >= :fechaDesde')
        ->andWhere('(r.id IS NULL OR (r.fechaDesde > :fechaHasta OR r.fechaHasta < :fechaDesde))')
        ->setParameter('fechaDesde', $fechaDesde)
        ->setParameter('fechaHasta', $fechaHasta);

    return $qb->getQuery()->getResult();
}


    public function getFechasOcupadas($publicacionAmarraId): array
{
    $qb = $this->createQueryBuilder('p')
        ->select('r.fechaDesde, r.fechaHasta')
        ->leftJoin('p.reservaAmarra', 'r')
        ->where('p.id = :id')
        ->setParameter('id', $publicacionAmarraId)
        ->getQuery();

    $reservas = $qb->getResult();

    $fechasOcupadas = [];
    foreach ($reservas as $reserva) {
        $period = new \DatePeriod(
            new \DateTime($reserva['fechaDesde']->format('Y-m-d')),
            new \DateInterval('P1D'),
            (new \DateTime($reserva['fechaHasta']->format('Y-m-d')))->modify('+1 day')
        );

        foreach ($period as $date) {
            $fechasOcupadas[] = $date->format('Y-m-d');
        }
    }

    return $fechasOcupadas;
}
    

//    public function findOneBySomeField($value): ?PublicacionAmarra
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
