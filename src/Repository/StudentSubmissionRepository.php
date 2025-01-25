<?php

namespace App\Repository;

use App\Entity\StudentSubmission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentSubmission>
 */
class StudentSubmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentSubmission::class);
    }

    public function saveAction(StudentSubmission $studentSubmission): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($studentSubmission);
        $entityManager->flush();
    }

    //    /**
    //     * @return StudentSubmission[] Returns an array of StudentSubmission objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?StudentSubmission
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
