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

    public function deleteAction(StudentSubmission $studentSubmission): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($studentSubmission);
        $entityManager->flush();
    }
}
