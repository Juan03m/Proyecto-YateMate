<?php

namespace App\Repository;

use App\Entity\Publicacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Publicacion>
 */
class PublicacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publicacion::class);
    }

       /**
         * @return Publicacion[] Returns an array of Publicacion objects
         */
        public function buscarPorTitulo($titulo): array
        {
            return $this->createQueryBuilder('p')
                ->innerJoin('p.embarcacion', 'e')
                ->leftJoin('e.solicitudes', 's', 'WITH', 's.aceptada = true')
                ->where(
                    $this->createQueryBuilder('p')->expr()->andX(
                        $this->createQueryBuilder('p')->expr()->like('p.titulo', ':val'),
                        $this->createQueryBuilder('p')->expr()->isNull('s.id')
                    )
                )
                ->setParameter('val', '%' . $titulo . '%')
                ->orderBy('p.fecha', 'ASC')
                ->getQuery()
                ->getResult();
        }


        

        public function findRelatedByTipoEmbarcacion(string $tipoEmbarcacion, int $publicacionId): array
        {
            return $this->createQueryBuilder('p')
                ->leftJoin('p.embarcacion', 'e')
                ->andWhere('e.Tipo = :tipoEmbarcacion')
                ->andWhere('p.id != :publicacionId')
                ->setParameter('tipoEmbarcacion', $tipoEmbarcacion)
                ->setParameter('publicacionId', $publicacionId)
                ->setMaxResults(4) // Limitamos el número de publicaciones relacionadas a 4
                ->getQuery()
                ->getResult();
        }



        public function findPublicacionesSinSolicitudAceptada(): array
        {
            return $this->createQueryBuilder('p')
                ->innerJoin('p.embarcacion', 'e')
                ->leftJoin('e.solicitudes', 's', 'WITH', 's.aceptada = true')
                ->where('s.id IS NULL')
                ->getQuery()
                ->getResult();
        }
    //    public function findOneBySomeField($value): ?Publicacion
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
