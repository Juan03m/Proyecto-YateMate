<?php

namespace App\Repository;

use App\Entity\Bien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bien>
 */
class BienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bien::class);
    }

        /**
         * @return Bien[] Returns an array of Bien objects
         */
        public function buscarPorUsuario($usuario): array
        {
            return $this->createQueryBuilder('e')
            ->andWhere('e.owner = :val')
            ->setParameter('val', $usuario)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
      }



      public function traerBienenNoOfrecidos($usuario){
        $qb = $this->createQueryBuilder('e');

        // Agregar la unión con la entidad Solicitud
        $qb->leftJoin('e.solicitudes', 's')
           ->andWhere('e.owner = :val')
           ->setParameter('val', $usuario)
           ->andWhere('s.aceptada IS NULL OR s.aceptada = false') // Solo incluir los bienes sin solicitudes aceptadas
           ->orderBy('e.id', 'ASC')
           ->setMaxResults(10);

        return $qb->getQuery()->getResult();

      }

    //    public function findOneBySomeField($value): ?Bien
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
