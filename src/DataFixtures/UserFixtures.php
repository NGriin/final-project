<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixtures
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->create(User::class, function (User $user) use ($manager) {
            $user
                ->setEmail('articleadmin@symfony.skillbox')
                ->setPhone('+79313667033')
                ->setFullName('Администратор')
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setRoles(['ROLE_ADMIN'])
                ->setAvatar('card.jpg');

            $manager->persist(new ApiToken($user));
        });

        $this->createMany(User::class, 5, function (User $user) use ($manager) {
            $user
                ->setEmail($this->faker->email)
                ->setPhone($this->faker->phoneNumber)
                ->setFullName($this->faker->name)
                ->setPassword($this->passwordHasher->hashPassword($user, '123456'))
                ->setRoles(['ROLE_USER'])
                ->setAvatar('card.jpg');

            $manager->persist(new ApiToken($user));
        });
    }
}
