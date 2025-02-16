<?php

namespace App\Repository;

use App\Entity\Grade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grade>
 */
class GradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    public function saveAction(Grade $grade): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($grade);
        $entityManager->flush();
    }

    public function deleteAction(Grade $grade): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($grade);
        $entityManager->flush();
    }
}
