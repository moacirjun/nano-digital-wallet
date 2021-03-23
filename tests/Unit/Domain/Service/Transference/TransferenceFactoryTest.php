<?php

namespace App\Tests\Unit\Domain\Service\Transference;

use App\Domain\Service\Transference\TransferenceFactory;
use App\Domain\Model\User;
use App\Domain\Model\Wallet;
use App\Tests\Unit\BaseCodeceptionTestCase;

class TransferenceFactoryTest extends BaseCodeceptionTestCase
{
    public function testMakeFromUsers()
    {
        $service = $this->getContainer()->get(TransferenceFactory::class);
        $userRepository = $this->getEntityManager()->getRepository(User::class);

        $user1 = $userRepository->find(1);
        $user2 = $userRepository->find(3);

        $transference = $service->makeFromUsers($user1, $user2, 15.77);

        $this->assertEquals(15.77, $transference->getAmount());
        $this->assertEquals(1, $transference->getPayerWallet()->getUser()->getId());
        $this->assertEquals(3, $transference->getPayeeWallet()->getUser()->getId());
    }

    public function testMake()
    {
        $userRepository = $this->getEntityManager()->getRepository(User::class);

        $user1 = $userRepository->find(1);
        $user2 = $userRepository->find(3);

        $wallet1 = new Wallet();
        $wallet1->setUser($user1);
        $wallet1->setOnHand(100);
        $wallet1->setOnHold(0);

        $wallet2 = new Wallet();
        $wallet2->setUser($user2);
        $wallet2->setOnHand(200);
        $wallet2->setOnHold(0);

        $service = $this->getContainer()->get(TransferenceFactory::class);

        $transference = $service->make($wallet1, $wallet2, 12.99);

        $this->assertEquals(100, $transference->getPayerWallet()->getOnHand());
        $this->assertEquals(200, $transference->getPayeeWallet()->getOnHand());
        $this->assertEquals(1, $transference->getPayerWallet()->getUser()->getId());
        $this->assertEquals(3, $transference->getPayeeWallet()->getUser()->getId());
    }
}
