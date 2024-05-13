<?php

namespace App\Repository;

use App\Entity\Embarcacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Embarcacion>
 */
class EmbarcacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Embarcacion::class);
    }

  /**
     * @return Embarcacion[] Returns an array of Embarcacion objects
     */
    public function buscarPorUsuario($usuario): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.usuario = :val')
            ->setParameter('val', $usuario)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Embarcacion
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
