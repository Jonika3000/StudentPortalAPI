<?php

namespace App\Repository;

use App\Entity\Homework;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Homework>
 */
class HomeworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Homework::class);
    }

    public function saveAction(Homework $homework): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($homework);
        $entityManager->flush();
    }

    public function deleteAction(Homework $homework): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($homework);
        $entityManager->flush();
    }
}
