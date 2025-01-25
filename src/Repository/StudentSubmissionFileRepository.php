<?php

namespace App\Repository;

use App\Entity\StudentSubmissionFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentSubmissionFile>
 */
class StudentSubmissionFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentSubmissionFile::class);
    }

    public function saveAction(StudentSubmissionFile $studentSubmissionFile): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($studentSubmissionFile);
        $entityManager->flush();
    }
}
