<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function saveUser(User $user): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function findUsersByBirthday(\DateTime $date): array
    {
        return $this->createQueryBuilder('u')
            ->where('MONTH(u.birthday) = :month')
            ->andWhere('DAY(u.birthday) = :day')
            ->setParameter('month', $date->format('m'))
            ->setParameter('day', $date->format('d'))
            ->getQuery()
            ->getResult();
    }

    public function updateUser(User $user): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function getFilteredUsersQuery(): QueryBuilder
    {
        $entityManager = $this->getEntityManager();
        $subqueryStudent = $entityManager->createQueryBuilder()
            ->select('IDENTITY(s.associatedUser)')
            ->from(Student::class, 's');

        $subqueryTeacher = $entityManager->createQueryBuilder()
            ->select('IDENTITY(t.associatedUser)')
            ->from(Teacher::class, 't');

        $qb = $entityManager->createQueryBuilder();
        $qb->select('u')
            ->from(User::class, 'u')
            ->where(
                $qb->expr()->notIn('u.id', $subqueryStudent->getDQL())
            )
            ->andWhere(
                $qb->expr()->notIn('u.id', $subqueryTeacher->getDQL())
            );

        return $qb;
    }
}
