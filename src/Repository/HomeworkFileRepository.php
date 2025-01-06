<?php

namespace App\Repository;

use App\Entity\HomeworkFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HomeworkFile>
 */
class HomeworkFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomeworkFile::class);
    }

    public function saveAction(HomeworkFile $homeworkFile): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($homeworkFile);
        $entityManager->flush();
    }

    public function deleteAction(HomeworkFile $homework): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($homework);
        $entityManager->flush();
    }

    //    /**
    //     * @return HomeworkFile[] Returns an array of HomeworkFile objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?HomeworkFile
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
