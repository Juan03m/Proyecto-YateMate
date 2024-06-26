<?php

namespace App\Repository;

use App\Entity\ReservaAmarra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservaAmarra>
 */
class ReservaAmarraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservaAmarra::class);
    }

    public function findReservasTerminadas(\DateTime $hoy): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.fechaHasta <= :hoy')
            ->setParameter('hoy', $hoy)
            ->getQuery()
            ->getResult();
    }

    public function findReservasPorUsuario($idUsuario)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.solicitante', 'u')
            ->andWhere('u.id = :idUsuario')
            ->setParameter('idUsuario', $idUsuario)
            ->getQuery()
            ->getResult();
    }



    



//    /**
//     * @return ReservaAmarra[] Returns an array of ReservaAmarra objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReservaAmarra
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
