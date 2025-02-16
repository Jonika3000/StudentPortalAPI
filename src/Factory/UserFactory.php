<?php

namespace App\Factory;

use App\Constants\UserRoles;
use App\Entity\User;
use App\Enum\Gender;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function create(string $role): User
    {
        $faker = Factory::create();

        $user = new User();
        $user->setFirstName($faker->firstName());
        $user->setSecondName($faker->lastName());
        $user->setEmail($faker->unique()->safeEmail());
        $user->setAddress($faker->address());
        $user->setPhoneNumber($faker->phoneNumber());
        $user->setBirthday($faker->dateTimeBetween('-18 years', '-5 years'));
        $user->setGender($faker->randomElement([Gender::Male, Gender::Female]));
        $user->setRoles([UserRoles::USER, $role]);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

        return $user;
    }
}
