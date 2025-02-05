<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\USER;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new USER();
        $user->setName('test');
        $user->setEmail('test@example.com');
        $user->setRoles(["ROLE_USER"]);
        $user->setDateBirth(new \DateTime('1990-05-01'));

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'test');
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();
    }
}

