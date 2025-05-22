<?php

// src/DataFixtures/UserFixture.php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->hasher->hashPassword($user, 'adminpass'));
        $manager->persist($user);
        $this->addReference('user-admin', $user);

        $user2 = new User();
        $user2->setEmail('user@example.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->hasher->hashPassword($user2, 'userpass'));
        $manager->persist($user2);
        $this->addReference('user-1', $user2);

        $manager->flush();
    }
}
