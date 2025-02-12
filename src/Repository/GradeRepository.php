<?php

namespace App\Repository;

use App\Entity\Grade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grade>
 */
// TODO: RENAME METHODS
class GradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    public function saveGrade(Grade $grade): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($grade);
        $entityManager->flush();
    }

    public function deleteGrade(Grade $grade): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($grade);
        $entityManager->flush();
    }

    //    /**
    //     * @return Grade[] Returns an array of Grade objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Grade
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
