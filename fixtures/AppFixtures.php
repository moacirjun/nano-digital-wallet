<?php

namespace App\DataFixtures;

use App\Entity\Transference;
use App\Entity\User;
use App\Entity\Wallet;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Users
        $user = new User();
        $user->setName('Mike');
        $user->setDocument('11111111111');
        $user->setEmail('mike@stranger.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setType(1);
        $user->setPassword($this->encoder->encodePassword($user, '123'));

        $user2 = new User();
        $user2->setName('Lucas');
        $user2->setDocument('22222222222');
        $user2->setEmail('lucas@stranger.com');
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setType(1);
        $user2->setPassword($this->encoder->encodePassword($user, '456'));

        $user3 = new User();
        $user3->setName('Will');
        $user3->setDocument('33333333333');
        $user3->setEmail('will@stranger.com');
        $user3->setRoles(['ROLE_ADMIN', 'ROLE_SHOPPER']);
        $user3->setType(2);
        $user3->setPassword($this->encoder->encodePassword($user, '798'));

        //Wallets
        $wallet = new Wallet();
        $wallet->setUser($user);
        $wallet->setOnHand(1000);
        $wallet->setOnHold(0);

        $wallet2 = new Wallet();
        $wallet2->setUser($user2);
        $wallet2->setOnHand(1000);
        $wallet2->setOnHold(0);

        $wallet3 = new Wallet();
        $wallet3->setUser($user3);
        $wallet3->setOnHand(1000);
        $wallet3->setOnHold(0);

        //Transference
        $transference = new Transference(
            null,
            Uuid::v4()->toRfc4122(),
            $wallet,
            $wallet3,
            250,
            1,
            new DateTimeImmutable()
        );

        $transference2 = new Transference(
            null,
            Uuid::v4()->toRfc4122(),
            $wallet2,
            $wallet3,
            350,
            1,
            new DateTimeImmutable()
        );

        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($wallet);
        $manager->persist($wallet2);
        $manager->persist($wallet3);
        $manager->persist($transference);
        $manager->persist($transference2);
        $manager->flush();
    }
}
