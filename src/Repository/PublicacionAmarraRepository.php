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
            ->andWhere('p.estaAlquilada = :alquilada')
            ->andWhere('p.fechaHasta <= :hoy')
            ->setParameter('alquilada', false)
            ->setParameter('hoy', $hoy->format('Y-m-d')) // Asegúrate de usar el formato correcto
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return PublicacionAmarra[] Returns an array of PublicacionAmarra objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

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
