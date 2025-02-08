<?php

namespace App\Security\Voter;

use App\Entity\StudentSubmission;
use App\Entity\User;
use App\Repository\TeacherRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class GradeVoter extends Voter
{
    public const GRADE = 'grade';

    public function __construct(private readonly TeacherRepository $teacherRepository)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::GRADE === $attribute && $subject instanceof StudentSubmission;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $studentSubmission = $subject;
        $teacher = $this->teacherRepository->findOneBy(['associatedUser' => $user->getId()]);
        $lessonTeachers = $studentSubmission->getHomework()->getLesson()->getTeachers();
        if (!$lessonTeachers->contains($teacher)) {
            throw new \InvalidArgumentException('Teacher is not associated with the lesson.');
        }

        return false;
    }
}
