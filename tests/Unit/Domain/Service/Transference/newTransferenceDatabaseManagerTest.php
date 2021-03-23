<?php

namespace App\Tests\Unit\Domain\Service\Transference;

use App\Domain\Model\Transference\PayerHasNoEnoughMoney;
use App\Domain\Service\Transference\newTransferenceDatabaseManager;
use App\Entity\Transference;
use App\Entity\Wallet;
use App\Tests\Unit\BaseCodeceptionTestCase;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class newTransferenceDatabaseManagerTest extends BaseCodeceptionTestCase
{
    /** @var newTransferenceDatabaseManager */
    private $service;

    /** @var Wallet */
    private $userWallet;

    /** @var Wallet */
    private $shopperWallet;

    protected function _before()
    {
        parent::_before();

        $this->userWallet = $this->getEntityManager()->getRepository(Wallet::class)->find(1);
        $this->shopperWallet = $this->getEntityManager()->getRepository(Wallet::class)->find(3);
        $this->service = $this->getContainer()->get(newTransferenceDatabaseManager::class);
    }

    public function testShouldThrowErrorWhenPayerHasNotEnoughMoney()
    {
        $transference = new Transference(
            null,
            Uuid::v4()->toRfc4122(),
            $this->userWallet,
            $this->shopperWallet,
            3000,
            1,
            new DateTimeImmutable()
        );

        $this->expectException(PayerHasNoEnoughMoney::class);
        $this->service->process($transference);
    }

    public function testShouldPersistTheTransferenceAndModifyWallets()
    {
        $this->userWallet->setOnHand(300);
        $this->shopperWallet->setOnHand(300);

        $this->getEntityManager()->persist($this->userWallet);
        $this->getEntityManager()->persist($this->shopperWallet);
        $this->getEntityManager()->flush();

        $transference = new Transference(
            null,
            Uuid::v4()->toRfc4122(),
            $this->userWallet,
            $this->shopperWallet,
            150,
            1,
            new DateTimeImmutable()
        );

        $this->service->process($transference);

        //Refresh from database
        $this->getEntityManager()->refresh($this->userWallet);
        $this->getEntityManager()->refresh($this->shopperWallet);
        $this->getEntityManager()->refresh($transference);

        $this->assertEquals(150, $this->userWallet->getOnHand());
        $this->assertEquals(450, $this->shopperWallet->getOnHand());
        $this->assertIsNumeric($transference->getId());
    }
}
