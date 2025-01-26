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

    public function deleteAction(HomeworkFile $homeworkFile): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($homeworkFile);
        $entityManager->flush();
    }
}
