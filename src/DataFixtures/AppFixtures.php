<?php

namespace App\DataFixtures;

use App\Constants\UserRoles;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserFactory $userFactory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->userFactory->create(UserRoles::STUDENT);
        $manager->persist($user);

        $manager->flush();
    }
}
